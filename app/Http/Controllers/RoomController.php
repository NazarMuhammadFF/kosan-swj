<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\Property;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Room::class);

        $query = Room::query()->with('property');

        // Filter by property
        if ($request->filled('property_id')) {
            $query->where('property_id', $request->property_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sort = $request->get('sort', 'code');
        $direction = $request->get('direction', 'asc');
        $query->orderBy($sort, $direction);

        $rooms = $query->paginate(20);
        $properties = Property::orderBy('name')->get(['id', 'name']);

        return view('rooms.index', compact('rooms', 'properties'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $this->authorize('create', Room::class);

        $propertyId = $request->query('property_id');
        $property = $propertyId ? Property::findOrFail($propertyId) : null;
        $properties = Property::orderBy('name')->get(['id', 'name']);

        return view('rooms.create', compact('properties', 'property'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoomRequest $request)
    {
        $validated = $request->validated();

        // Handle photo uploads
        if ($request->hasFile('photos')) {
            $photos = [];
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('rooms', 'public');
                $photos[] = $path;
            }
            $validated['photos'] = $photos;
        }

        // Create room
        $room = Room::create($validated);

        return redirect()
            ->route('rooms.show', $room)
            ->with('success', 'Kamar berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        $this->authorize('view', $room);

        $room->load([
            'property',
            'bookings' => fn($q) => $q->latest()->take(10),
            'bookings.tenant',
            'contract',
            'contract.tenant',
            'maintenanceTickets' => fn($q) => $q->latest()->take(5)
        ]);

        return view('rooms.show', compact('room'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room)
    {
        $this->authorize('update', $room);

        $properties = Property::orderBy('name')->get(['id', 'name']);

        return view('rooms.edit', compact('room', 'properties'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoomRequest $request, Room $room)
    {
        $validated = $request->validated();

        // Handle new photo uploads
        if ($request->hasFile('new_photos')) {
            $existingPhotos = $room->photos ?? [];
            
            foreach ($request->file('new_photos') as $photo) {
                $path = $photo->store('rooms', 'public');
                $existingPhotos[] = $path;
            }
            
            $validated['photos'] = $existingPhotos;
        }

        // Handle photo removals
        if ($request->filled('remove_photos')) {
            $existingPhotos = $room->photos ?? [];
            $removePhotos = $request->remove_photos;

            foreach ($removePhotos as $photoPath) {
                // Delete from storage
                if (Storage::disk('public')->exists($photoPath)) {
                    Storage::disk('public')->delete($photoPath);
                }
                
                // Remove from array
                $existingPhotos = array_values(array_diff($existingPhotos, [$photoPath]));
            }
            
            $validated['photos'] = $existingPhotos;
        }

        // Update room
        $room->update($validated);

        return redirect()
            ->route('rooms.show', $room)
            ->with('success', 'Kamar berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        $this->authorize('delete', $room);

        // Check if room has active contracts
        if ($room->contract()->exists()) {
            return redirect()
                ->back()
                ->with('error', 'Tidak dapat menghapus kamar yang memiliki kontrak aktif!');
        }

        // Delete photos
        if ($room->photos) {
            foreach ($room->photos as $photo) {
                if (Storage::disk('public')->exists($photo)) {
                    Storage::disk('public')->delete($photo);
                }
            }
        }

        // Delete room
        $room->delete();

        return redirect()
            ->route('properties.show', $room->property_id)
            ->with('success', 'Kamar berhasil dihapus!');
    }

    /**
     * Change room status
     */
    public function changeStatus(Request $request, Room $room)
    {
        $this->authorize('update', $room);

        $request->validate([
            'status' => 'required|in:available,occupied,maintenance,reserved'
        ]);

        // Use helper methods for status changes
        switch ($request->status) {
            case 'available':
                $room->markAsAvailable();
                break;
            case 'occupied':
                $room->markAsOccupied();
                break;
            case 'maintenance':
                $room->markAsMaintenance();
                break;
            case 'reserved':
                $room->markAsReserved();
                break;
        }

        return redirect()
            ->back()
            ->with('success', "Status kamar berhasil diubah menjadi: {$request->status}");
    }
}

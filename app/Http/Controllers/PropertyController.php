<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Property::class);

        $query = Property::with(['owner', 'rooms'])
            ->withCount(['rooms', 'reviews']);

        // Filter by owner (for owner role)
        if (auth()->user()->isOwner()) {
            $query->where('owner_id', auth()->id());
        }

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by city
        if ($request->filled('city')) {
            $query->byCity($request->city);
        }

        // Filter by gender type
        if ($request->filled('gender_type')) {
            $query->byGenderType($request->gender_type);
        }

        // Filter by published status
        if ($request->filled('is_published')) {
            $query->where('is_published', $request->is_published);
        }

        // Filter by featured
        if ($request->filled('is_featured')) {
            $query->where('is_featured', $request->is_featured);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $properties = $query->paginate(10)->withQueryString();

        return view('properties.index', compact('properties'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Property::class);

        return view('properties.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Property::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'phone' => 'nullable|string|max:20',
            'gender_type' => 'required|in:male,female,mixed',
            'facilities' => 'nullable|array',
            'facilities.*' => 'string',
            'rules' => 'nullable|array',
            'rules.*' => 'string',
            'deposit_amount' => 'required|numeric|min:0',
            'photos' => 'nullable|array|max:10',
            'photos.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'video_url' => 'nullable|url',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        // Handle photo uploads
        $photoUrls = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('properties/photos', 'public');
                $photoUrls[] = $path;
            }
        }

        // Create property
        $property = Property::create([
            'owner_id' => auth()->id(),
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'address' => $validated['address'],
            'city' => $validated['city'],
            'province' => $validated['province'],
            'postal_code' => $validated['postal_code'] ?? null,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'gender_type' => $validated['gender_type'],
            'facilities' => $validated['facilities'] ?? [],
            'rules' => $validated['rules'] ?? [],
            'deposit_amount' => $validated['deposit_amount'],
            'photos' => $photoUrls,
            'video_url' => $validated['video_url'] ?? null,
            'is_published' => $request->boolean('is_published', false),
            'is_featured' => $request->boolean('is_featured', false),
        ]);

        return redirect()
            ->route('properties.show', $property)
            ->with('success', 'Property berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Property $property)
    {
        $this->authorize('view', $property);

        $property->load([
            'owner',
            'rooms' => function ($query) {
                $query->withCount('contracts');
            },
            'reviews' => function ($query) {
                $query->published()->latest()->limit(5);
            },
        ]);

        // Get statistics
        $stats = [
            'total_rooms' => $property->rooms()->count(),
            'available_rooms' => $property->rooms()->available()->count(),
            'occupied_rooms' => $property->rooms()->occupied()->count(),
            'occupancy_rate' => $property->getOccupancyRate(),
            'average_rating' => $property->getAverageRating(),
            'total_reviews' => $property->getTotalReviews(),
            'price_range' => $property->getPriceRange(),
        ];

        return view('properties.show', compact('property', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Property $property)
    {
        $this->authorize('update', $property);

        return view('properties.edit', compact('property'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Property $property)
    {
        $this->authorize('update', $property);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'phone' => 'nullable|string|max:20',
            'gender_type' => 'required|in:male,female,mixed',
            'facilities' => 'nullable|array',
            'facilities.*' => 'string',
            'rules' => 'nullable|array',
            'rules.*' => 'string',
            'deposit_amount' => 'required|numeric|min:0',
            'new_photos' => 'nullable|array|max:10',
            'new_photos.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'remove_photos' => 'nullable|array',
            'remove_photos.*' => 'string',
            'video_url' => 'nullable|url',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        // Handle photo removals
        $existingPhotos = $property->photos ?? [];
        if ($request->filled('remove_photos')) {
            foreach ($request->remove_photos as $photoPath) {
                // Delete from storage
                if (Storage::disk('public')->exists($photoPath)) {
                    Storage::disk('public')->delete($photoPath);
                }
                // Remove from array
                $existingPhotos = array_values(array_filter($existingPhotos, fn($p) => $p !== $photoPath));
            }
        }

        // Handle new photo uploads
        if ($request->hasFile('new_photos')) {
            foreach ($request->file('new_photos') as $photo) {
                $path = $photo->store('properties/photos', 'public');
                $existingPhotos[] = $path;
            }
        }

        // Update property
        $property->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'address' => $validated['address'],
            'city' => $validated['city'],
            'province' => $validated['province'],
            'postal_code' => $validated['postal_code'] ?? null,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'gender_type' => $validated['gender_type'],
            'facilities' => $validated['facilities'] ?? [],
            'rules' => $validated['rules'] ?? [],
            'deposit_amount' => $validated['deposit_amount'],
            'photos' => $existingPhotos,
            'video_url' => $validated['video_url'] ?? null,
            'is_published' => $request->boolean('is_published', false),
            'is_featured' => $request->boolean('is_featured', false),
        ]);

        return redirect()
            ->route('properties.show', $property)
            ->with('success', 'Property berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Property $property)
    {
        $this->authorize('delete', $property);

        // Check if property has active contracts
        if ($property->contracts()->active()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus property yang masih memiliki kontrak aktif!');
        }

        // Delete photos from storage
        if (!empty($property->photos)) {
            foreach ($property->photos as $photoPath) {
                if (Storage::disk('public')->exists($photoPath)) {
                    Storage::disk('public')->delete($photoPath);
                }
            }
        }

        $property->delete();

        return redirect()
            ->route('properties.index')
            ->with('success', 'Property berhasil dihapus!');
    }

    /**
     * Toggle published status
     */
    public function togglePublish(Property $property)
    {
        $this->authorize('update', $property);

        $property->update([
            'is_published' => !$property->is_published,
        ]);

        $status = $property->is_published ? 'dipublikasikan' : 'di-draft';

        return back()->with('success', "Property berhasil {$status}!");
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(Property $property)
    {
        $this->authorize('update', $property);

        $property->update([
            'is_featured' => !$property->is_featured,
        ]);

        $status = $property->is_featured ? 'dijadikan unggulan' : 'dihapus dari unggulan';

        return back()->with('success', "Property berhasil {$status}!");
    }
}

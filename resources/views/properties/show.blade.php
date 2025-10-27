<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <a href="{{ route('properties.index') }}" class="mr-4 text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $property->name }}
                </h2>
            </div>
            @can('update', $property)
                <a href="{{ route('properties.edit', $property) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Edit Property
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Badges & Actions --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 flex justify-between items-center">
                    <div class="flex gap-3">
                        @if($property->is_published)
                            <span class="inline-flex items-center px-3 py-1 text-sm font-medium text-green-800 bg-green-100 rounded-full">
                                ✓ Published
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 text-sm font-medium text-gray-800 bg-gray-100 rounded-full">
                                Draft
                            </span>
                        @endif
                        
                        @if($property->is_featured)
                            <span class="inline-flex items-center px-3 py-1 text-sm font-medium text-yellow-800 bg-yellow-100 rounded-full">
                                ⭐ Unggulan
                            </span>
                        @endif

                        <span class="inline-flex items-center px-3 py-1 text-sm font-medium {{ $property->gender_type == 'male' ? 'text-blue-800 bg-blue-100' : ($property->gender_type == 'female' ? 'text-pink-800 bg-pink-100' : 'text-purple-800 bg-purple-100') }} rounded-full">
                            {{ $property->gender_type == 'male' ? '👨 Putra' : ($property->gender_type == 'female' ? '👩 Putri' : '👥 Campur') }}
                        </span>
                    </div>

                    @can('publish', $property)
                        <div class="flex gap-2">
                            <form method="POST" action="{{ route('properties.toggle-publish', $property) }}" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-3 py-2 {{ $property->is_published ? 'bg-gray-600' : 'bg-green-600' }} border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150">
                                    {{ $property->is_published ? 'Unpublish' : 'Publish' }}
                                </button>
                            </form>

                            <form method="POST" action="{{ route('properties.toggle-featured', $property) }}" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-3 py-2 {{ $property->is_featured ? 'bg-gray-600' : 'bg-yellow-600' }} border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150">
                                    {{ $property->is_featured ? 'Unfeature' : 'Feature' }}
                                </button>
                            </form>
                        </div>
                    @endcan
                </div>
            </div>

            {{-- Statistik --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm text-gray-600 mb-1">Total Kamar</div>
                        <div class="text-3xl font-bold text-gray-900">{{ $property->rooms_count }}</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm text-gray-600 mb-1">Kamar Tersedia</div>
                        <div class="text-3xl font-bold text-green-600">{{ $property->available_rooms_count }}</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm text-gray-600 mb-1">Tingkat Hunian</div>
                        <div class="text-3xl font-bold text-indigo-600">{{ number_format($property->getOccupancyRate(), 0) }}%</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm text-gray-600 mb-1">Total Review</div>
                        <div class="text-3xl font-bold text-yellow-600">{{ $property->reviews_count }}</div>
                    </div>
                </div>
            </div>

            {{-- Foto Property --}}
            @if($property->photos && count($property->photos) > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Foto Property</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach($property->photos as $photo)
                                <div class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden bg-gray-100">
                                    <img src="{{ asset('storage/' . $photo) }}" alt="Property photo" class="object-cover w-full h-48 rounded-lg hover:opacity-75 cursor-pointer transition-opacity">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Informasi Property --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Property</h3>
                        
                        <div class="space-y-3">
                            <div>
                                <div class="text-sm font-medium text-gray-500">Deskripsi</div>
                                <div class="text-gray-900 mt-1 whitespace-pre-line">{{ $property->description }}</div>
                            </div>

                            <div>
                                <div class="text-sm font-medium text-gray-500">Alamat Lengkap</div>
                                <div class="text-gray-900 mt-1">{{ $property->full_address }}</div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <div class="text-sm font-medium text-gray-500">Kota</div>
                                    <div class="text-gray-900 mt-1">{{ $property->city }}</div>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-500">Provinsi</div>
                                    <div class="text-gray-900 mt-1">{{ $property->province }}</div>
                                </div>
                            </div>

                            @if($property->postal_code)
                                <div>
                                    <div class="text-sm font-medium text-gray-500">Kode Pos</div>
                                    <div class="text-gray-900 mt-1">{{ $property->postal_code }}</div>
                                </div>
                            @endif

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <div class="text-sm font-medium text-gray-500">Telepon</div>
                                    <div class="text-gray-900 mt-1">{{ $property->phone }}</div>
                                </div>
                                @if($property->whatsapp)
                                    <div>
                                        <div class="text-sm font-medium text-gray-500">WhatsApp</div>
                                        <div class="text-gray-900 mt-1">{{ $property->whatsapp }}</div>
                                    </div>
                                @endif
                            </div>

                            @if($property->latitude && $property->longitude)
                                <div>
                                    <div class="text-sm font-medium text-gray-500">Koordinat</div>
                                    <div class="text-gray-900 mt-1">{{ $property->latitude }}, {{ $property->longitude }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Fasilitas & Peraturan --}}
                <div class="space-y-6">
                    @if($property->facilities && count($property->facilities) > 0)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Fasilitas</h3>
                                <ul class="space-y-2">
                                    @foreach($property->facilities as $facility)
                                        <li class="flex items-start">
                                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-gray-700">{{ $facility }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    @if($property->rules && count($property->rules) > 0)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Peraturan</h3>
                                <ul class="space-y-2">
                                    @foreach($property->rules as $rule)
                                        <li class="flex items-start">
                                            <svg class="w-5 h-5 text-red-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-gray-700">{{ $rule }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Kamar --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Daftar Kamar</h3>
                        <a href="{{ route('rooms.create', ['property_id' => $property->id]) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah Kamar
                        </a>
                    </div>

                    @if($property->rooms_count > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kamar</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga/Bulan</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($property->rooms as $room)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $room->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $room->status == 'available' ? 'bg-green-100 text-green-800' : '' }}
                                                    {{ $room->status == 'occupied' ? 'bg-red-100 text-red-800' : '' }}
                                                    {{ $room->status == 'maintenance' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                    {{ $room->status == 'reserved' ? 'bg-blue-100 text-blue-800' : '' }}">
                                                    {{ ucfirst($room->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp {{ number_format($room->price, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('rooms.show', $room) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Detail</a>
                                                <a href="{{ route('rooms.edit', $room) }}" class="text-gray-600 hover:text-gray-900">Edit</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            Belum ada kamar. <a href="{{ route('rooms.create', ['property_id' => $property->id]) }}" class="text-indigo-600 hover:text-indigo-900">Tambah kamar pertama</a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Danger Zone --}}
            @can('delete', $property)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-2 border-red-200">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-red-900 mb-2">Danger Zone</h3>
                        <p class="text-sm text-gray-600 mb-4">Menghapus property akan menghapus semua data terkait termasuk kamar, kontrak, dan transaksi.</p>
                        <form method="POST" action="{{ route('properties.destroy', $property) }}" onsubmit="return confirm('Anda yakin ingin menghapus property ini? Aksi ini tidak dapat dibatalkan!');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Hapus Property
                            </button>
                        </form>
                    </div>
                </div>
            @endcan
        </div>
    </div>
</x-app-layout>

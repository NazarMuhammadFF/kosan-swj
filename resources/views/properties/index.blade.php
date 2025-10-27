<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kelola Property') }}
            </h2>
            @can('create', App\Models\Property::class)
                <a href="{{ route('properties.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Property
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Success/Error Messages --}}
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            {{-- Filters --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('properties.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        {{-- Search --}}
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700">Cari</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Nama atau alamat..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        {{-- City --}}
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700">Kota</label>
                            <input type="text" name="city" id="city" value="{{ request('city') }}" placeholder="Jakarta..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        {{-- Gender Type --}}
                        <div>
                            <label for="gender_type" class="block text-sm font-medium text-gray-700">Tipe</label>
                            <select name="gender_type" id="gender_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Semua Tipe</option>
                                <option value="male" {{ request('gender_type') == 'male' ? 'selected' : '' }}>Putra</option>
                                <option value="female" {{ request('gender_type') == 'female' ? 'selected' : '' }}>Putri</option>
                                <option value="mixed" {{ request('gender_type') == 'mixed' ? 'selected' : '' }}>Campur</option>
                            </select>
                        </div>

                        {{-- Status --}}
                        <div>
                            <label for="is_published" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="is_published" id="is_published" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Semua Status</option>
                                <option value="1" {{ request('is_published') == '1' ? 'selected' : '' }}>Published</option>
                                <option value="0" {{ request('is_published') == '0' ? 'selected' : '' }}>Draft</option>
                            </select>
                        </div>

                        {{-- Buttons --}}
                        <div class="md:col-span-4 flex gap-2">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Filter
                            </button>
                            <a href="{{ route('properties.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Properties List --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($properties->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($properties as $property)
                                <div class="border rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-300">
                                    {{-- Property Image --}}
                                    <div class="h-48 bg-gray-200 relative">
                                        @if($property->main_photo)
                                            <img src="{{ asset('storage/' . $property->main_photo) }}" alt="{{ $property->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="flex items-center justify-center h-full text-gray-400">
                                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                                </svg>
                                            </div>
                                        @endif

                                        {{-- Badges --}}
                                        <div class="absolute top-2 left-2 flex gap-2">
                                            @if($property->is_featured)
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 rounded-full">
                                                    ⭐ Unggulan
                                                </span>
                                            @endif
                                            @if($property->is_published)
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">
                                                    ✓ Published
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-800 bg-gray-100 rounded-full">
                                                    Draft
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Property Info --}}
                                    <div class="p-4">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $property->name }}</h3>
                                        <p class="text-sm text-gray-600 mb-2">📍 {{ $property->city }}, {{ $property->province }}</p>
                                        
                                        <div class="flex items-center gap-4 text-sm text-gray-700 mb-3">
                                            <span>🚪 {{ $property->rooms_count }} Kamar</span>
                                            <span>⭐ {{ number_format($property->reviews_count) }} Review</span>
                                        </div>

                                        <div class="flex items-center justify-between">
                                            <span class="text-xs px-2 py-1 rounded-full {{ $property->gender_type == 'male' ? 'bg-blue-100 text-blue-800' : ($property->gender_type == 'female' ? 'bg-pink-100 text-pink-800' : 'bg-purple-100 text-purple-800') }}">
                                                {{ $property->gender_type == 'male' ? '👨 Putra' : ($property->gender_type == 'female' ? '👩 Putri' : '👥 Campur') }}
                                            </span>
                                        </div>

                                        {{-- Actions --}}
                                        <div class="mt-4 flex gap-2">
                                            <a href="{{ route('properties.show', $property) }}" class="flex-1 inline-flex justify-center items-center px-3 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                Detail
                                            </a>
                                            @can('update', $property)
                                                <a href="{{ route('properties.edit', $property) }}" class="inline-flex items-center px-3 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                    Edit
                                                </a>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-6">
                            {{ $properties->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada property</h3>
                            <p class="mt-1 text-sm text-gray-500">Mulai dengan membuat property baru.</p>
                            @can('create', App\Models\Property::class)
                                <div class="mt-6">
                                    <a href="{{ route('properties.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Tambah Property Pertama
                                    </a>
                                </div>
                            @endcan
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

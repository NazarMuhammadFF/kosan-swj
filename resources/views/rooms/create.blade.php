<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('rooms.index') }}" class="mr-4 text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Kamar Baru') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('rooms.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        {{-- Property Selection --}}
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Pilih Property</h3>
                            
                            <div class="mb-4">
                                <label for="property_id" class="block text-sm font-medium text-gray-700">Property <span class="text-red-500">*</span></label>
                                <select name="property_id" id="property_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('property_id') border-red-500 @enderror">
                                    <option value="">Pilih Property</option>
                                    @foreach($properties as $prop)
                                        <option value="{{ $prop->id }}" {{ old('property_id', $property?->id) == $prop->id ? 'selected' : '' }}>
                                            {{ $prop->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('property_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Informasi Dasar --}}
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Kamar</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Nama Kamar --}}
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Kamar <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="Contoh: Kamar A1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('name') border-red-500 @enderror">
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Kode Kamar --}}
                                <div>
                                    <label for="code" class="block text-sm font-medium text-gray-700">Kode Kamar</label>
                                    <input type="text" name="code" id="code" value="{{ old('code') }}" placeholder="Contoh: A-01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('code') border-red-500 @enderror">
                                    @error('code')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Lantai --}}
                                <div>
                                    <label for="floor" class="block text-sm font-medium text-gray-700">Lantai</label>
                                    <input type="number" name="floor" id="floor" value="{{ old('floor', 1) }}" min="0" max="100" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('floor') border-red-500 @enderror">
                                    @error('floor')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Ukuran --}}
                                <div>
                                    <label for="size" class="block text-sm font-medium text-gray-700">Ukuran (m²)</label>
                                    <input type="number" name="size" id="size" value="{{ old('size') }}" step="0.1" min="0" placeholder="Contoh: 12" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('size') border-red-500 @enderror">
                                    @error('size')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Kapasitas --}}
                                <div>
                                    <label for="capacity" class="block text-sm font-medium text-gray-700">Kapasitas <span class="text-red-500">*</span></label>
                                    <input type="number" name="capacity" id="capacity" value="{{ old('capacity', 1) }}" required min="1" max="10" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('capacity') border-red-500 @enderror">
                                    @error('capacity')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Status --}}
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                    <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('status') border-red-500 @enderror">
                                        <option value="available" {{ old('status', 'available') == 'available' ? 'selected' : '' }}>Available</option>
                                        <option value="occupied" {{ old('status') == 'occupied' ? 'selected' : '' }}>Occupied</option>
                                        <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                        <option value="reserved" {{ old('status') == 'reserved' ? 'selected' : '' }}>Reserved</option>
                                    </select>
                                    @error('status')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Harga --}}
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Harga & Biaya</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="price" class="block text-sm font-medium text-gray-700">Harga Sewa/Bulan <span class="text-red-500">*</span></label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">Rp</span>
                                        </div>
                                        <input type="number" name="price" id="price" value="{{ old('price') }}" required min="0" placeholder="1000000" class="block w-full pl-12 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('price') border-red-500 @enderror">
                                    </div>
                                    @error('price')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="electricity_cost" class="block text-sm font-medium text-gray-700">Biaya Listrik/Bulan</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">Rp</span>
                                        </div>
                                        <input type="number" name="electricity_cost" id="electricity_cost" value="{{ old('electricity_cost', 0) }}" min="0" placeholder="50000" class="block w-full pl-12 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('electricity_cost') border-red-500 @enderror">
                                    </div>
                                    @error('electricity_cost')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="water_cost" class="block text-sm font-medium text-gray-700">Biaya Air/Bulan</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">Rp</span>
                                        </div>
                                        <input type="number" name="water_cost" id="water_cost" value="{{ old('water_cost', 0) }}" min="0" placeholder="25000" class="block w-full pl-12 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('water_cost') border-red-500 @enderror">
                                    </div>
                                    @error('water_cost')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Deskripsi --}}
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi Kamar</label>
                            <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Foto Kamar --}}
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Foto Kamar</h3>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Foto (Max 10 foto, masing-masing max 2MB)</label>
                            <input type="file" name="photos[]" id="photos" multiple accept="image/jpeg,image/png,image/jpg,image/webp" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none @error('photos') border-red-500 @enderror @error('photos.*') border-red-500 @enderror">
                            <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG, WEBP. Ukuran maksimal 2MB per foto.</p>
                            @error('photos')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('photos.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Fasilitas --}}
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Fasilitas Kamar</h3>
                            <div id="facilities-container" class="space-y-2">
                                @if(old('facilities'))
                                    @foreach(old('facilities') as $index => $facility)
                                        <div class="flex gap-2 facility-item">
                                            <input type="text" name="facilities[]" value="{{ $facility }}" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Contoh: AC">
                                            <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                                Hapus
                                            </button>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="flex gap-2 facility-item">
                                        <input type="text" name="facilities[]" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Contoh: AC">
                                        <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                            Hapus
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <button type="button" id="add-facility" class="mt-2 inline-flex items-center px-3 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Tambah Fasilitas
                            </button>
                            @error('facilities')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex gap-3 pt-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Simpan Kamar
                            </button>
                            <a href="{{ route('rooms.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Add Facility
        document.getElementById('add-facility').addEventListener('click', function() {
            const container = document.getElementById('facilities-container');
            const newItem = document.createElement('div');
            newItem.className = 'flex gap-2 facility-item';
            newItem.innerHTML = `
                <input type="text" name="facilities[]" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Contoh: AC">
                <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                    Hapus
                </button>
            `;
            container.appendChild(newItem);
        });
    </script>
    @endpush
</x-app-layout>

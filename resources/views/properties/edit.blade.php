<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('properties.show', $property) }}" class="mr-4 text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Property: ') }} {{ $property->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('properties.update', $property) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        {{-- Informasi Dasar --}}
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Dasar</h3>
                            
                            {{-- Nama Property --}}
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700">Nama Property <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" value="{{ old('name', $property->name) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Deskripsi --}}
                            <div class="mb-4">
                                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi <span class="text-red-500">*</span></label>
                                <textarea name="description" id="description" rows="4" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('description') border-red-500 @enderror">{{ old('description', $property->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Tipe Gender --}}
                            <div class="mb-4">
                                <label for="gender_type" class="block text-sm font-medium text-gray-700">Tipe Penghuni <span class="text-red-500">*</span></label>
                                <select name="gender_type" id="gender_type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('gender_type') border-red-500 @enderror">
                                    <option value="">Pilih Tipe</option>
                                    <option value="male" {{ old('gender_type', $property->gender_type) == 'male' ? 'selected' : '' }}>Putra</option>
                                    <option value="female" {{ old('gender_type', $property->gender_type) == 'female' ? 'selected' : '' }}>Putri</option>
                                    <option value="mixed" {{ old('gender_type', $property->gender_type) == 'mixed' ? 'selected' : '' }}>Campur</option>
                                </select>
                                @error('gender_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Alamat --}}
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Alamat</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Alamat Lengkap --}}
                                <div class="md:col-span-2">
                                    <label for="address" class="block text-sm font-medium text-gray-700">Alamat Lengkap <span class="text-red-500">*</span></label>
                                    <textarea name="address" id="address" rows="2" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('address') border-red-500 @enderror">{{ old('address', $property->address) }}</textarea>
                                    @error('address')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Kota --}}
                                <div>
                                    <label for="city" class="block text-sm font-medium text-gray-700">Kota <span class="text-red-500">*</span></label>
                                    <input type="text" name="city" id="city" value="{{ old('city', $property->city) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('city') border-red-500 @enderror">
                                    @error('city')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Provinsi --}}
                                <div>
                                    <label for="province" class="block text-sm font-medium text-gray-700">Provinsi <span class="text-red-500">*</span></label>
                                    <input type="text" name="province" id="province" value="{{ old('province', $property->province) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('province') border-red-500 @enderror">
                                    @error('province')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Kode Pos --}}
                                <div>
                                    <label for="postal_code" class="block text-sm font-medium text-gray-700">Kode Pos</label>
                                    <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $property->postal_code) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('postal_code') border-red-500 @enderror">
                                    @error('postal_code')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Koordinat --}}
                                <div>
                                    <label for="latitude" class="block text-sm font-medium text-gray-700">Latitude</label>
                                    <input type="text" name="latitude" id="latitude" value="{{ old('latitude', $property->latitude) }}" placeholder="-6.200000" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('latitude') border-red-500 @enderror">
                                    @error('latitude')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="longitude" class="block text-sm font-medium text-gray-700">Longitude</label>
                                    <input type="text" name="longitude" id="longitude" value="{{ old('longitude', $property->longitude) }}" placeholder="106.816666" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('longitude') border-red-500 @enderror">
                                    @error('longitude')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Kontak --}}
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Kontak</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Telepon <span class="text-red-500">*</span></label>
                                    <input type="text" name="phone" id="phone" value="{{ old('phone', $property->phone) }}" required placeholder="08123456789" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('phone') border-red-500 @enderror">
                                    @error('phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="whatsapp" class="block text-sm font-medium text-gray-700">WhatsApp</label>
                                    <input type="text" name="whatsapp" id="whatsapp" value="{{ old('whatsapp', $property->whatsapp) }}" placeholder="08123456789" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('whatsapp') border-red-500 @enderror">
                                    @error('whatsapp')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Foto Property --}}
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Foto Property</h3>
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
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Fasilitas</h3>
                            <div id="facilities-container" class="space-y-2">
                                @if(old('facilities', $property->facilities))
                                    @foreach(old('facilities', $property->facilities) as $index => $facility)
                                        <div class="flex gap-2 facility-item">
                                            <input type="text" name="facilities[]" value="{{ $facility }}" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Contoh: WiFi gratis">
                                            <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                                Hapus
                                            </button>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="flex gap-2 facility-item">
                                        <input type="text" name="facilities[]" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Contoh: WiFi gratis">
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

                        {{-- Peraturan --}}
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Peraturan</h3>
                            <div id="rules-container" class="space-y-2">
                                @if(old('rules', $property->rules))
                                    @foreach(old('rules', $property->rules) as $index => $rule)
                                        <div class="flex gap-2 rule-item">
                                            <input type="text" name="rules[]" value="{{ $rule }}" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Contoh: Dilarang merokok">
                                            <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                                Hapus
                                            </button>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="flex gap-2 rule-item">
                                        <input type="text" name="rules[]" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Contoh: Dilarang merokok">
                                        <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                            Hapus
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <button type="button" id="add-rule" class="mt-2 inline-flex items-center px-3 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Tambah Peraturan
                            </button>
                            @error('rules')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status Publikasi --}}
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_published" value="1" {{ old('is_published', $property->is_published) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-600">Publikasikan property ini</span>
                            </label>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex gap-3 pt-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Simpan Property
                            </button>
                            <a href="{{ route('properties.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
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
                <input type="text" name="facilities[]" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Contoh: WiFi gratis">
                <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                    Hapus
                </button>
            `;
            container.appendChild(newItem);
        });

        // Add Rule
        document.getElementById('add-rule').addEventListener('click', function() {
            const container = document.getElementById('rules-container');
            const newItem = document.createElement('div');
            newItem.className = 'flex gap-2 rule-item';
            newItem.innerHTML = `
                <input type="text" name="rules[]" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Contoh: Dilarang merokok">
                <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                    Hapus
                </button>
            `;
            container.appendChild(newItem);
        });
    </script>
    @endpush
</x-app-layout>

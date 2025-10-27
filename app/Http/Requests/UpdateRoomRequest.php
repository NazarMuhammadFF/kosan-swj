<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('room'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $roomId = $this->route('room')->id;

        return [
            'property_id' => 'required|exists:properties,id',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:rooms,code,' . $roomId,
            'floor' => 'nullable|integer|min:0|max:100',
            'size' => 'nullable|numeric|min:0',
            'capacity' => 'required|integer|min:1|max:10',
            'price' => 'required|numeric|min:0',
            'electricity_cost' => 'nullable|numeric|min:0',
            'water_cost' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'facilities' => 'nullable|array',
            'facilities.*' => 'string|max:255',
            'new_photos' => 'nullable|array|max:10',
            'new_photos.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'remove_photos' => 'nullable|array',
            'remove_photos.*' => 'string',
            'status' => 'nullable|in:available,occupied,maintenance,reserved',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'property_id' => 'property',
            'name' => 'nama kamar',
            'code' => 'kode kamar',
            'floor' => 'lantai',
            'size' => 'ukuran',
            'capacity' => 'kapasitas',
            'price' => 'harga',
            'electricity_cost' => 'biaya listrik',
            'water_cost' => 'biaya air',
            'description' => 'deskripsi',
            'facilities' => 'fasilitas',
            'facilities.*' => 'item fasilitas',
            'new_photos' => 'foto baru',
            'new_photos.*' => 'file foto',
            'remove_photos' => 'foto yang dihapus',
            'status' => 'status',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'property_id.required' => 'Property harus dipilih.',
            'property_id.exists' => 'Property yang dipilih tidak valid.',
            'name.required' => 'Nama kamar wajib diisi.',
            'code.unique' => 'Kode kamar sudah digunakan.',
            'capacity.required' => 'Kapasitas kamar wajib diisi.',
            'capacity.min' => 'Kapasitas minimal 1 orang.',
            'price.required' => 'Harga sewa wajib diisi.',
            'price.min' => 'Harga tidak boleh negatif.',
            'new_photos.max' => 'Maksimal 10 foto.',
            'new_photos.*.image' => 'File harus berupa gambar.',
            'new_photos.*.mimes' => 'Format foto harus: jpeg, png, jpg, atau webp.',
            'new_photos.*.max' => 'Ukuran foto maksimal 2MB.',
        ];
    }
}

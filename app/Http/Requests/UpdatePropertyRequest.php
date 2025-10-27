<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePropertyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $property = $this->route('property');
        return $this->user()->can('update', $property);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'phone' => 'nullable|string|max:20',
            'gender_type' => 'required|in:male,female,mixed',
            'facilities' => 'nullable|array',
            'facilities.*' => 'string|max:100',
            'rules' => 'nullable|array',
            'rules.*' => 'string|max:500',
            'deposit_amount' => 'required|numeric|min:0|max:999999999',
            'new_photos' => 'nullable|array|max:10',
            'new_photos.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'remove_photos' => 'nullable|array',
            'remove_photos.*' => 'string',
            'video_url' => 'nullable|url|max:500',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
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
            'name' => 'nama property',
            'description' => 'deskripsi',
            'address' => 'alamat',
            'city' => 'kota',
            'province' => 'provinsi',
            'postal_code' => 'kode pos',
            'latitude' => 'latitude',
            'longitude' => 'longitude',
            'phone' => 'nomor telepon',
            'gender_type' => 'tipe gender',
            'facilities' => 'fasilitas',
            'rules' => 'peraturan',
            'deposit_amount' => 'jumlah deposit',
            'new_photos' => 'foto baru',
            'video_url' => 'URL video',
            'is_published' => 'status publikasi',
            'is_featured' => 'status unggulan',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama property wajib diisi.',
            'address.required' => 'Alamat wajib diisi.',
            'city.required' => 'Kota wajib diisi.',
            'province.required' => 'Provinsi wajib diisi.',
            'gender_type.required' => 'Tipe gender wajib dipilih.',
            'gender_type.in' => 'Tipe gender harus: Pria, Wanita, atau Campur.',
            'deposit_amount.required' => 'Jumlah deposit wajib diisi.',
            'new_photos.*.image' => 'File harus berupa gambar.',
            'new_photos.*.mimes' => 'Format gambar harus: JPEG, PNG, JPG, atau WEBP.',
            'new_photos.*.max' => 'Ukuran gambar maksimal 2MB.',
            'video_url.url' => 'URL video tidak valid.',
        ];
    }
}


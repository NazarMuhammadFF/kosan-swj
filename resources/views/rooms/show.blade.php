<x-app-layout>
    <div class="p-6">
        <a href="{{ route('rooms.index') }}" class="text-blue-600">← Kembali</a>
        <h1 class="text-2xl font-bold my-4">Kamar {{ $room->code }}</h1>
        <ul class="list-disc pl-6">
            <li>Ukuran: {{ $room->size_sqm }} m²</li>
            <li>Harga dasar: Rp {{ number_format($room->base_price,0,',','.') }}</li>
            <li>AC: {{ $room->has_ac ? 'Ya' : 'Tidak' }}</li>
            <li>Kamar mandi dalam: {{ $room->has_private_bath ? 'Ya' : 'Tidak' }}</li>
            <li>Catatan: {{ $room->notes ?? '-' }}</li>
        </ul>
    </div>
</x-app-layout>

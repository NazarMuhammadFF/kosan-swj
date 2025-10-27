<x-app-layout>
    @role('owner')
    <h1>Kamu pemilik kos.</h1>
    @elserole('tenant')
        <h1>Kamu penghuni kos.</h1>
    @endrole
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Daftar Kamar</h1>
        <table class="table-auto w-full border">
            <thead>
                <tr>
                    <th class="p-2 border">Kode</th>
                    <th class="p-2 border">Ukuran (m²)</th>
                    <th class="p-2 border">Harga/Bulan</th>
                    <th class="p-2 border">AC</th>
                    <th class="p-2 border">KM Dalam</th>
                </tr>
            </thead>
            <tbody>
            @foreach($rooms as $r)
                <tr>
                    <td class="p-2 border"><a class="text-blue-600" href="{{ route('rooms.show',$r) }}">{{ $r->code }}</a></td>
                    <td class="p-2 border">{{ $r->size_sqm }}</td>
                    <td class="p-2 border">Rp {{ number_format($r->base_price,0,',','.') }}</td>
                    <td class="p-2 border">{{ $r->has_ac ? 'Ya' : 'Tidak' }}</td>
                    <td class="p-2 border">{{ $r->has_private_bath ? 'Ya' : 'Tidak' }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $rooms->links() }}</div>
    </div>
</x-app-layout>

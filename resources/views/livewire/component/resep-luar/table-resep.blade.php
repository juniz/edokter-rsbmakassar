<div wire:init='load'>
    <x-ui.table :headers="['#', 'Nama Obat', 'Jumlah', 'Aturan Pakai', 'Aksi']" class="mt-3" dark>
        @foreach ($reseps as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->nama_brng }}</td>
                <td>{{ $item->jml }}</td>
                <td>{{ $item->aturan_pakai }}</td>
                <td>
                    <button class="btn btn-danger btn-sm" wire:click='confirmDelete("{{ $item->no_resep }}", "{{ $item->kode_brng }}")'>Hapus</button>
                </td>
            </tr>
        @endforeach
    </x-ui.table>
</div>

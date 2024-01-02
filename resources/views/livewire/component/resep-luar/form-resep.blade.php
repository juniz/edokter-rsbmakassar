<div>
    <form>
        <div x-data="{ jml:0 }">
            <template x-for="i+1 in jml" x-transition>
                <div class="row" :key="i">
                    <div wire:ignore class="col-md-5">
                        <x-ui.select label="Nama Obat" id="obat" />
                    </div>
                    <div class="col-md-2">
                        <x-ui.input label="Jumlah" id="jml" model="jml.{{ $i }}" />
                    </div>
                    <div class="col-md-4">
                        <x-ui.input label="Aturan Pakai" id="aturan" model="aturan.{{ $i }}" />
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger btn-sm" @click="jml--">Hapus</button>
                    </div>
                </div>
            </template>
            <div class="d-flex flex-row">
                <button type="button" class="btn btn-primary btn-sm" @click="jml++">Tambah</button>
                <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
            </div>
        </div>
    </form>
</div>

@push('js')
<script>
    $(document).ready(function() {
        $('#obat').select2({
            theme: 'bootstrap4',
        });
    });
</script>

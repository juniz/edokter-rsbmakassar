<div>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="resep-tab" data-toggle="tab" data-target="#resep" type="button"
                role="tab" aria-controls="resep" aria-selected="true">Resep</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="copyresep-tab" data-toggle="tab" data-target="#copyresep" type="button"
                role="tab" aria-controls="copyresep" aria-selected="false">Resep Racikan</button>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="resep" role="tabpanel" aria-labelledby="resep-tab">
            <div class="callout-info">
                <h5>Input Resep</h5>
                <form wire:submit.prevent='simpan'>
                    <div class="containerResep">
                        @for($i=1; $i <= $jmlForm; $i++) 
                        <div class="row row-{{$i}} pb-2">
                            <div wire:ignore class="col-md-6">
                                <select class="form-control obat-{{$i}} w-100" id="obat-{{$i}}"
                                    data-placeholder="Pilih Obat">
                                </select>
                            </div>
                            <div wire:ignore class="col-md-2">
                                <input type="number" id="jumlah-{{$i}}" name="jumlah[]" wire:model.defer='jumlah.{{ $i-1 }}' class="form-control" placeholder="Jumlah">
                            </div>
                            <div wire:ignore class="col-md-4">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" id="aturan-{{$i}}" name="aturan[]" wire:model.defer='aturan.{{ $i-1 }}' class="form-control"
                                            placeholder="Aturan Pakai">
                                        {{-- @if($i > 1)
                                        <div class="input-group-append">
                                            <a class="btn btn-danger" role="button">-</a>
                                        </div>
                                        @endif --}}
                                    </div>
                                </div>
                            </div>
                    </div>
                    @endfor
            </div>
            <div class="d-flex flex-row-reverse" style="gap: 10px">
                <button class="btn btn-primary" type="submit">Simpan</button>
                <button wire:ignore class="btn btn-success" wire:click='tambahForm' type="reset">+</button>
                <button class="btn btn-danger" wire:click='kurangiForm' type="reset">-</button>
            </div>
            </form>
        </div>
    </div>
</div>
</div>

@push('js')
<script>
    var poli = '';

    function formatData (data) {
        var $data = $(
            '<b>'+ data.id +'</b> - <i>'+ data.text +'</i>'
        );
        return $data;
    };

    window.addEventListener('livewire:load', function () {
        poli = @this.poli;
        console.log(poli);
        $('.obat-1').select2({
            placeholder: 'Pilih Obat',
            ajax: {
                url: '/api/ralan/obat',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                templateResult: formatData,
            },
            cache: true,
            minimumInputLength: 3
        }).on('select2:select', function (e) {
            var data = e.params.data;
            @this.addObat(data.id, 0);
        });
        
        // $('#iter').select2({
        //     placeholder: 'Pilih jumlah iter',
        //     allowClear: true,
        // });
    })
    
    window.livewire.on('tambahForm', (e) => {
        var i = e.jml;
        $('.obat-'+i).select2({
            placeholder: 'Pilih Obat',
            ajax: {
                url: '/api/ralan/obat',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                templateResult: formatData,
            },
            cache: true,
            minimumInputLength: 3
        }).on('select2:select', function (e) {
            var data = e.params.data;
            @this.addObat(data.id, i-1);
        });
    });

    window.livewire.on('resepLuarSimpan', (e) => {
        $('.obat-1').val(null).trigger('change');
    });
</script>
@endpush
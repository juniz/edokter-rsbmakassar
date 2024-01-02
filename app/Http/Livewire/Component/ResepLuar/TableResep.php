<?php

namespace App\Http\Livewire\Component\ResepLuar;

use Illuminate\Support\Facades\App;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class TableResep extends Component
{
    use LivewireAlert;
    public $no_rawat, $readyToLoad = false, $idObat, $noResep;
    protected $listeners = ['deleteObatLuar' => 'delete', 'refershTableResepLuar' => '$refresh'];

    public function mount($noRawat)
    {
        $this->no_rawat = $noRawat;
    }

    public function render()
    {
        return view('livewire.component.resep-luar.table-resep', [
            'reseps' =>$this->readyToLoad ? DB::table('resep_luar')
                        ->join('resep_luar_obat', 'resep_luar.no_resep', '=', 'resep_luar_obat.no_resep')
                        ->join('databarang', 'resep_luar_obat.kode_brng', '=', 'databarang.kode_brng')
                        ->where('no_rawat', $this->no_rawat)
                        ->select('databarang.nama_brng', 'resep_luar_obat.jml', 'resep_luar_obat.aturan_pakai', 'resep_luar_obat.no_resep', 'resep_luar_obat.kode_brng')
                        ->get() : []
        ]);
    }

    public function load()
    {
        $this->readyToLoad = true;
    }

    public function confirmDelete($noResep, $obat)
    {
        $this->noResep = $noResep;
        $this->idObat = $obat;
        $this->confirm('Yakin mau menghapus obat ini?', [
            'toast' => false,
            'timer' => '',
            'position' => 'center',
            'showConfirmButton' => true,
            'cancelButtonText' => '<i class="fas fa-times"></i> Batal',
            'onConfirmed' => 'deleteObatLuar',
        ]);
    }

    public function delete()
    {
        try{
            DB::table('resep_luar_obat')
                ->where('no_resep', $this->idObat)
                ->delete();

            $this->emit('refershTableResepLuar');
            $this->alert('success', 'Berhasil hapus data');
        }catch(\Exception $e){
            $this->alert('error', 'Gagal', [
                'position' =>  'center',
                'timer' =>  '',
                'toast' =>  false,
                'text' =>  App::environment('local') ? $e->getMessage() : 'Terjadi Kesalahan',
                'confirmButtonText' =>  'Oke'
            ]);
        }
    }
}

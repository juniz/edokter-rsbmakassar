<?php

namespace App\Http\Livewire\Ralan;

use App\Traits\SwalResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Resep extends Component
{
    use LivewireAlert;
    public $isCollapsed = true, $noRawat, $noRm, $swal = 'swal:resep', $poli, $jmlForm = 1, $form = [];
    public $obat = [], $jumlah = [], $aturan = [];

    public function mount($noRawat, $noRm)
    {
        $this->noRawat = $noRawat;
        $this->noRm = $noRm;
        $this->poli = session()->get('kd_poli');
        $this->dispatchBrowserEvent('poli-id', ['poli' => $this->poli]);
    }

    public function render()
    {
        return view('livewire.ralan.resep');
    }

    public function collapsed()
    {
        $this->isCollapsed = !$this->isCollapsed;
    }

    public function tambahForm()
    {
        $this->jmlForm++;
        $this->emit('tambahForm', ['jml' => $this->jmlForm]);
    }

    public function kurangiForm()
    {
        if($this->jmlForm > 1){
            Arr::pull($this->obat, $this->jmlForm - 1);
            Arr::pull($this->jumlah, $this->jmlForm - 1);
            Arr::pull($this->aturan, $this->jmlForm - 1);
            $this->jmlForm--;
        }
    }

    public function addObat($value, $index)
    {
        $this->obat[] = $value;
    }

    public function resetInput()
    {
        $this->resetExcept(['noRawat', 'noRm', 'poli', 'jmlForm', 'form', 'obat', 'jumlah', 'aturan']);
        $this->jmlForm = 1;
        $this->form = [];
        $this->obat = [];
        $this->jumlah = [];
        $this->aturan = [];
    }

    public function simpan()
    {
        // dd($this->obat, $this->jumlah, $this->aturan);
        try{
            DB::beginTransaction();
            $no_resep = '';
            $cek = DB::table('resep_luar')
                    ->where('no_rawat', $this->noRawat)
                    ->first();
            if($cek){
                $no_resep = $cek->no_resep;
            }else{
                $no = DB::table('resep_luar')->where('tgl_perawatan', 'like', '%' . date('Y-m-d') . '%')->orWhere('tgl_peresepan', 'like', '%' . date('Y-m-d') . '%')->selectRaw("ifnull(MAX(CONVERT(RIGHT(no_resep,3),signed)),0) as resep")->first();
                $maxNo = substr($no->resep, 0, 3);
                $nextNo = sprintf('%03s', ($maxNo + 1));
                $tgl = date('Ymd');
                $no_resep = 'RL'.$tgl . '' . $nextNo;

                DB::table('resep_luar')
                    ->insert([
                        'no_resep' => $no_resep,
                        'tgl_perawatan' => '0000-00-00',
                        'jam' => '00:00:00',
                        'no_rawat' => $this->noRawat,
                        'kd_dokter' => session()->get('username'),
                        'tgl_peresepan' => date('Y-m-d'),
                        'jam_peresepan' => date('H:i:s'),
                    ]);
            }
            for ($i = 0; $i < count($this->obat); $i++) {
                $data[] = [
                    'no_resep' => $no_resep,
                    'kode_brng' => $this->obat[$i],
                    'jml' => $this->jumlah[$i],
                    'aturan_pakai' => $this->aturan[$i],
                ];
            }

            DB::table('resep_luar_obat')
                ->insert($data);

            DB::commit();
            $this->resetInput();
            $this->emit('resepLuarSimpan');
            $this->alert('success', 'Data berhasil disimpan');

        }catch(\Exception $e){
            DB::rollBack();
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

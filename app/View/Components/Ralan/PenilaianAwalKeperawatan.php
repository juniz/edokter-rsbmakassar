<?php

namespace App\View\Components\Ralan;

use Illuminate\View\Component;
use Illuminate\Support\Facades\DB;
class PenilaianAwalKeperawatan extends Component
{
    protected $noRawat;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($noRawat)
    {
        $this->noRawat=$noRawat;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.ralan.penilaian-awal-keperawatan',[
            'data' => $this->getPenilaianAwalKeperawatan(),
        ]);
    }

    public function getPenilaianAwalKeperawatan()
    {
        $penilaianAwalKeperawatan = DB::table('penilaian_awal_keperawatan_ralan')
            ->where('no_rawat', $this->noRawat)
            ->first();

        return $penilaianAwalKeperawatan;
    }
}

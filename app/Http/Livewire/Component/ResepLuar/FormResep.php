<?php

namespace App\Http\Livewire\Component\ResepLuar;

use Livewire\Component;

class FormResep extends Component
{
    public $obat, $jml = [], $aturan = [];
    public function render()
    {
        return view('livewire.component.resep-luar.form-resep');
    }
}

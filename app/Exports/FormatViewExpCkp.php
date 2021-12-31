<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FormatViewExpCkp implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private $data;
    private $tahun;
    private $waktu;

    public function __construct($data,$tahun,$waktu)
    {
        $this->data = $data;
        $this->tahun = $tahun;
        $this->waktu = $waktu;
    }

    public function view(): View
    {
        return view('peringkat.exp-ckp-xml', [
            'data' => $this->data,
            'tahun'=> $this->tahun,
            'waktu'=>$this->waktu
        ]);
    }
}

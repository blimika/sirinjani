<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FormatViewExim implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private $data;
    private $tahun;
    private $menurut;
    private $waktu;

    public function __construct($data,$tahun,$menurut,$waktu)
    {
        $this->data = $data;
        $this->tahun = $tahun;
        $this->menurut = $menurut;
        $this->waktu = $waktu;
    }

    public function view(): View
    {
        return view('peringkat.export-xml', [
            'data' => $this->data,
            'tahun'=> $this->tahun,
            'menurut'=>$this->menurut,
            'waktu'=>$this->waktu
        ]);
    }
}

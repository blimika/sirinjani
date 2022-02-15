<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FormExpRincian implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private $data;
    private $judul;
    private $waktu;

    public function __construct($data,$judul,$waktu)
    {
        $this->data = $data;
        $this->judul = $judul;
        $this->waktu = $waktu;
    }

    public function view(): View
    {
        return view('peringkat.exp-rincian-xml', [
            'data' => $this->data,
            'judul'=> $this->judul,
            'waktu'=>$this->waktu
        ]);
    }
}

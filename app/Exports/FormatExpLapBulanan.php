<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FormatExpLapBulanan implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private $data;
    private $judul;
    private $waktu;
    private $catatan;

    public function __construct($data,$judul,$waktu,$catatan)
    {
        $this->data = $data;
        $this->judul = $judul;
        $this->waktu = $waktu;
        $this->catatan = $catatan;
    }

    public function view(): View
    {
        return view('laporan.exp-bulanan-xml', [
            'data' => $this->data,
            'judul'=> $this->judul,
            'waktu'=>$this->waktu,
            'catatan'=>$this->catatan
        ]);
    }
}

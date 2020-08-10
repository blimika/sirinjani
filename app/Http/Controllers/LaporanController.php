<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UnitKerja;
use DB;


class LaporanController extends Controller
{
    //
    public function bulanan()
    {
        $data_bulan = array(
            1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'
        );
        $dataUnit = UnitKerja::where([['unit_jenis','=','1'],['unit_eselon','=','3']])->get();
        //$unitFilter = $dataUnit->first();
        $data_tahun = DB::table('m_keg')
        ->selectRaw('year(keg_end) as tahun')
        ->groupBy('tahun')
        ->whereYear('keg_end','<=',NOW())
        ->orderBy('tahun','asc')
          ->get();

        if (request('tahun')<=0)
        {
            $tahun_filter=date('Y');
        }
        else
        {
            $tahun_filter = request('tahun');
        }
        return view('laporan.bulanan',['dataUnitkerja'=>$dataUnit,'dataTahun'=>$data_tahun,'tahun'=>$tahun_filter,'unit'=>request('unit'),'dataBulan'=>$data_bulan]);
    }
}

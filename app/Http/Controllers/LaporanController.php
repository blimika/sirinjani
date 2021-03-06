<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Helpers\CommunityBPS;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\KodeWilayah;
use App\KodeLevel;
use App\UnitKerja;
use Excel;
use App\Kegiatan;
use App\KegDetil;
use App\KegJenis;
use App\KegRealisasi;
use App\KegTarget;
use App\SpjTarget;
use App\Helpers\Generate;
use App\Helpers\Tanggal;
use App\SpjRealisasi;


class LaporanController extends Controller
{
    //
    public function bulanan()
    {
        $data_bulan = array(
            1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'
        );
        $dataUnit = UnitKerja::where([['unit_jenis','=','1'],['unit_eselon','=','3']])->get();
        $dataSubFungsi = UnitKerja::where([['unit_jenis','=','1'],['unit_eselon','=','4']])->get();
        //dd($dataUnit);
        $unitFilter = $dataUnit->first();
        $data_tahun = DB::table('m_keg')
        ->selectRaw('year(keg_end) as tahun')
        ->groupBy('tahun')
        ->whereYear('keg_end','<=',NOW())
        ->orderBy('tahun','asc')
          ->get();
        if (request('bulan')<=0)
        {
            $bulan_filter=date('m');
        }
        else
        {
            $bulan_filter = request('bulan');
        }
        if (request('tahun')<=0)
        {
            $tahun_filter=date('Y');
        }
        else
        {
            $tahun_filter = request('tahun');
        }
        if (request('unit')<=0)
        {
            $unit_filter = $unitFilter->unit_kode;
        }
        else
        {
            $unit_filter = request('unit');
        }
        $dataUnitNama = UnitKerja::where('unit_kode',$unit_filter)->first();
        $unit_nama = $dataUnitNama->unit_nama;
        $data = Kegiatan::when($bulan_filter,function ($query) use ($bulan_filter) {
            return $query->whereMonth('keg_end','=',$bulan_filter);
        })
        ->whereYear('keg_end','=',$tahun_filter)->orderBy('keg_unitkerja','asc')->get();
        //dd($data);
        return view('laporan.bulanan',['dataUnitkerja'=>$dataUnit,'dataTahun'=>$data_tahun,'tahun'=>$tahun_filter,'unit'=>$unit_filter,'unit_nama'=>$unit_nama,'dataBulan'=>$data_bulan,'bulan'=>$bulan_filter,'dataSubFungsi'=>$dataSubFungsi,'data'=>$data]);
    }
    public function tahunan()
    {
        $data_bulan = array(
            1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'
        );
        $dataUnit = UnitKerja::where([['unit_jenis','=','1'],['unit_eselon','=','3']])->get();
        $dataSubFungsi = UnitKerja::where([['unit_jenis','=','1'],['unit_eselon','=','4']])->get();
        //dd($dataUnit);
        $unitFilter = $dataUnit->first();
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
        if (request('unit')<=0)
        {
            $unit_filter = $unitFilter->unit_kode;
        }
        else
        {
            $unit_filter = request('unit');
        }
        $dataUnitNama = UnitKerja::where('unit_kode',$unit_filter)->first();
        $unit_nama = $dataUnitNama->unit_nama;
        $data = Kegiatan::whereYear('keg_end','=',$tahun_filter)->orderBy('keg_end','asc')->get();
        //dd($data);
        return view('laporan.tahunan',['dataUnitkerja'=>$dataUnit,'dataTahun'=>$data_tahun,'tahun'=>$tahun_filter,'unit'=>$unit_filter,'unit_nama'=>$unit_nama,'dataBulan'=>$data_bulan,'dataSubFungsi'=>$dataSubFungsi,'data'=>$data]);
    }
}

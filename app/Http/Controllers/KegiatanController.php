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
use App\KegTarget;
use App\KegSpj;

class KegiatanController extends Controller
{
    //
    public function index()
    {
        $data_bulan = array(
            1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'
        );
        $data_tahun = DB::table('kegiatan')
                    ->selectRaw('year(keg_start) as tahun')
                    ->groupBy('tahun')
                    ->orderBy('tahun','asc')
                      ->get();
        //dd($data_tahun);
        if (request('tahun')==NULL)
        {
            $tahun_filter=date('Y');
        }
        else
        {
            $tahun_filter = request('tahun');
        }
        $dataUnit = UnitKerja::where([['unit_jenis','=','1'],['unit_eselon','=','3']])->get();
        $dataKegiatan = Kegiatan::leftJoin('t_unitkerja','kegiatan.keg_unitkerja','=','t_unitkerja.unit_kode')
                        ->when(request('bulan'),function ($query){
                            return $query->whereMonth('keg_start','=',request('bulan'));
                        })
                        ->orderBy('kegiatan.created_at','desc')->whereYear('keg_start','=',$tahun_filter)->get();
        //dd($dataKegiatan);
        return view('kegiatan.index',['dataKeg'=>$dataKegiatan,'dataUnitkerja'=>$dataUnit,'bulan'=>request('bulan'),'tahun'=>$tahun_filter,'dataBulan'=>$data_bulan,'dataTahun'=>$data_tahun]);
    }

    public function bidang()
    {
        $data_bulan = array(
            1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'
        );
        $data_bidang = 
        $data_tahun = DB::table('kegiatan')
                    ->selectRaw('year(keg_start) as tahun')
                    ->groupBy('tahun')
                    ->orderBy('tahun','asc')
                      ->get();
        //dd($data_tahun);
        if (request('tahun')==NULL)
        {
            $tahun_filter=date('Y');
        }
        else
        {
            $tahun_filter = request('tahun');
        }
        $dataUnit = UnitKerja::where([['unit_jenis','=','1'],['unit_eselon','=','3']])->get();
        $dataKegiatan = Kegiatan::leftJoin('t_unitkerja','kegiatan.keg_unitkerja','=','t_unitkerja.unit_kode')
                        ->when(request('unit'),function ($query){
                            return $query->where('t_unitkerja.unit_parent','=',request('unit'));
                        })
                        ->when(request('bulan'),function ($query){
                            return $query->whereMonth('keg_start','=',request('bulan'));
                        })
                        ->whereYear('keg_start','=',$tahun_filter)
                        ->orderBy('kegiatan.keg_unitkerja','asc')
                        ->orderBy('kegiatan.keg_start','asc')
                        ->get();
        //dd($dataUnit);
        return view('kegiatan.bidang',['dataKeg'=>$dataKegiatan,'dataUnitkerja'=>$dataUnit,'bulan'=>request('bulan'),'tahun'=>$tahun_filter,'dataBulan'=>$data_bulan,'dataTahun'=>$data_tahun,'unit'=>request('unit')]);
    }
    public function tambah()
    {
        $unitTarget = UnitKerja::where([['unit_jenis','=','2'],['unit_eselon','=','3']])->get();
        $unitProv = UnitKerja::where([['unit_jenis','=','1'],['unit_eselon','=','4']])->get();
        $kegJenis = KegJenis::get();
        return view('kegiatan.tambah',[
            'unitTarget'=>$unitTarget,
            'unitProv'=>$unitProv,
            'kegJenis'=>$kegJenis
        ]);
    }
    public function simpan(Request $request)
    {
        dd($request->all());
    }
}

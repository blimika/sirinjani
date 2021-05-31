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
use App\Notifikasi;
use App\JenisNotifikasi;
use App\LogAktivitas;
use App\JenisLog;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailKegiatan;
use App\Mail\MailPenerimaan;
use App\Mail\MailPengiriman;

class LogAktivitasController extends Controller
{
    //
    public function ListLog()
    {
        $data_bulan = array(
            1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'
        );
        $data_tahun = DB::table('t_aktivitas')
                    ->selectRaw('year(created_at) as tahun')
                    ->groupBy('tahun')
                    ->orderBy('tahun','asc')
                      ->get();
        if (request('tahun')==NULL)
        {
            $tahun_filter=date('Y');
        }
        elseif (request('tahun')==0)
        {
            $tahun_filter=date('Y');
        }
        else
        {
            $tahun_filter = request('tahun');
        }
        if (request('bulan')==NULL)
        {
            $bulan_filter= (int) date('m');
        }
        elseif (request('bulan')==0)
        {
            $bulan_filter = NULL;
        }
        else
        {
            $bulan_filter = request('bulan');
        }
        $dataOperator = User::get();
        $dataJenisLog = JenisLog::orderBy('jlog_id','asc')->get();
        $data = LogAktivitas::when(request('operator'),function ($query){
            return $query->where('log_username',request('operator'));
        })->when(request('jenis_log'),function ($query){
            return $query->where('log_jenis',request('jenis_log'));
        })->when($bulan_filter,function ($query) use ($bulan_filter){
            return $query->whereMonth('created_at',$bulan_filter);
        })->whereYear('created_at',$tahun_filter)->orderBy('created_at','desc')->get();
        return view('aktivitas.index',[
            'dataLog'=>$data,
            'dataJenisLog'=>$dataJenisLog,
            'jenis_log'=>request('jenis_log'),
            'dataOperator'=>$dataOperator,
            'operator'=>request('operator'),
            'bulan'=>$bulan_filter,
            'tahun'=>$tahun_filter,
            'dataBulan'=>$data_bulan,
            'dataTahun'=>$data_tahun
        ]);
    }
}

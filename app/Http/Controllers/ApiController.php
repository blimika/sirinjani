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
use Illuminate\Support\Facades\Mail;
use App\Mail\MailKegiatan;
use App\Mail\MailPenerimaan;
use App\Mail\MailPengiriman;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Api;

class ApiController extends Controller
{
    //
    public function ListKegiatan()
    {
        $data_bulan = array(
            1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'
        );
        $data_tahun = DB::table('m_keg')
                    ->selectRaw('year(keg_start) as tahun')
                    ->groupBy('tahun')
                    ->orderBy('tahun','asc')
                      ->get();
        //dd($data_tahun);
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
        //dd($bulan_filter);
        $dataUnit = UnitKerja::where([['unit_jenis','=','1'],['unit_eselon','=','3']])->get();
        //dd($dataUnit);
        $dataKegiatan = Kegiatan::leftJoin('t_unitkerja','m_keg.keg_unitkerja','=','t_unitkerja.unit_kode')
                        ->when(request('unit'),function ($query){
                            return $query->where('t_unitkerja.unit_parent','=',request('unit'));
                        })
                        ->when($bulan_filter,function ($query) use ($bulan_filter){
                            return $query->whereMonth('keg_start','=',$bulan_filter);
                        })
                        ->orderBy('m_keg.created_at','desc')->whereYear('keg_start','=',$tahun_filter)->get();
        $data = array(
            'status'=>true,
            'jumlah_record'=>count($dataKegiatan),
            'data'=>$dataKegiatan
        );             
        return response()->json($data, 200);
    }
    public function DeadlineKegiatan()
    {
        $dataKegiatan = Kegiatan::whereBetween('keg_end',array(\Carbon\Carbon::now()->format('Y-m-d'), \Carbon\Carbon::now()->addWeek()->format('Y-m-d')))->orderBy('keg_end')->get();
        $data = array(
            'status'=>true,
            'jumlah_record'=>count($dataKegiatan),
            'data'=>$dataKegiatan
        );
        return response()->json($data, 200);
    }
    public function DetilKegiatan($kegid)
    {
        $count = Kegiatan::where('keg_id',$kegid)->count();
        if ($count > 0)
        {
            //kegiatan ada
            $status = true;
            $dataKegiatan = Kegiatan::where('keg_id',$kegid)->first();
            //$dataTarget = KegTarget::where([['keg_id',$kegId],['keg_t_target','>',0]])->get();
            //$dataRealisasi = KegRealisasi::where('keg_id',$kegId)->get();
            $kode = 200;
        }
        else
        {
            //kegiatan tidak ada
            //tampilan error 404
            $status = false;
            $dataKegiatan='';
            $kode = 404;
        }
        $data = array(
            'status'=>$status,
            'data'=>$dataKegiatan
        );
        return response()->json($data, $kode);
    }
}

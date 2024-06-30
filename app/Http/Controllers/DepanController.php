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

class DepanController extends Controller
{
    //
    public function depan()
    {
        $bulan = Carbon::now()->subMonth()->month;
        $tahun = Carbon::now()->subMonth()->year;
        //dd($tahun);
        $data_bulan = array(
            1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'
        );
        $data_bulan_pendek = array(
            1=>'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'
        );
        //nilai terbaik
        $dataRankBulanan = DB::table('m_keg')
                ->leftJoin('m_keg_target','m_keg.keg_id','=','m_keg_target.keg_id')
                ->leftJoin(DB::raw("(select unit_kode as unit_kode_prov, unit_nama as unit_nama_prov from t_unitkerja where unit_jenis='1' and unit_eselon='3') as unit_prov"),'m_keg.keg_timkerja','=','unit_prov.unit_kode_prov')
                ->leftJoin('t_unitkerja','m_keg_target.keg_t_unitkerja','=','t_unitkerja.unit_kode')
                ->whereMonth('m_keg.keg_end','=',Carbon::now()->format('m'))
				->whereYear('m_keg.keg_end','=',Carbon::now()->format('Y'))
				->where('m_keg_target.keg_t_target','>','0')
				->select(DB::raw("month(m_keg.keg_end) as bulan, year(m_keg.keg_end) as tahun,m_keg_target.keg_t_unitkerja,t_unitkerja.unit_nama, sum(m_keg_target.keg_t_target) as keg_jml_target, sum(m_keg_target.keg_t_point_waktu) as point_waktu, sum(m_keg_target.keg_t_point_jumlah) as point_volume, sum(m_keg_target.keg_t_point) as point_jumlah, avg(m_keg_target.keg_t_point) as point_keg, avg(m_keg_target.spj_t_point) as point_spj, avg(m_keg_target.keg_t_point_total) as point_total, count(*) as keg_jml"))
				->groupBy('m_keg_target.keg_t_unitkerja')
				->orderBy('point_total','desc')
                ->orderBy('keg_jml_target','desc')
                ->orderBy('keg_jml','desc')
                ->orderBy('m_keg_target.keg_t_unitkerja','asc')
                ->first();
        $dataRankTahunan = DB::table('m_keg')
                ->leftJoin('m_keg_target','m_keg.keg_id','=','m_keg_target.keg_id')
                ->leftJoin(DB::raw("(select unit_kode as unit_kode_prov, unit_nama as unit_nama_prov from t_unitkerja where unit_jenis='1' and unit_eselon='3') as unit_prov"),'m_keg.keg_timkerja','=','unit_prov.unit_kode_prov')
                ->leftJoin('t_unitkerja','m_keg_target.keg_t_unitkerja','=','t_unitkerja.unit_kode')
                ->whereMonth('m_keg.keg_end','<=',Carbon::now()->format('m'))
                ->whereYear('m_keg.keg_end','=',Carbon::now()->format('Y'))
                ->where('m_keg_target.keg_t_target','>','0')
                ->select(DB::raw("m_keg_target.keg_t_unitkerja,t_unitkerja.unit_nama, sum(m_keg_target.keg_t_target) as keg_jml_target, sum(m_keg_target.keg_t_point_waktu) as point_waktu, sum(m_keg_target.keg_t_point_jumlah) as point_volume, sum(m_keg_target.keg_t_point) as point_jumlah, avg(m_keg_target.keg_t_point) as point_keg, avg(m_keg_target.spj_t_point) as point_spj, avg(m_keg_target.keg_t_point_total) as point_total, count(*) as keg_jml"))
                ->groupBy('m_keg_target.keg_t_unitkerja')
                ->orderBy('point_total','desc')
                ->orderBy('keg_jml_target','desc')
                ->orderBy('keg_jml','desc')
                ->orderBy('m_keg_target.keg_t_unitkerja','asc')
                ->first();
        //dd($dataRankBulanan);
        $data = DB::table('m_keg')
                ->leftJoin('m_keg_target','m_keg.keg_id','=','m_keg_target.keg_id')
                ->leftJoin('t_unitkerja','m_keg_target.keg_t_unitkerja','=','t_unitkerja.unit_kode')

                ->whereMonth('m_keg.keg_end','=',$bulan)
				->whereYear('m_keg.keg_end','=',$tahun)
				->where('m_keg_target.keg_t_target','>','0')
                ->leftJoin(DB::raw("(select keg_r_unitkerja, sum(keg_r_jumlah) as jumlah_dikirim from m_keg_realisasi left join m_keg on m_keg.keg_id=m_keg_realisasi.keg_id where month(keg_end)='".$bulan."' and year(keg_end)='".$tahun."' and keg_r_jenis='1' group by keg_r_unitkerja) as pengiriman"),'m_keg_target.keg_t_unitkerja','=','pengiriman.keg_r_unitkerja')
                ->leftJoin(DB::raw("(select keg_r_unitkerja, sum(keg_r_jumlah) as jumlah_diterima from m_keg_realisasi left join m_keg on m_keg.keg_id=m_keg_realisasi.keg_id where month(keg_end)='".$bulan."' and year(keg_end)='".$tahun."' and keg_r_jenis='2' group by keg_r_unitkerja) as penerimaan"),'m_keg_target.keg_t_unitkerja','=','penerimaan.keg_r_unitkerja')
				->select(DB::raw("month(m_keg.keg_end) as bulan, year(m_keg.keg_end) as tahun,m_keg_target.keg_t_unitkerja,t_unitkerja.unit_nama, sum(m_keg_target.keg_t_target) as keg_jml_target, count(*) as keg_jml, pengiriman.jumlah_dikirim,penerimaan.jumlah_diterima"))
				->groupBy('m_keg_target.keg_t_unitkerja')
				->orderBy('keg_t_unitkerja','asc')
                ->get();
        //dd($data);
        //$dataUnit = UnitKerja::where([['unit_jenis','=','1'],['unit_eselon','=','3']])->get();
        $label_tahun = array();
        for ($t=$tahun; $t>=($tahun-2); $t--)
        {
            $label_tahun[] = $t;
        }
        //dd(json_encode($label_tahun));
        /*
        $tahun0 = $tahun;
        $tahun1 = $tahun - 1;
        $tahun2 = $tahun - 2;
        $data_grafik_keg = array();
        for ($i=1; $i <= 12 ; $i++) {
            $data_tahun0 = Kegiatan::whereYear('keg_end',$tahun0)
                                ->whereMonth('keg_end',$i)
                                ->select(DB::raw("year(keg_end) as tahun, month(keg_end) as bulan, count(*) as jumlah"))
                                ->groupByRaw('tahun,bulan')->first();
            $data_tahun1 = Kegiatan::whereYear('keg_end',$tahun1)
                                ->whereMonth('keg_end',$i)
                                ->select(DB::raw("year(keg_end) as tahun, month(keg_end) as bulan, count(*) as jumlah"))
                                ->groupByRaw('tahun,bulan')->first();
            $data_tahun2 = Kegiatan::whereYear('keg_end',$tahun2)
                                ->whereMonth('keg_end',$i)
                                ->select(DB::raw("year(keg_end) as tahun, month(keg_end) as bulan, count(*) as jumlah"))
                                ->groupByRaw('tahun,bulan')->first();
            $data_grafik_keg[] = array(
                                    'bulan'=>$data_bulan_pendek[$i],
                                    'tahun0'=>$data_tahun0->jumlah,
                                    'tahun1'=>$data_tahun1->jumlah,
                                    'tahun2'=>$data_tahun2->jumlah,
                                );
        }
                                */
        //dd ($data_grafik_keg);
        $data_grafik_poin = array();
        $data_grafik_keg = array();
        $data_grafik_target = array();
        for ($i=1; $i <= 12 ; $i++) { //generate bulan
            $data_nilai = \DB::table('m_keg')
                ->leftJoin(\DB::raw("(select keg_id as kegid_terima, sum(keg_r_jumlah) as jumlah_terima, keg_r_jenis as jenis_terima from m_keg_realisasi where keg_r_jenis=2 group by kegid_terima) as keg_real_terima"),'keg_real_terima.kegid_terima','m_keg.keg_id')
                ->leftJoin(\DB::raw("(select keg_id as kegid_kirim, sum(keg_r_jumlah) as jumlah_kirim, keg_r_jenis as jenis_kirim from m_keg_realisasi where keg_r_jenis=1 group by kegid_kirim) as keg_real_kirim"),'keg_real_kirim.kegid_kirim','m_keg.keg_id')
				//->where('m_keg.keg_timkerja',$unit_filter)
                ->whereMonth('m_keg.keg_end','=',(int)$i)
				->whereYear('m_keg.keg_end','=',$tahun)
				->select(\DB::raw("month(m_keg.keg_end) as bulan, year(m_keg.keg_end) as tahun, sum(m_keg.keg_total_target) as keg_jml_target,count(*) as keg_jml,sum(jumlah_terima) as jml_terima,sum(jumlah_kirim) as jml_kirim"))
				->first();
            $data_grafik_target[] = array(
                'bulan' => $data_bulan_pendek[$i],
                'target' => $data_nilai->keg_jml_target,
                'kirim' =>  $data_nilai->jml_kirim,
                'terima' => $data_nilai->jml_terima
            );
        }
        //$data_keg_tahun = DB::table('m_keg')
        return view('depan',[
            'dataRekapKegiatan'=>$data,
            'Ranking1Bulan'=>$dataRankBulanan,
            'Ranking1Tahun'=>$dataRankTahunan,
            //'data_grafik_keg'=>json_encode($data_grafik_keg),
            'data_grafik_target'=>json_encode($data_grafik_target),
            'data_grafik_label'=>json_encode($label_tahun),
            'tahun_berjalan'=>$tahun
        ]);
    }
}

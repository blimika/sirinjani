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
        return view('depan',[
            'dataRekapKegiatan'=>$data,
            'Ranking1Bulan'=>$dataRankBulanan,
            'Ranking1Tahun'=>$dataRankTahunan,
        ]);
    }
}

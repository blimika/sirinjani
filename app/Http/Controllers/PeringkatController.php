<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\UnitKerja;
use App\Exports\FormatViewExim;
use Excel;

class PeringkatController extends Controller
{
    //
    public function tahunan()
    {
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
        $dataUnit = UnitKerja::where([['unit_jenis','=','1'],['unit_eselon','=','3']])->get();
        $data = DB::table('m_keg')
                ->leftJoin('m_keg_target','m_keg.keg_id','=','m_keg_target.keg_id')
                ->leftJoin(DB::raw("(select unit_kode as unit_kode_prov, unit_nama as unit_nama_prov, unit_parent as unit_parent_prov from t_unitkerja where unit_jenis='1') as unit_prov"),'m_keg.keg_unitkerja','=','unit_prov.unit_kode_prov')
                ->leftJoin('t_unitkerja','m_keg_target.keg_t_unitkerja','=','t_unitkerja.unit_kode')
                ->when(request('unit'),function ($query){
                    return $query->where('unit_prov.unit_parent_prov','=',request('unit'));
                })
				->whereYear('m_keg.keg_end','=',$tahun_filter)
				->where('m_keg_target.keg_t_target','>','0')
				->select(DB::raw("m_keg_target.keg_t_unitkerja,t_unitkerja.unit_nama, sum(m_keg_target.keg_t_target) as keg_jml_target, sum(m_keg_target.keg_t_point_waktu) as point_waktu, sum(m_keg_target.keg_t_point_jumlah) as point_jumlah, sum(m_keg_target.keg_t_point) as point_total, avg(m_keg_target.keg_t_point) as point_rata, count(*) as keg_jml"))
				->groupBy('m_keg_target.keg_t_unitkerja')
				->orderBy('point_rata','desc')
                ->get();
        //dd($data);
        return view('peringkat.tahunan',['dataUnitkerja'=>$dataUnit,'dataTahun'=>$data_tahun,'tahun'=>$tahun_filter,'dataPeringkat'=>$data,'unit'=>request('unit')]);
    }
    public function bulanan()
    {
        $data_bulan = array(
            1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'
        );
        /*
        $data_tahun = DB::table('m_keg')
                    ->selectRaw('year(keg_start) as tahun')
                    ->groupBy('tahun')
                    ->orderBy('tahun','asc')
                      ->get(); */
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
        $data = DB::table('m_keg')
                ->leftJoin('m_keg_target','m_keg.keg_id','=','m_keg_target.keg_id')
                ->leftJoin(DB::raw("(select unit_kode as unit_kode_prov, unit_nama as unit_nama_prov, unit_parent as unit_parent_prov from t_unitkerja where unit_jenis='1') as unit_prov"),'m_keg.keg_unitkerja','=','unit_prov.unit_kode_prov')
                ->leftJoin('t_unitkerja','m_keg_target.keg_t_unitkerja','=','t_unitkerja.unit_kode')
                ->when(request('unit'),function ($query){
                    return $query->where('unit_prov.unit_parent_prov','=',request('unit'));
                })
                ->whereMonth('m_keg.keg_end','=',$bulan_filter)
				->whereYear('m_keg.keg_end','=',$tahun_filter)
				->where('m_keg_target.keg_t_target','>','0')
				->select(DB::raw("month(m_keg.keg_end) as bulan, year(m_keg.keg_end) as tahun,m_keg_target.keg_t_unitkerja,t_unitkerja.unit_nama, sum(m_keg_target.keg_t_target) as keg_jml_target, sum(m_keg_target.keg_t_point_waktu) as point_waktu, sum(m_keg_target.keg_t_point_jumlah) as point_jumlah, sum(m_keg_target.keg_t_point) as point_total, avg(m_keg_target.keg_t_point) as point_rata, count(*) as keg_jml"))
				->groupBy('m_keg_target.keg_t_unitkerja')
				->orderBy('point_rata','desc')
                ->get();
        //dd($data);
        $dataUnit = UnitKerja::where([['unit_jenis','=','1'],['unit_eselon','=','3']])->get();
        return view('peringkat.bulanan',['dataUnitkerja'=>$dataUnit,'dataTahun'=>$data_tahun,'tahun'=>$tahun_filter,'unit'=>request('unit'),'dataPeringkat'=>$data,'dataBulan'=>$data_bulan,'bulan'=>$bulan_filter]);
    }
    public function Ckp()
    {
        $data_bulan = array(
            1=>'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'
        );
        $Kabkota = UnitKerja::where([['unit_jenis','=','2'],['unit_eselon','=','3']])->get();
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
        if (request('bulan')<=0)
        {
            $bulan_filter=date('m');
        }
        else
        {
            $bulan_filter = request('bulan');
        }

        return view('peringkat.ckp',['dataKabkota'=>$Kabkota,'dataTahun'=>$data_tahun,'tahun'=>$tahun_filter,'unit'=>request('unit'),'dataBulan'=>$data_bulan]);
    }
    public function rincian()
    {
        $data_bulan = array(
            1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'
        );
        $dataUnit = UnitKerja::where([['unit_jenis','=','2'],['unit_eselon','=','3']])->get();
        $unitFilter = $dataUnit->first();
        $data_tahun = DB::table('m_keg')
        ->selectRaw('year(keg_end) as tahun')
        ->groupBy('tahun')
        ->whereYear('keg_end','<=',NOW())
        ->orderBy('tahun','asc')
          ->get();
        if (request('bulan')<=0)
        {
            $bulan_filter=(int) date('m');
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
        $unit_nama = UnitKerja::where('unit_kode',$unit_filter)->first();
        $data = DB::table('m_keg')
                ->leftJoin('m_keg_target','m_keg.keg_id','=','m_keg_target.keg_id')
                ->leftJoin(DB::raw("(select keg_id, keg_r_unitkerja, sum(keg_r_jumlah) as jumlah_dikirim from m_keg_realisasi where keg_r_unitkerja='".$unit_filter."' and keg_r_jenis='1' group by keg_id) as pengiriman"),'m_keg.keg_id','=','pengiriman.keg_id')
                ->leftJoin(DB::raw("(select keg_id, keg_r_unitkerja, sum(keg_r_jumlah) as jumlah_diterima from m_keg_realisasi where keg_r_unitkerja='".$unit_filter."' and keg_r_jenis='2' group by keg_id) as penerimaan"),'m_keg.keg_id','=','penerimaan.keg_id')
                ->leftJoin(DB::raw("(select unit_kode as unit_kode_prov, unit_nama as unit_nama_prov, unit_parent as unit_parent_prov from t_unitkerja where unit_jenis='1') as unit_prov"),'m_keg.keg_unitkerja','=','unit_prov.unit_kode_prov')
                ->leftJoin(DB::raw("(select unit_kode as unit_kode_parent, unit_nama as unit_nama_parent from t_unitkerja where unit_jenis='1' and unit_eselon='3') as unit_parent"),'unit_prov.unit_parent_prov','=','unit_parent.unit_kode_parent')
                ->leftJoin('t_unitkerja','m_keg_target.keg_t_unitkerja','=','t_unitkerja.unit_kode')
                ->when($bulan_filter,function ($query) use ($bulan_filter) {
                    return $query->whereMonth('m_keg.keg_end','=',$bulan_filter);
                })
				->whereYear('m_keg.keg_end','=',$tahun_filter)
                ->where('m_keg_target.keg_t_target','>','0')
                ->where('m_keg_target.keg_t_unitkerja','=',$unit_filter)
				->select(DB::raw("m_keg_target.keg_t_unitkerja,t_unitkerja.unit_nama, month(m_keg.keg_end) as bulan_keg,m_keg.keg_id, m_keg.keg_nama, unit_kode_prov, unit_nama_prov, unit_kode_parent, unit_nama_parent, keg_start, keg_end, m_keg_target.keg_t_target, pengiriman.jumlah_dikirim, penerimaan.jumlah_diterima, m_keg_target.keg_t_point"))
				->orderBy('keg_end','asc')
                ->get();
        //dd($data);
        return view('peringkat.rincian',['dataUnitkerja'=>$dataUnit,'unit'=>$unit_filter,'unitnama'=>$unit_nama->unit_nama,'dataBulan'=>$data_bulan,'dataTahun'=>$data_tahun,'tahun'=>$tahun_filter,'dataRincian'=>$data,'bulan'=>$bulan_filter]);
    }
    public function ExportExcel($unitkerja,$bulan,$tahun)
    {
        $data_bulan = array(
            1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'
        );
        $unit_nama = UnitKerja::where('unit_kode',$unitkerja)->first();
        $data = DB::table('m_keg')
                ->leftJoin('m_keg_target','m_keg.keg_id','=','m_keg_target.keg_id')
                ->leftJoin(DB::raw("(select keg_id, keg_r_unitkerja, sum(keg_r_jumlah) as jumlah_dikirim from m_keg_realisasi where keg_r_unitkerja='".$unitkerja."' and keg_r_jenis='1' group by keg_id) as pengiriman"),'m_keg.keg_id','=','pengiriman.keg_id')
                ->leftJoin(DB::raw("(select keg_id, keg_r_unitkerja, sum(keg_r_jumlah) as jumlah_diterima from m_keg_realisasi where keg_r_unitkerja='".$unitkerja."' and keg_r_jenis='2' group by keg_id) as penerimaan"),'m_keg.keg_id','=','penerimaan.keg_id')
                ->leftJoin(DB::raw("(select unit_kode as unit_kode_prov, unit_nama as unit_nama_prov, unit_parent as unit_parent_prov from t_unitkerja where unit_jenis='1') as unit_prov"),'m_keg.keg_unitkerja','=','unit_prov.unit_kode_prov')
                ->leftJoin(DB::raw("(select unit_kode as unit_kode_parent, unit_nama as unit_nama_parent from t_unitkerja where unit_jenis='1' and unit_eselon='3') as unit_parent"),'unit_prov.unit_parent_prov','=','unit_parent.unit_kode_parent')
                ->leftJoin('t_unitkerja','m_keg_target.keg_t_unitkerja','=','t_unitkerja.unit_kode')
                ->when($bulan,function ($query) use ($bulan){
                    return $query->whereMonth('m_keg.keg_end','=',$bulan);
                })
				->whereYear('m_keg.keg_end','=',$tahun)
                ->where('m_keg_target.keg_t_target','>','0')
                ->where('m_keg_target.keg_t_unitkerja','=',$unitkerja)
				->select(DB::raw("m_keg_target.keg_t_unitkerja,t_unitkerja.unit_nama, month(m_keg.keg_end) as bulan_keg, year(m_keg.keg_end) as tahun_keg,m_keg.keg_id, m_keg.keg_nama, unit_kode_prov, unit_nama_prov, unit_kode_parent, unit_nama_parent, keg_start, keg_end, m_keg_target.keg_t_target, pengiriman.jumlah_dikirim, penerimaan.jumlah_diterima, m_keg_target.keg_t_point"))
				->orderBy('keg_end','asc')
                ->get()->toArray();
        //dd($data);
        foreach ($data as $item) {
            $rincian_array[] = array(
                'BPS KABKOTA' => $item->unit_nama,
                'BULAN' => $data_bulan[$item->bulan_keg],
                'TAHUN' => $item->tahun_keg,
                'KEG_ID' => $item->keg_id,
                'KEGIATAN' => $item->keg_nama,
                'TANGGAL MULAI' => $item->keg_start,
                'TANGGAL BERAKHIR' => $item->keg_end,
                'TARGET' => $item->keg_t_target,
                'DIKIRIM' => $item->jumlah_dikirim,
                'DITERIMA' => $item->jumlah_diterima,
                'NILAI' => $item->keg_t_point
            );
        }
        $fileName = 'rincian-kegiatan-kabkota-';
        $namafile = $fileName . date('Y-m-d_H-i-s') . '.xlsx';
        //dd($anggaran_array);
        return Excel::download(new FormatViewExim($rincian_array), $namafile);
    }
}

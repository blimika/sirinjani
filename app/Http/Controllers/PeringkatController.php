<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\UnitKerja;
use App\Exports\FormatViewExim;
use Excel;
use App\Helpers\Generate;
use App\Helpers\Tanggal;
use Illuminate\Support\Facades\Auth;
use App\User;
use Carbon\Carbon;
use Session;
use App\Kegiatan;
use App\Exports\FormatViewExpCkp;

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
            $bulan_filter = date('m');
        }
        else
        {
            $tahun_filter = request('tahun');
            if ($tahun_filter == date('Y'))
            {
                $bulan_filter = date('m');
            }
            else
            {
                $bulan_filter = 12;
            }
        }
        $dataUnit = UnitKerja::where([['unit_jenis','=','1'],['unit_eselon','=','3']])->get();
       /*
        $data = DB::table('m_keg')
                ->leftJoin('m_keg_target','m_keg.keg_id','=','m_keg_target.keg_id')
                ->leftJoin(DB::raw("(select unit_kode as unit_kode_prov, unit_nama as unit_nama_prov, unit_parent as unit_parent_prov from t_unitkerja where unit_jenis='1') as unit_prov"),'m_keg.keg_unitkerja','=','unit_prov.unit_kode_prov')
                ->leftJoin('t_unitkerja','m_keg_target.keg_t_unitkerja','=','t_unitkerja.unit_kode')
                ->when(request('unit'),function ($query){
                    return $query->where('unit_prov.unit_parent_prov','=',request('unit'));
                })
                ->whereMonth('m_keg.keg_end','<=',$bulan_filter)
				->whereYear('m_keg.keg_end','=',$tahun_filter)
				->where('m_keg_target.keg_t_target','>','0')
				->select(DB::raw("m_keg_target.keg_t_unitkerja,t_unitkerja.unit_nama, sum(m_keg_target.keg_t_target) as keg_jml_target, sum(m_keg_target.keg_t_point_waktu) as point_waktu, sum(m_keg_target.keg_t_point_jumlah) as point_jumlah, sum(m_keg_target.keg_t_point) as point_total, avg(m_keg_target.keg_t_point) as point_rata, count(*) as keg_jml"))
				->groupBy('m_keg_target.keg_t_unitkerja')
				->orderBy('point_rata','desc')
                ->orderBy('keg_jml_target','desc')
                ->orderBy('keg_jml','desc')
                ->orderBy('m_keg_target.keg_t_unitkerja','asc')
                ->get();
                */
            $data = DB::table('m_keg')
                ->leftJoin('m_keg_target','m_keg.keg_id','=','m_keg_target.keg_id')
                ->leftJoin(DB::raw("(select unit_kode as unit_kode_prov, unit_nama as unit_nama_prov, unit_parent as unit_parent_prov from t_unitkerja where unit_jenis='1') as unit_prov"),'m_keg.keg_unitkerja','=','unit_prov.unit_kode_prov')
                ->leftJoin('t_unitkerja','m_keg_target.keg_t_unitkerja','=','t_unitkerja.unit_kode')
                ->when(request('unit'),function ($query){
                    return $query->where('unit_prov.unit_parent_prov','=',request('unit'));
                })
                ->whereMonth('m_keg.keg_end','<=',$bulan_filter)
				->whereYear('m_keg.keg_end','=',$tahun_filter)
				->where('m_keg_target.keg_t_target','>','0')
				->select(DB::raw("m_keg_target.keg_t_unitkerja,t_unitkerja.unit_nama, sum(m_keg_target.keg_t_target) as keg_jml_target, sum(m_keg_target.keg_t_point_waktu) as point_waktu, sum(m_keg_target.keg_t_point_jumlah) as point_volume, sum(m_keg_target.keg_t_point) as point_jumlah, avg(m_keg_target.keg_t_point) as point_keg, avg(m_keg_target.spj_t_point) as point_spj, avg(m_keg_target.keg_t_point_total) as point_total, count(*) as keg_jml"))
				->groupBy('m_keg_target.keg_t_unitkerja')
				->orderBy('point_total','desc')
                ->orderBy('keg_jml_target','desc')
                ->orderBy('keg_jml','desc')
                ->orderBy('m_keg_target.keg_t_unitkerja','asc')
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
        /*
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
                ->orderBy('keg_jml_target','desc')
                ->orderBy('keg_jml','desc')
                ->orderBy('m_keg_target.keg_t_unitkerja','asc')
                ->get();
        */
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
				->select(DB::raw("month(m_keg.keg_end) as bulan, year(m_keg.keg_end) as tahun,m_keg_target.keg_t_unitkerja,t_unitkerja.unit_nama, sum(m_keg_target.keg_t_target) as keg_jml_target, sum(m_keg_target.keg_t_point_waktu) as point_waktu, sum(m_keg_target.keg_t_point_jumlah) as point_volume, sum(m_keg_target.keg_t_point) as point_jumlah, avg(m_keg_target.keg_t_point) as point_keg, avg(m_keg_target.spj_t_point) as point_spj, avg(m_keg_target.keg_t_point_total) as point_total, count(*) as keg_jml"))
				->groupBy('m_keg_target.keg_t_unitkerja')
				->orderBy('point_total','desc')
                ->orderBy('keg_jml_target','desc')
                ->orderBy('keg_jml','desc')
                ->orderBy('m_keg_target.keg_t_unitkerja','asc')
                ->get();
        //dd($data);
        $dataUnit = UnitKerja::where([['unit_jenis','=','1'],['unit_eselon','=','3']])->get();
        return view('peringkat.bulanan',['dataUnitkerja'=>$dataUnit,'dataTahun'=>$data_tahun,'tahun'=>$tahun_filter,'unit'=>request('unit'),'dataPeringkat'=>$data,'dataBulan'=>$data_bulan,'bulan'=>$bulan_filter]);
    }
    public function Ckp()
    {
        if (Auth::user()->level > 5 or Auth::user()->flag_liatckp == 1)
        {
            $data_bulan = array(
                1=>'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'
            );
            $Kabkota = UnitKerja::where([['unit_jenis','=','2'],['unit_eselon','=','3']])->get();
            $data_tahun = DB::table('m_keg')
            ->selectRaw('year(keg_end) as tahun')
            ->groupBy('tahun')
            //->whereYear('keg_end','<=',NOW())
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
        else
        {
            return view('error.aksesditolak');
        }

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
        //->whereYear('keg_end','<=',NOW())
        ->orderBy('tahun','asc')
         ->get();
        if (request('bulan')<=0)
        {
            $bulan_filter = (int) date('m');
        }
        else
        {
            $bulan_filter = request('bulan');
        }
        if (request('tahun')<=0)
        {
            $tahun_filter = date('Y');
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
    public function ExportCkpExcel($tahun)
    {
        $data_bulan = array(
            1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'
        );
        $Kabkota = UnitKerja::where([['unit_jenis','=','2'],['unit_eselon','=','3']])->get();
        //dd($Kabkota);

        foreach ($Kabkota as $item) {

            $rincian_array[] = array(
                'BPS KABKOTA' => $item->unit_nama,
                'POIN JANUARI' => Generate::NilaiCkpBulan($item->unit_kode,1,$tahun)['nilai_point'],
                'CKP JANUARI' => Generate::NilaiCkpBulan($item->unit_kode,1,$tahun)['nilai_ckp'],
                'POIN FEBRUARI' => Generate::NilaiCkpBulan($item->unit_kode,2,$tahun)['nilai_point'],
                'CKP FEBRUARI' => Generate::NilaiCkpBulan($item->unit_kode,2,$tahun)['nilai_ckp'],
                'POIN MARET' => Generate::NilaiCkpBulan($item->unit_kode,3,$tahun)['nilai_point'],
                'CKP MARET' => Generate::NilaiCkpBulan($item->unit_kode,3,$tahun)['nilai_ckp'],
                'POIN APRIL' => Generate::NilaiCkpBulan($item->unit_kode,4,$tahun)['nilai_point'],
                'CKP APRIL' => Generate::NilaiCkpBulan($item->unit_kode,4,$tahun)['nilai_ckp'],
                'POIN MEI' => Generate::NilaiCkpBulan($item->unit_kode,5,$tahun)['nilai_point'],
                'CKP MEI' => Generate::NilaiCkpBulan($item->unit_kode,5,$tahun)['nilai_ckp'],
                'POIN JUNI' => Generate::NilaiCkpBulan($item->unit_kode,6,$tahun)['nilai_point'],
                'CKP JUNI' => Generate::NilaiCkpBulan($item->unit_kode,6,$tahun)['nilai_ckp'],
                'POIN JULI' => Generate::NilaiCkpBulan($item->unit_kode,7,$tahun)['nilai_point'],
                'CKP JULI' => Generate::NilaiCkpBulan($item->unit_kode,7,$tahun)['nilai_ckp'],
                'POIN AGUSTUS' => Generate::NilaiCkpBulan($item->unit_kode,8,$tahun)['nilai_point'],
                'CKP AGUSTUS' => Generate::NilaiCkpBulan($item->unit_kode,8,$tahun)['nilai_ckp'],
                'POIN SEPTEMBER' => Generate::NilaiCkpBulan($item->unit_kode,9,$tahun)['nilai_point'],
                'CKP SEPTEMBER' => Generate::NilaiCkpBulan($item->unit_kode,9,$tahun)['nilai_ckp'],
                'POIN OKTOBER' => Generate::NilaiCkpBulan($item->unit_kode,10,$tahun)['nilai_point'],
                'CKP OKTOBER' => Generate::NilaiCkpBulan($item->unit_kode,10,$tahun)['nilai_ckp'],
                'POIN NOVEMBER' => Generate::NilaiCkpBulan($item->unit_kode,11,$tahun)['nilai_point'],
                'CKP NOVEMBER' => Generate::NilaiCkpBulan($item->unit_kode,11,$tahun)['nilai_ckp'],
                'POIN DESEMBER' => Generate::NilaiCkpBulan($item->unit_kode,12,$tahun)['nilai_point'],
                'CKP DESEMBER' => Generate::NilaiCkpBulan($item->unit_kode,12,$tahun)['nilai_ckp'],
            );
        }
        $fileName = 'nilai-ckp-kabkota-';
        $namafile = $fileName . date('Y-m-d_H-i-s') . '.xlsx';
        $waktu = Tanggal::LengkapHariPanjang(\Carbon\Carbon::now());
        //dd($rincian_array);
        //return Excel::download(new FormatExpCkp($rincian_array,$tahun,$waktu), $namafile);
        return Excel::download(new FormatViewExpCkp($rincian_array,$tahun,$waktu), $namafile);
    }
    public function RekapNilaiBulanan()
    {
        if (Auth::user()->level == 3 or Auth::user()->level > 4)
        {
            $data_bulan = array(
                1=>'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'
            );
            $Kabkota = UnitKerja::where([['unit_jenis','=','2'],['unit_eselon','=','3']])->get();
            $data_tahun = DB::table('m_keg')
            ->selectRaw('year(keg_end) as tahun')
            ->groupBy('tahun')
            //->whereYear('keg_end','<=',NOW())
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
            $dataUnit = UnitKerja::where([['unit_jenis','=','1'],['unit_eselon','=','3']])->get();
            if (request('unit')<=0)
            {
                $unit_terpilih = 'BPS Provinsi Nusa Tenggara Barat';
                $unit = 0;
            }
            else
            {
                $unit_kepilih = UnitKerja::where('unit_kode',request('unit'))->first();
                $unit_terpilih = $unit_kepilih->unit_nama;
                $unit = request('unit');
            }
            return view('peringkat.rekapnilai',['dataUnitkerja'=>$dataUnit,'unit_nama'=>$unit_terpilih,'dataKabkota'=>$Kabkota,'dataTahun'=>$data_tahun,'tahun'=>$tahun_filter,'unit'=>$unit,'dataBulan'=>$data_bulan]);
        }
        else
        {
            return view('error.aksesditolak');
        }
    }
    public function RekapNilaiBulananExport($unitkode,$tahun)
    {
        $data_bulan = array(
            1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'
        );
        $Kabkota = UnitKerja::where([['unit_jenis','=','2'],['unit_eselon','=','3']])->get();
        //dd($Kabkota);
        //ListNilaiMenurutFungsi($unitkode,$kabkota,$bulan,$tahun)
        //ListNilaiTotal($kabkota,$bulan,$tahun)
        foreach ($Kabkota as $item) {
            if ($unitkode == 0)
            {
                //total menurut provinsi
                $fileName = 'rekapnilai-kabkota-menurut-provinsi-';
                $menurut = '';
                $rincian_array[] = array(
                    'BPS KABKOTA' => $item->unit_nama,
                    'JANUARI' => Generate::ListNilaiTotal($item->unit_kode,1,$tahun)['nilai_total'],
                    'FEBRUARI' => Generate::ListNilaiTotal($item->unit_kode,2,$tahun)['nilai_total'],
                    'MARET' => Generate::ListNilaiTotal($item->unit_kode,3,$tahun)['nilai_total'],
                    'APRIL' => Generate::ListNilaiTotal($item->unit_kode,4,$tahun)['nilai_total'],
                    'MEI' => Generate::ListNilaiTotal($item->unit_kode,5,$tahun)['nilai_total'],
                    'JUNI' => Generate::ListNilaiTotal($item->unit_kode,6,$tahun)['nilai_total'],
                    'JULI' => Generate::ListNilaiTotal($item->unit_kode,7,$tahun)['nilai_total'],
                    'AGUSTUS' => Generate::ListNilaiTotal($item->unit_kode,8,$tahun)['nilai_total'],
                    'SEPTEMBER' => Generate::ListNilaiTotal($item->unit_kode,9,$tahun)['nilai_total'],
                    'OKTOBER' => Generate::ListNilaiTotal($item->unit_kode,10,$tahun)['nilai_total'],
                    'NOVEMBER' => Generate::ListNilaiTotal($item->unit_kode,11,$tahun)['nilai_total'],
                    'DESEMBER' => Generate::ListNilaiTotal($item->unit_kode,12,$tahun)['nilai_total'],
                );
            }
            else
            {
                //bila kode fungsi ada nilainya
                $fileName = 'rekapnilai-kabkota-menurut-'.$unitkode.'-';
                $rincian_array[] = array(
                    'BPS KABKOTA' => $item->unit_nama,
                    'JANUARI' => Generate::ListNilaiMenurutFungsi($unitkode,$item->unit_kode,1,$tahun)['nilai_total'],
                    'FEBRUARI' => Generate::ListNilaiMenurutFungsi($unitkode,$item->unit_kode,2,$tahun)['nilai_total'],
                    'MARET' => Generate::ListNilaiMenurutFungsi($unitkode,$item->unit_kode,3,$tahun)['nilai_total'],
                    'APRIL' => Generate::ListNilaiMenurutFungsi($unitkode,$item->unit_kode,4,$tahun)['nilai_total'],
                    'MEI' => Generate::ListNilaiMenurutFungsi($unitkode,$item->unit_kode,5,$tahun)['nilai_total'],
                    'JUNI' => Generate::ListNilaiMenurutFungsi($unitkode,$item->unit_kode,6,$tahun)['nilai_total'],
                    'JULI' => Generate::ListNilaiMenurutFungsi($unitkode,$item->unit_kode,7,$tahun)['nilai_total'],
                    'AGUSTUS' => Generate::ListNilaiMenurutFungsi($unitkode,$item->unit_kode,8,$tahun)['nilai_total'],
                    'SEPTEMBER' => Generate::ListNilaiMenurutFungsi($unitkode,$item->unit_kode,9,$tahun)['nilai_total'],
                    'OKTOBER' => Generate::ListNilaiMenurutFungsi($unitkode,$item->unit_kode,10,$tahun)['nilai_total'],
                    'NOVEMBER' => Generate::ListNilaiMenurutFungsi($unitkode,$item->unit_kode,11,$tahun)['nilai_total'],
                    'DESEMBER' => Generate::ListNilaiMenurutFungsi($unitkode,$item->unit_kode,12,$tahun)['nilai_total'],
                );
            }
        }
        if ($unitkode == 0)
        {
            $menurut = '';
        }
        else
        {
            $fungsi = UnitKerja::where('unit_kode',$unitkode)->first();
            $menurut = 'Menurut ['.$unitkode.'] '.$fungsi->unit_nama;
        }
        $waktu = Tanggal::LengkapHariPanjang(\Carbon\Carbon::now());
        $namafile = $fileName . date('Y-m-d_H-i-s') . '.xlsx';
        //dd($anggaran_array);
        return Excel::download(new FormatViewExim($rincian_array,$tahun,$menurut,$waktu), $namafile);
    }
}

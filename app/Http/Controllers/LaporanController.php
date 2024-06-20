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
use App\Exports\FormatViewExim;
use App\Exports\FormatExpLapBulanan;
use App\Exports\FormatExpLapTahunan;


class LaporanController extends Controller
{
    //
    public function bulanan()
    {
        $data_bulan = array(
            1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'
        );
        $dataUnit = UnitKerja::where([['unit_jenis','=','1'],['unit_eselon','<','4']])->get();
        $dataSubFungsi = UnitKerja::where([['unit_jenis','=','1'],['unit_eselon','=','4']])->get();
        //dd($dataUnit);
        $unitFilter = $dataUnit->first();
        $data_tahun = DB::table('m_keg')
        ->selectRaw('year(keg_end) as tahun')
        ->groupBy('tahun')
        //->whereYear('keg_end','<=',NOW())
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
        if ($unit_filter == "52000")
        {
            $unit_item = 'unit_parent';
        }
        else
        {
            $unit_item = 'unit_kode';
        }
        $dataUnitNama = UnitKerja::where('unit_kode',$unit_filter)->first();
        $unit_nama = $dataUnitNama->unit_nama;
        $data = Kegiatan::leftJoin('t_unitkerja','m_keg.keg_timkerja','=','t_unitkerja.unit_kode')
            ->when($bulan_filter,function ($query) use ($bulan_filter) {
            return $query->whereMonth('keg_end','=',$bulan_filter);
            })
            ->whereYear('keg_end','=',$tahun_filter)->orderBy('keg_timkerja','asc')->get();
        //dd($data);
        //$data_cek_unit = Kegiatan::leftJoin('t_unitkerja','m_keg.keg_unitkerja','=','t_unitkerja.unit_kode')->where('')
        return view('laporan.bulanan',['dataUnitkerja'=>$dataUnit,'dataTahun'=>$data_tahun,'tahun'=>$tahun_filter,'unit'=>$unit_filter,'unit_nama'=>$unit_nama,'dataBulan'=>$data_bulan,'bulan'=>$bulan_filter,'dataSubFungsi'=>$dataSubFungsi,'data'=>$data,'unit_item'=>$unit_item]);
    }
    public function bulananExport($unitkerja,$bulan,$tahun)
    {
        $data_bulan = array(
            1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'
        );
        $dataUnit = UnitKerja::where([['unit_jenis','=','1'],['unit_eselon','<','4']])->get();
        $dataSubFungsi = UnitKerja::where([['unit_jenis','=','1'],['unit_eselon','=','4']])->get();
        //dd($dataUnit);

        $dataUnitNama = UnitKerja::where('unit_kode',$unitkerja)->first();
        $unit_nama = $dataUnitNama->unit_nama;
        if ($unitkerja == '52000')
        {
            $unitfilter = '';
        }
        else
        {
            $unitfilter = $unitkerja;
        }
        $data = Kegiatan::leftJoin('t_unitkerja','m_keg.keg_timkerja','=','t_unitkerja.unit_kode')
            ->when($bulan,function ($query) use ($bulan) {
                return $query->whereMonth('keg_end','=',$bulan);
            })
            ->whereYear('keg_end','=',$tahun)
            ->when($unitfilter,function ($query) use ($unitkerja) {
                return $query->where('unit_kode',$unitkerja);
            })
            ->orderBy('keg_timkerja','asc')
            ->orderBy('keg_start','asc')
            ->get();
            $no=1;
            foreach ($data as $item) {
                if ($item->RealisasiKirim->sum('keg_r_jumlah') > 0)
                {
                    $persen_terima = number_format(($item->RealisasiTerima->sum('keg_r_jumlah')/$item->RealisasiKirim->sum('keg_r_jumlah'))*100,2,",",".");
                }
                else
                {
                    $persen_terima = number_format(0,2,",",".");
                }
                $rincian_array[] = array(
                    'TIM KERJA' => $item->unit_nama,
                    'NO'=> $no,
                    'KEG_ID' => $item->keg_id,
                    'KEGIATAN' => $item->keg_nama,
                    'TANGGAL MULAI' => Tanggal::Panjang($item->keg_start),
                    'TANGGAL BERAKHIR' => Tanggal::Panjang($item->keg_end),
                    'TARGET' => $item->Target->sum('keg_t_target'),
                    'DIKIRIM' => $item->RealisasiKirim->sum('keg_r_jumlah') ." (". number_format(($item->RealisasiKirim->sum('keg_r_jumlah')/$item->Target->sum('keg_t_target'))*100,2,",",".")."%)",
                    'DITERIMA' => $item->RealisasiTerima->sum('keg_r_jumlah') ." (".$persen_terima."%)"
                );
                $no++;
            }
            $judul = "Laporan Kegiatan [".$unitkerja."] ".$unit_nama." Bulan ".$data_bulan[(int)$bulan]." ".$tahun;
            $waktu = Tanggal::LengkapHariPanjang(\Carbon\Carbon::now());
            $catatan = 'Catatan: Persentase Dikirim adalah terhadap Target, Persentase Diterima adalah terhadap Dikirim';
            $fileName = 'laporan-bulanan-'.$unit_nama.'-';
            $namafile = $fileName . date('Y-m-d_H-i-s') . '.xlsx';
            //dd($rincian_array,$judul,$waktu,$catatan);
            return Excel::download(new FormatExpLapBulanan($rincian_array,$judul,$waktu,$catatan), $namafile);
    }
    public function tahunan()
    {
        $data_bulan = array(
            1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'
        );
        $data_bulan_pendek = array(
            1=>'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'
        );
        $dataUnit = UnitKerja::where([['unit_jenis','=','1'],['unit_eselon','=','3']])->get();
        $dataSubFungsi = UnitKerja::where([['unit_jenis','=','1'],['unit_eselon','=','4']])->get();
        //dd($dataUnit);
        $unitFilter = $dataUnit->first();
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
        if (request('unit')<=0)
        {
            $unit_filter = $unitFilter->unit_kode;
        }
        else
        {
            $unit_filter = request('unit');
        }
        //ini kalo eselon 2 diaktifkan
        if ($unit_filter == "52000")
        {
            $unit_item = 'unit_parent';
        }
        else
        {
            $unit_item = 'unit_kode';
        }
        $dataUnitNama = UnitKerja::where('unit_kode',$unit_filter)->first();
        $unit_nama = $dataUnitNama->unit_nama;
        $data = Kegiatan::whereYear('keg_end',$tahun_filter)->orderBy('keg_start','asc')->get();
        //dd($data);
        $data_grafik_poin = array();
        $data_grafik_keg = array();
        $data_grafik_target = array();
        for ($i=1; $i <= 12 ; $i++) { //generate bulan
            $data_nilai = \DB::table('m_keg')
                ->leftJoin(\DB::raw("(select keg_id as kegid_terima, sum(keg_r_jumlah) as jumlah_terima, keg_r_jenis as jenis_terima from m_keg_realisasi where keg_r_jenis=2 group by kegid_terima) as keg_real_terima"),'keg_real_terima.kegid_terima','m_keg.keg_id')
                ->leftJoin(\DB::raw("(select keg_id as kegid_kirim, sum(keg_r_jumlah) as jumlah_kirim, keg_r_jenis as jenis_kirim from m_keg_realisasi where keg_r_jenis=1 group by kegid_kirim) as keg_real_kirim"),'keg_real_kirim.kegid_kirim','m_keg.keg_id')
				->where('m_keg.keg_timkerja',$unit_filter)
                ->whereMonth('m_keg.keg_end','=',(int)$i)
				->whereYear('m_keg.keg_end','=',$tahun_filter)
				->select(\DB::raw("month(m_keg.keg_end) as bulan, year(m_keg.keg_end) as tahun, sum(m_keg.keg_total_target) as keg_jml_target,count(*) as keg_jml,sum(jumlah_terima) as jml_terima,sum(jumlah_kirim) as jml_kirim"))
				->first();
            $data_grafik_target[] = array(
                'bulan' => $data_bulan_pendek[$i],
                'target' => $data_nilai->keg_jml_target,
                'kirim' =>  $data_nilai->jml_kirim,
                'terima' => $data_nilai->jml_terima
            );
        }
        //$data_grafik = json_encode($data_grafik);
        //dd($data_grafik_target);
        return view('laporan.tahunan',[
            'data_grafik_target'=>json_encode($data_grafik_target),
            'dataUnitkerja'=>$dataUnit,'dataTahun'=>$data_tahun,'tahun'=>$tahun_filter,'unit'=>$unit_filter,'unit_nama'=>$unit_nama,'dataBulan'=>$data_bulan,'dataSubFungsi'=>$dataSubFungsi,'data'=>$data,'unit_item'=>$unit_item]);
    }
    public function tahunanExport($unitkerja,$tahun)
    {
        $data_bulan = array(
            1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'
        );
        $dataUnit = UnitKerja::where([['unit_jenis','=','1'],['unit_eselon','=','3']])->get();
        $dataSubFungsi = UnitKerja::where([['unit_jenis','=','1'],['unit_eselon','=','4']])->get();
        //dd($dataUnit);

        $dataUnitNama = UnitKerja::where('unit_kode',$unitkerja)->first();
        $unit_nama = $dataUnitNama->unit_nama;
        $data = Kegiatan::leftJoin('t_unitkerja','m_keg.keg_timkerja','=','t_unitkerja.unit_kode')
            ->whereYear('keg_end','=',$tahun)
            ->where('keg_timkerja',$unitkerja)
            ->orderBy('keg_timkerja','asc')
            ->orderBy('keg_end','asc')
            ->get();
        //dd($data);
            foreach ($data as $item) {
                if ($item->RealisasiKirim->sum('keg_r_jumlah') > 0)
                {
                    $persen_terima = number_format(($item->RealisasiTerima->sum('keg_r_jumlah')/$item->RealisasiKirim->sum('keg_r_jumlah'))*100,2,",",".");
                }
                else
                {
                    $persen_terima = number_format(0,2,",",".");
                }
                $rincian_array[] = array(
                    'SUBFUNGSI' => $item->unit_nama,
                    'BULAN' => $data_bulan[(int) Carbon::parse($item->keg_end)->format('m')],
                    'TAHUN' => $tahun,
                    'KEG_ID' => $item->keg_id,
                    'KEGIATAN' => $item->keg_nama,
                    'TANGGAL MULAI' => Tanggal::Panjang($item->keg_start),
                    'TANGGAL BERAKHIR' => Tanggal::Panjang($item->keg_end),
                    'TARGET' => $item->Target->sum('keg_t_target'),
                    'DIKIRIM' => $item->RealisasiKirim->sum('keg_r_jumlah')." (".number_format(($item->RealisasiKirim->sum('keg_r_jumlah')/$item->Target->sum('keg_t_target'))*100,2,",",".")."%)",
                    'DITERIMA' => $item->RealisasiTerima->sum('keg_r_jumlah')." (".$persen_terima."%)"
                );
            }
            $judul = "Laporan Kegiatan [".$unitkerja."] ".$unit_nama." Tahun ".$tahun;
            $waktu = Tanggal::LengkapHariPanjang(\Carbon\Carbon::now());
            $catatan = 'Catatan: Persentase Dikirim adalah terhadap Target, Persentase Diterima adalah terhadap Dikirim';
            $fileName = 'laporan-tahunan-'.$unit_nama.'-';
            $namafile = $fileName . date('Y-m-d_H-i-s') . '.xlsx';
            //dd($rincian_array,$judul,$waktu,$catatan);
            return Excel::download(new FormatExpLapTahunan($rincian_array,$judul,$waktu,$catatan), $namafile);
    }
    public function KabkotaBulanan()
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
        return view('laporan.kabkota-bulan',['dataUnitkerja'=>$dataUnit,'unit'=>$unit_filter,'unitnama'=>$unit_nama->unit_nama,'dataBulan'=>$data_bulan,'dataTahun'=>$data_tahun,'tahun'=>$tahun_filter,'dataRincian'=>$data,'bulan'=>$bulan_filter]);
    }
    public function KabkotaTahunan()
    {
        $data_bulan = array(
            1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'
        );
        $data_bulan_pendek = array(
            1=>'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'
        );
        $dataUnit = UnitKerja::where([['unit_jenis','=','2'],['unit_eselon','=','3']])->get();
        $unitFilter = $dataUnit->first();
        $data_tahun = DB::table('m_keg')
        ->selectRaw('year(keg_end) as tahun')
        ->groupBy('tahun')
        //->whereYear('keg_end','<=',NOW())
        ->orderBy('tahun','asc')
         ->get();
        if (request('tahun')<=0)
        {
            $tahun_filter = date('Y');
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
        if (request('unit')<=0)
        {
            $unit_filter = $unitFilter->unit_kode;
        }
        else
        {
            $unit_filter = request('unit');
        }
        /*
        $i=12;
        $data_nilai = \DB::table('m_keg')
                ->leftJoin('m_keg_target','m_keg.keg_id','=','m_keg_target.keg_id')
                ->leftJoin('t_unitkerja','m_keg_target.keg_t_unitkerja','=','t_unitkerja.unit_kode')
                ->leftJoin(\DB::raw("(select keg_id as kegid_terima, keg_r_unitkerja as unit_terima, sum(keg_r_jumlah) as jumlah_terima, keg_r_jenis as jenis_terima from m_keg_realisasi where keg_r_jenis=2 and keg_r_unitkerja='".$unit_filter."' group by kegid_terima) as keg_real_terima"),'keg_real_terima.kegid_terima','m_keg.keg_id')
				->where('m_keg_target.keg_t_unitkerja',$unit_filter)
                ->whereMonth('m_keg.keg_end','=',(int)$i)
				->whereYear('m_keg.keg_end','=','2022')
				->where('m_keg_target.keg_t_target','>','0')
                ->select(\DB::raw("month(m_keg.keg_end) as bulan, year(m_keg.keg_end) as tahun,m_keg_target.keg_t_unitkerja,t_unitkerja.unit_nama, sum(m_keg_target.keg_t_target) as keg_jml_target, sum(m_keg_target.keg_t_point_waktu) as point_waktu, sum(m_keg_target.keg_t_point_jumlah) as point_keg_jumlah, sum(m_keg_target.keg_t_point) as point_keg_total, avg(m_keg_target.keg_t_point) as point_keg, avg(m_keg_target.spj_t_point) as point_spj, avg(m_keg_target.keg_t_point_total) as point_total, count(*) as keg_jml,sum(jumlah_terima) as jml_terima"))
				->first();
        dd($data_nilai); */
        $data_timkerja = UnitKerja::where([['unit_eselon','3'],['unit_jenis','1']])->get();
        //$kode_tim = '52520';
        //$kode_kabkota = '52010';
        /*hasil
        0 => {#1451 ▼
            +"keg_t_unitkerja": "52010"
            +"unit_nama": "BPS Kabupaten Lombok Barat"
            +"keg_jml_target": "1229"
            +"point_waktu": "274.0000"
            +"point_volume": "290.0000"
            +"point_jumlah": "285.2000"
            +"point_keg": "3.90684932"
            +"point_spj": "0.00000000"
            +"point_total": "3.90684932"
            +"keg_jml": 73
            +"kode_tim": "52530"
            +"nama_tim": "Fungsi Statistik Produksi"
            }
        */
        $data_nilai_tim1 = DB::table('m_keg')
                ->leftJoin('m_keg_target','m_keg.keg_id','=','m_keg_target.keg_id')
                ->leftJoin(DB::raw("(select unit_kode as kode_tim, unit_nama as nama_tim from t_unitkerja where unit_jenis='1' and unit_eselon='3') as timprov"),'m_keg.keg_timkerja','=','timprov.kode_tim')
                ->leftJoin('t_unitkerja','m_keg_target.keg_t_unitkerja','=','t_unitkerja.unit_kode')
                ->whereMonth('m_keg.keg_end','<=',$bulan_filter)
				->whereYear('m_keg.keg_end','=',$tahun_filter)
                ->where('m_keg_target.keg_t_unitkerja',$unit_filter)
				->where('m_keg_target.keg_t_target','>','0')
				->select(DB::raw("m_keg_target.keg_t_unitkerja,t_unitkerja.unit_nama, sum(m_keg_target.keg_t_target) as keg_jml_target, sum(m_keg_target.keg_t_point_waktu) as point_waktu, sum(m_keg_target.keg_t_point_jumlah) as point_volume, sum(m_keg_target.keg_t_point) as point_jumlah, avg(m_keg_target.keg_t_point) as point_keg, avg(m_keg_target.spj_t_point) as point_spj, avg(m_keg_target.keg_t_point_total) as point_total, count(*) as keg_jml,kode_tim,nama_tim"))
				->groupBy('m_keg_target.keg_t_unitkerja')
                ->groupBy('timprov.kode_tim')
				->orderBy('point_total','desc')
                ->orderBy('keg_jml_target','desc')
                ->orderBy('keg_jml','desc')
                ->take(4)->get();
        $data_nilai_tim2 = DB::table('m_keg')
                ->leftJoin('m_keg_target','m_keg.keg_id','=','m_keg_target.keg_id')
                ->leftJoin(DB::raw("(select unit_kode as kode_tim, unit_nama as nama_tim from t_unitkerja where unit_jenis='1' and unit_eselon='3') as timprov"),'m_keg.keg_timkerja','=','timprov.kode_tim')
                ->leftJoin('t_unitkerja','m_keg_target.keg_t_unitkerja','=','t_unitkerja.unit_kode')
                ->whereMonth('m_keg.keg_end','<=',$bulan_filter)
				->whereYear('m_keg.keg_end','=',$tahun_filter)
                ->where('m_keg_target.keg_t_unitkerja',$unit_filter)
				->where('m_keg_target.keg_t_target','>','0')
				->select(DB::raw("m_keg_target.keg_t_unitkerja,t_unitkerja.unit_nama, sum(m_keg_target.keg_t_target) as keg_jml_target, sum(m_keg_target.keg_t_point_waktu) as point_waktu, sum(m_keg_target.keg_t_point_jumlah) as point_volume, sum(m_keg_target.keg_t_point) as point_jumlah, avg(m_keg_target.keg_t_point) as point_keg, avg(m_keg_target.spj_t_point) as point_spj, avg(m_keg_target.keg_t_point_total) as point_total, count(*) as keg_jml,kode_tim,nama_tim"))
				->groupBy('m_keg_target.keg_t_unitkerja')
                ->groupBy('timprov.kode_tim')
				->orderBy('point_total','desc')
                ->orderBy('keg_jml_target','desc')
                ->orderBy('keg_jml','desc')
                ->offset(4)
                ->take(4)->get();
        //dd($data_timkerja,$data_nilai_tim1,$data_nilai_tim2);
        $data_grafik_poin = array();
        $data_grafik_keg = array();
        $data_grafik_target = array();
        for ($i=1; $i <= 12 ; $i++) { //generate bulan
            /*
            $data_nilai = \DB::table('m_keg')
                ->leftJoin('m_keg_target','m_keg.keg_id','=','m_keg_target.keg_id')
                ->leftJoin(\DB::raw("(select unit_kode as unit_kode_prov, unit_nama as unit_nama_prov, unit_parent as unit_parent_prov from t_unitkerja where unit_jenis='1') as unit_prov"),'m_keg.keg_unitkerja','=','unit_prov.unit_kode_prov')
                ->leftJoin('t_unitkerja','m_keg_target.keg_t_unitkerja','=','t_unitkerja.unit_kode')
                ->leftJoin(\DB::raw("(select keg_id as kegid_terima, keg_r_unitkerja as unit_terima, sum(keg_r_jumlah) as jumlah_terima, keg_r_jenis as jenis_terima from m_keg_realisasi where keg_r_jenis='2' group by kegid_terima) as keg_real_terima"),'m_keg.keg_id','keg_real_terima.kegid_terima')
				->where('m_keg_target.keg_t_unitkerja',$unit_filter)
                ->whereMonth('m_keg.keg_end','=',(int)$i)
				->whereYear('m_keg.keg_end','=',$tahun_filter)
				->where('m_keg_target.keg_t_target','>','0')
				->select(\DB::raw("month(m_keg.keg_end) as bulan, year(m_keg.keg_end) as tahun,m_keg_target.keg_t_unitkerja,t_unitkerja.unit_nama, sum(m_keg_target.keg_t_target) as keg_jml_target, sum(m_keg_target.keg_t_point_waktu) as point_waktu, sum(m_keg_target.keg_t_point_jumlah) as point_keg_jumlah, sum(m_keg_target.keg_t_point) as point_keg_total, avg(m_keg_target.keg_t_point) as point_keg, avg(m_keg_target.spj_t_point) as point_spj, avg(m_keg_target.keg_t_point_total) as point_total, count(*) as keg_jml,jumlah_terima"))
				->first();
                //lama
            */
            $data_nilai = \DB::table('m_keg')
                ->leftJoin('m_keg_target','m_keg.keg_id','=','m_keg_target.keg_id')
                ->leftJoin('t_unitkerja','m_keg_target.keg_t_unitkerja','=','t_unitkerja.unit_kode')
                ->leftJoin(\DB::raw("(select keg_id as kegid_terima, keg_r_unitkerja as unit_terima, sum(keg_r_jumlah) as jumlah_terima, keg_r_jenis as jenis_terima from m_keg_realisasi where keg_r_jenis=2 and keg_r_unitkerja='".$unit_filter."' group by kegid_terima) as keg_real_terima"),'keg_real_terima.kegid_terima','m_keg.keg_id')
                ->leftJoin(\DB::raw("(select keg_id as kegid_kirim, keg_r_unitkerja as unit_kirim, sum(keg_r_jumlah) as jumlah_kirim, keg_r_jenis as jenis_kirim from m_keg_realisasi where keg_r_jenis=1 and keg_r_unitkerja='".$unit_filter."' group by kegid_kirim) as keg_real_kirim"),'keg_real_kirim.kegid_kirim','m_keg.keg_id')
				->where('m_keg_target.keg_t_unitkerja',$unit_filter)
                ->whereMonth('m_keg.keg_end','=',(int)$i)
				->whereYear('m_keg.keg_end','=',$tahun_filter)
				->where('m_keg_target.keg_t_target','>','0')
                ->select(\DB::raw("month(m_keg.keg_end) as bulan, year(m_keg.keg_end) as tahun,m_keg_target.keg_t_unitkerja,t_unitkerja.unit_nama, sum(m_keg_target.keg_t_target) as keg_jml_target, sum(m_keg_target.keg_t_point_waktu) as point_waktu, sum(m_keg_target.keg_t_point_jumlah) as point_keg_jumlah, sum(m_keg_target.keg_t_point) as point_keg_total, avg(m_keg_target.keg_t_point) as point_keg, avg(m_keg_target.spj_t_point) as point_spj, avg(m_keg_target.keg_t_point_total) as point_total, count(*) as keg_jml,sum(jumlah_terima) as jml_terima,sum(jumlah_kirim) as jml_kirim"))
				->first();
            $data_grafik_poin[] = array(
                'bulan' => $data_bulan_pendek[$i],
                'poin' => number_format($data_nilai->point_total,3,".",",")
            );
            $data_grafik_keg[] = array(
                'bulan' => $data_bulan_pendek[$i],
                'kegiatan' => $data_nilai->keg_jml
            );
            $data_grafik_target[] = array(
                'bulan' => $data_bulan_pendek[$i],
                'target' => $data_nilai->keg_jml_target,
                'kirim' =>  $data_nilai->jml_kirim,
                'terima' => $data_nilai->jml_terima
            );
        }
        //$data_grafik = json_encode($data_grafik);
        //dd($data_grafik);
        $unit_nama = UnitKerja::where('unit_kode',$unit_filter)->first();
        $data = DB::table('m_keg')
                ->leftJoin('m_keg_target','m_keg.keg_id','=','m_keg_target.keg_id')
                ->leftJoin(DB::raw("(select keg_id, keg_r_unitkerja, sum(keg_r_jumlah) as jumlah_dikirim from m_keg_realisasi where keg_r_unitkerja='".$unit_filter."' and keg_r_jenis='1' group by keg_id) as pengiriman"),'m_keg.keg_id','=','pengiriman.keg_id')
                ->leftJoin(DB::raw("(select keg_id, keg_r_unitkerja, sum(keg_r_jumlah) as jumlah_diterima from m_keg_realisasi where keg_r_unitkerja='".$unit_filter."' and keg_r_jenis='2' group by keg_id) as penerimaan"),'m_keg.keg_id','=','penerimaan.keg_id')
                ->leftJoin(DB::raw("(select unit_kode as unit_kode_prov, unit_nama as unit_nama_prov, unit_parent as unit_parent_prov from t_unitkerja where unit_jenis='1') as unit_prov"),'m_keg.keg_unitkerja','=','unit_prov.unit_kode_prov')
                ->leftJoin(DB::raw("(select unit_kode as unit_kode_parent, unit_nama as unit_nama_parent from t_unitkerja where unit_jenis='1' and unit_eselon='3') as unit_parent"),'unit_prov.unit_parent_prov','=','unit_parent.unit_kode_parent')
                ->leftJoin('t_unitkerja','m_keg_target.keg_t_unitkerja','=','t_unitkerja.unit_kode')
                ->whereYear('m_keg.keg_end','=',$tahun_filter)
                ->where('m_keg_target.keg_t_target','>','0')
                ->where('m_keg_target.keg_t_unitkerja','=',$unit_filter)
				->select(DB::raw("m_keg_target.keg_t_unitkerja,t_unitkerja.unit_nama, month(m_keg.keg_end) as bulan_keg,m_keg.keg_id, m_keg.keg_nama, unit_kode_prov, unit_nama_prov, unit_kode_parent, unit_nama_parent, keg_start, keg_end, m_keg_target.keg_t_target, pengiriman.jumlah_dikirim, penerimaan.jumlah_diterima, m_keg_target.keg_t_point"))
				->orderBy('keg_end','asc')
                ->get();
        //dd($data);
        return view('laporan.kabkota-tahun',[
            'dataUnitkerja'=>$dataUnit,
            'unit'=>$unit_filter,
            'unitnama'=>$unit_nama->unit_nama,
            'dataBulan'=>$data_bulan,
            'dataTahun'=>$data_tahun,
            'tahun'=>$tahun_filter,
            'dataRincian'=>$data,
            'data_grafik_poin'=>json_encode($data_grafik_poin),
            'data_grafik_keg'=>json_encode($data_grafik_keg),
            'data_grafik_target'=>json_encode($data_grafik_target),
            'data_grafik_baris1'=>$data_nilai_tim1,
            'data_grafik_baris2'=>$data_nilai_tim2,
        ]);
    }
}

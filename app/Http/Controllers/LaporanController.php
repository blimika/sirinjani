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
        $dataUnitNama = UnitKerja::where('unit_kode',$unit_filter)->first();
        $unit_nama = $dataUnitNama->unit_nama;
        $data = Kegiatan::leftJoin('t_unitkerja','m_keg.keg_unitkerja','=','t_unitkerja.unit_kode')
            ->when($bulan_filter,function ($query) use ($bulan_filter) {
            return $query->whereMonth('keg_end','=',$bulan_filter);
            })
            ->whereYear('keg_end','=',$tahun_filter)->orderBy('keg_unitkerja','asc')->get();
        //dd($data);
        //$data_cek_unit = Kegiatan::leftJoin('t_unitkerja','m_keg.keg_unitkerja','=','t_unitkerja.unit_kode')->where('')
        return view('laporan.bulanan',['dataUnitkerja'=>$dataUnit,'dataTahun'=>$data_tahun,'tahun'=>$tahun_filter,'unit'=>$unit_filter,'unit_nama'=>$unit_nama,'dataBulan'=>$data_bulan,'bulan'=>$bulan_filter,'dataSubFungsi'=>$dataSubFungsi,'data'=>$data]);
    }
    public function bulananExport($unitkerja,$bulan,$tahun)
    {
        $data_bulan = array(
            1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'
        );
        $dataUnit = UnitKerja::where([['unit_jenis','=','1'],['unit_eselon','=','3']])->get();
        $dataSubFungsi = UnitKerja::where([['unit_jenis','=','1'],['unit_eselon','=','4']])->get();
        //dd($dataUnit);
        
        $dataUnitNama = UnitKerja::where('unit_kode',$unitkerja)->first();
        $unit_nama = $dataUnitNama->unit_nama;
        $data = Kegiatan::leftJoin('t_unitkerja','m_keg.keg_unitkerja','=','t_unitkerja.unit_kode')
            ->when($bulan,function ($query) use ($bulan) {
            return $query->whereMonth('keg_end','=',$bulan);
            })
            ->whereYear('keg_end','=',$tahun)
            ->where('unit_parent',$unitkerja)
            ->orderBy('keg_unitkerja','asc')->get();
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
                    'FUNGSI' => $unit_nama,
                    'SUBFUNGSI' => $item->unit_nama,
                    'BULAN' => $data_bulan[(int)$bulan],
                    'TAHUN' => $tahun,
                    'KEG_ID' => $item->keg_id,
                    'KEGIATAN' => $item->keg_nama,
                    'TANGGAL MULAI' => Tanggal::Panjang($item->keg_start),
                    'TANGGAL BERAKHIR' => Tanggal::Panjang($item->keg_end),
                    'TARGET' => $item->Target->sum('keg_t_target'),
                    'DIKIRIM' => $item->RealisasiKirim->sum('keg_r_jumlah'),
                    'DITERIMA' => $item->RealisasiTerima->sum('keg_r_jumlah'),
                    'PERSENTASE DIKIRIM' => number_format(($item->RealisasiKirim->sum('keg_r_jumlah')/$item->Target->sum('keg_t_target'))*100,2,",","."),
                    'PERSENTASE DITERIMA' => $persen_terima
                );
            }
            $rincian_array[]=array(
                'FUNGSI'=>'Catatan: Persentase Dikirim adalah terhadap Target, Persentase Diterima adalah terhadap Dikirim');
            $fileName = 'laporan-bulanan-'.$unit_nama.'-';
            $namafile = $fileName . date('Y-m-d_H-i-s') . '.xlsx';
            //dd($rincian_array);
            return Excel::download(new FormatViewExim($rincian_array), $namafile);
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
        $dataUnitNama = UnitKerja::where('unit_kode',$unit_filter)->first();
        $unit_nama = $dataUnitNama->unit_nama;
        $data = Kegiatan::leftJoin('t_unitkerja','m_keg.keg_unitkerja','=','t_unitkerja.unit_kode')->whereYear('keg_end','=',$tahun_filter)->orderBy('keg_end','asc')->get();
        //dd($data);
        return view('laporan.tahunan',['dataUnitkerja'=>$dataUnit,'dataTahun'=>$data_tahun,'tahun'=>$tahun_filter,'unit'=>$unit_filter,'unit_nama'=>$unit_nama,'dataBulan'=>$data_bulan,'dataSubFungsi'=>$dataSubFungsi,'data'=>$data]);
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
        $data = Kegiatan::leftJoin('t_unitkerja','m_keg.keg_unitkerja','=','t_unitkerja.unit_kode')
            ->whereYear('keg_end','=',$tahun)
            ->where('unit_parent',$unitkerja)
            ->orderBy('keg_unitkerja','asc')
            ->get();
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
                    'FUNGSI' => $unit_nama,
                    'SUBFUNGSI' => $item->unit_nama,
                    'BULAN' => $data_bulan[(int) Carbon::parse($item->keg_end)->format('m')],
                    'TAHUN' => $tahun,
                    'KEG_ID' => $item->keg_id,
                    'KEGIATAN' => $item->keg_nama,
                    'TANGGAL MULAI' => Tanggal::Panjang($item->keg_start),
                    'TANGGAL BERAKHIR' => Tanggal::Panjang($item->keg_end),
                    'TARGET' => $item->Target->sum('keg_t_target'),
                    'DIKIRIM' => $item->RealisasiKirim->sum('keg_r_jumlah'),
                    'DITERIMA' => $item->RealisasiTerima->sum('keg_r_jumlah'),
                    'PERSENTASE DIKIRIM' => number_format(($item->RealisasiKirim->sum('keg_r_jumlah')/$item->Target->sum('keg_t_target'))*100,2,",","."),
                    'PERSENTASE DITERIMA' => $persen_terima
                );
            }
            $rincian_array[]=array(
                'FUNGSI'=>'Catatan: Persentase Dikirim adalah terhadap Target, Persentase Diterima adalah terhadap Dikirim');
            $fileName = 'laporan-tahunan-'.$unit_nama.'-';
            $namafile = $fileName . date('Y-m-d_H-i-s') . '.xlsx';
            //dd($anggaran_array);
            return Excel::download(new FormatViewExim($rincian_array), $namafile);
    }
}

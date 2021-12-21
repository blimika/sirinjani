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

class SpjController extends Controller
{
    //
    public function CariSpj($spjrid)
    {
        $count = SpjRealisasi::where('spj_r_id',$spjrid)->count();
        $arr = array(
            'status'=>false,
            'hasil'=>'Realisasi SPJ kegiatan ini tidak tersedia'
        );
        if ($count > 0) 
        {
            //unitkerja
            $data = SpjRealisasi::where('spj_r_id',$spjrid)->first();
            if ($data->MasterKegiatan->keg_spj==1)
            {
                $spj = 'Ada';
            }
            else
            {
                $spj = 'Tidak';
            }
            $arr = array(
                'status'=>true,
                'keg_id'=>$data->keg_id,
                'keg_nama'=>$data->MasterKegiatan->keg_nama,
                'keg_unitkerja'=>$data->MasterKegiatan->keg_unitkerja,
                'keg_unitkerja_nama'=>$data->MasterKegiatan->Unitkerja->unit_nama,
                'keg_target'=>$data->MasterKegiatan->keg_total_target,
                'keg_satuan'=>$data->MasterKegiatan->keg_target_satuan,
                'keg_start'=>$data->MasterKegiatan->keg_start,
                'keg_start_nama'=>Tanggal::HariPanjang($data->MasterKegiatan->keg_start),
                'keg_end'=>$data->MasterKegiatan->keg_end,
                'keg_end_nama'=>Tanggal::HariPanjang($data->MasterKegiatan->keg_end),
                'keg_jenis'=>$data->MasterKegiatan->keg_jenis,
                'keg_jenis_nama'=>$data->MasterKegiatan->JenisKeg->jkeg_nama,
                'keg_spj'=>$data->MasterKegiatan->keg_spj,  
                'keg_spj_nama'=>$spj,
                'keg_dibuat_oleh'=>$data->MasterKegiatan->keg_dibuat_oleh,
                'keg_diupdate_oleh'=>$data->MasterKegiatan->keg_diupdate_oleh,
                'keg_created_at'=>$data->MasterKegiatan->created_at,
                'keg_updated_at'=>$data->MasterKegiatan->updated_at,
                'spj_r_id'=>$data->spj_r_id,
                'spj_r_unitkerja'=>$data->spj_r_unitkerja,
                'spj_r_unitkerja_nama'=>$data->Unitkerja->unit_nama,
                'spj_r_jumlah'=>$data->spj_r_jumlah,
                'spj_r_tgl'=>$data->spj_r_tgl,
                'spj_r_tgl_nama'=>Tanggal::HariPanjang($data->spj_r_tgl),
                'spj_r_jenis'=>$data->spj_r_jenis,
                'spj_r_jenis_nama'=>$data->JenisRealisasi->rkeg_nama,
                'spj_r_link'=>$data->spj_r_link,
                'spj_r_ket'=>$data->spj_r_ket,
                'spj_r_dibuat_oleh'=>$data->spj_r_dibuat_oleh,
                'spj_r_diupdate_oleh'=>$data->spj_r_diupdate_oleh,
                'created_at'=>$data->created_at,
                'updated_at'=>$data->updated_at   
            );
        }
        return Response()->json($arr);
    }
    public function kirimSpj(Request $request)
    {
        //dd($request->all());
        $count = Kegiatan::where('keg_id',$request->keg_id)->count();
        if ($count > 0)
        {
            //kegiatan ini ada
            $data = new SpjRealisasi();
            $data->keg_id = $request->keg_id;
            $data->spj_r_unitkerja = $request->spj_r_unitkerja;
            $data->spj_r_tgl = $request->spj_r_tgl;
            $data->spj_r_jumlah = $request->spj_r_jumlah;
            $data->spj_r_jenis = 1;
            $data->spj_r_link = $request->spj_r_link;
            $data->spj_r_ket = $request->spj_r_ket;
            $data->spj_r_dibuat_oleh = Auth::user()->username;
            $data->spj_r_diupdate_oleh = Auth::user()->username;
            $data->save();

            $pesan_error="Pengiriman SPJ oleh ". $data->Unitkerja->unit_nama.' sudah disimpan';
            $pesan_warna="success";
        }
        else 
        {
            //kegiatan ini tidak ada
            $pesan_error="Kegiatan ini tidak ada";
            $pesan_warna="danger";
        }
        Session::flash('message', $pesan_error);
        Session::flash('message_type', $pesan_warna);
        return redirect()->route('kegiatan.detil',$request->keg_id);
    }
    public function UpdatekirimSpj(Request $request)
    {
        //dd($request->all());
        $count = SpjRealisasi::where('spj_r_id',$request->spj_r_id)->count();
        if ($count > 0)
         {
             //kegiatan ini ada
             $data = SpjRealisasi::where('spj_r_id',$request->spj_r_id)->first();
             $data->spj_r_tgl = $request->spj_r_tgl;
             $data->spj_r_jumlah = $request->spj_r_jumlah;
             $data->spj_r_link = $request->spj_r_link;
             $data->spj_r_ket = $request->spj_r_ket;
             $data->spj_r_diupdate_oleh = Auth::user()->username;
             $data->update();
 
             $pesan_error="Pengiriman SPJ dari ". $data->Unitkerja->unit_nama.' sudah diupdate';
             $pesan_warna="success";
         }
         else 
         {
             //kegiatan ini tidak ada
             $pesan_error="Kegiatan ini tidak ada";
             $pesan_warna="danger";
         }
         Session::flash('message', $pesan_error);
         Session::flash('message_type', $pesan_warna);
         return redirect()->route('kegiatan.detil',$request->keg_id);
    }
    public function HapuskirimSpj(Request $request)
    {
        $count = SpjRealisasi::where('spj_r_id','=',$request->id)->count();
        $arr = array(
            'status'=>false,
            'hasil'=>'data realiasi pengiriman spj ini tidak tersedia'
        );
        if ($count>0)
        {
            $data = SpjRealisasi::where('spj_r_id','=',$request->id)->first();
            $nama = $data->Unitkerja->unit_nama;
            $tgl = Tanggal::Panjang($data->spj_r_tgl);
            $data->delete();
            $arr = array(
                'status'=>true,
                'hasil'=>'Data pengiriman spj dari '.$nama.' tanggal '.$tgl.' berhasil dihapus'
            );
        }
        return Response()->json($arr);
    }
    public function terimaSpj(Request $request)
    {
        $nilai = Generate::NilaiSpjRealisasi($request->keg_id,$request->spj_r_unitkerja);
        //dd($nilai);
        $count = Kegiatan::where('keg_id',$request->keg_id)->count();
        if ($count > 0)
        {
            //kegiatan ini ada
            //buat realisasi 
            //dan nilai di tabel keg_target
            $data = new SpjRealisasi();
            $data->keg_id = $request->keg_id;
            $data->spj_r_unitkerja = $request->spj_r_unitkerja;
            $data->spj_r_tgl = $request->spj_r_tgl;
            $data->spj_r_jumlah = $request->spj_r_jumlah;
            $data->spj_r_jenis = 2;
            $data->spj_r_ket = Auth::user()->username;
            $data->spj_r_dibuat_oleh = Auth::user()->username;
            $data->spj_r_diupdate_oleh = Auth::user()->username;
            $data->save();
            $nilai = Generate::NilaiSpjRealisasi($request->keg_id,$request->spj_r_unitkerja);
            //update nilai KegTarget
            $dataNilai = SpjTarget::where([
				['keg_id',$request->keg_id],
				['spj_t_unitkerja',$request->spj_r_unitkerja],
            ])->first();
            $dataNilai->spj_t_point_waktu = $nilai['nilai_waktu'];
            $dataNilai->spj_t_point_jumlah = $nilai['nilai_volume'];
            $dataNilai->spj_t_point = $nilai['nilai_total'];
            $dataNilai->spj_t_diupdate_oleh = Auth::user()->username;
            $dataNilai->update();
            //nilai spj ini diupdate sama dgn di keg_t_target
            $dataNilaiKegSpj = KegTarget::where([
				['keg_id',$request->keg_id],
				['keg_t_unitkerja',$request->spj_r_unitkerja],
            ])->first();
            $dataNilaiKegSpj->spj_t_point_waktu = $nilai['nilai_waktu'];
            $dataNilaiKegSpj->spj_t_point_jumlah = $nilai['nilai_volume'];
            $dataNilaiKegSpj->spj_t_point = $nilai['nilai_total'];
            $dataNilaiKegSpj->keg_t_point_total = ($nilai['nilai_total'] + $dataNilaiKegSpj->keg_t_point)/2;
            $dataNilaiKegSpj->update();
            //batasnya update
            $pesan_error="Konfirmasi Penerimaan SPJ dari ". $data->Unitkerja->unit_nama.' sudah disimpan';
            $pesan_warna="success";
        }
        else 
        {
            //kegiatan ini tidak ada
            $pesan_error="Kegiatan ini tidak ada";
            $pesan_warna="danger";
        }
        Session::flash('message', $pesan_error);
        Session::flash('message_type', $pesan_warna);
        return redirect()->route('kegiatan.detil',$request->keg_id);
    }
    public function UpdateterimaSpj(Request $request)
    {
        //dd($request->all());
        $count = SpjRealisasi::where('spj_r_id',$request->spj_r_id)->count();
        if ($count > 0)
         {
             //kegiatan ini ada
             $data = SpjRealisasi::where('spj_r_id',$request->spj_r_id)->first();
             $data->spj_r_tgl = $request->spj_r_tgl;
             $data->spj_r_jumlah = $request->spj_r_jumlah;
             $data->spj_r_ket = Auth::user()->username;
             $data->spj_r_diupdate_oleh = Auth::user()->username;
             $data->update();

             $nilai = Generate::NilaiSpjRealisasi($data->keg_id,$data->spj_r_unitkerja);
             //update nilai KegTarget
             $dataNilai = SpjTarget::where([
                ['keg_id',$data->keg_id],
                ['spj_t_unitkerja',$data->spj_r_unitkerja],
             ])->first();
             $dataNilai->spj_t_point_waktu = $nilai['nilai_waktu'];
             $dataNilai->spj_t_point_jumlah = $nilai['nilai_volume'];
             $dataNilai->spj_t_point = $nilai['nilai_total'];
             $dataNilai->spj_t_diupdate_oleh = Auth::user()->username;
             $dataNilai->update();
             //nilai spj ini diupdate sama dgn di keg_t_target
                $dataNilaiKegSpj = KegTarget::where([
                    ['keg_id',$data->keg_id],
                    ['keg_t_unitkerja',$data->spj_r_unitkerja],
                ])->first();
                $dataNilaiKegSpj->spj_t_point_waktu = $nilai['nilai_waktu'];
                $dataNilaiKegSpj->spj_t_point_jumlah = $nilai['nilai_volume'];
                $dataNilaiKegSpj->spj_t_point = $nilai['nilai_total'];
                $dataNilaiKegSpj->keg_t_point_total = ($nilai['nilai_total'] + $dataNilaiKegSpj->keg_t_point)/2;
                $dataNilaiKegSpj->update();
            //batasnya update
             $pesan_error="Penerimaan SPJ dari ". $data->Unitkerja->unit_nama.' sudah diupdate';
             $pesan_warna="success";
         }
         else 
         {
             //kegiatan ini tidak ada
             $pesan_error="Kegiatan ini tidak ada";
             $pesan_warna="danger";
         }
         Session::flash('message', $pesan_error);
         Session::flash('message_type', $pesan_warna);
         return redirect()->route('kegiatan.detil',$request->keg_id);
    }
    public function HapusterimaSpj(Request $request)
    {
        $count = SpjRealisasi::where('spj_r_id','=',$request->id)->count();
        $arr = array(
            'status'=>false,
            'hasil'=>'data realiasi pengiriman spj ini tidak tersedia'
        );
        if ($count>0)
        {
            $data = SpjRealisasi::where('spj_r_id','=',$request->id)->first();
            $nama = $data->Unitkerja->unit_nama;
            $keg_id = $data->keg_id;
            $unitkerja = $data->spj_r_unitkerja;
            $tgl = Tanggal::Panjang($data->spj_r_tgl);
            $data->delete();

            $nilai = Generate::NilaiSpjRealisasi($keg_id,$unitkerja);
             //update nilai KegTarget
            $dataNilai = SpjTarget::where([
                ['keg_id',$keg_id],
                ['spj_t_unitkerja',$unitkerja],
             ])->first();
             $dataNilai->spj_t_point_waktu = $nilai['nilai_waktu'];
             $dataNilai->spj_t_point_jumlah = $nilai['nilai_volume'];
             $dataNilai->spj_t_point = $nilai['nilai_total'];
             $dataNilai->spj_t_diupdate_oleh = Auth::user()->username;
             $dataNilai->update();
             //nilai spj ini diupdate sama dgn di keg_t_target
             $dataNilaiKegSpj = KegTarget::where([
                ['keg_id',$keg_id],
                ['keg_t_unitkerja',$unitkerja],
            ])->first();
            $dataNilaiKegSpj->spj_t_point_waktu = $nilai['nilai_waktu'];
            $dataNilaiKegSpj->spj_t_point_jumlah = $nilai['nilai_volume'];
            $dataNilaiKegSpj->spj_t_point = $nilai['nilai_total'];
            $dataNilaiKegSpj->keg_t_point_total = ($nilai['nilai_total'] + $dataNilaiKegSpj->keg_t_point)/2;
            $dataNilaiKegSpj->update();
            //batasnya update
             $arr = array(
                    'status'=>true,
                    'hasil'=>'Data penerimaan spj dari '.$nama.' tanggal '.$tgl.' berhasil dihapus'
                );
        }
        return Response()->json($arr);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Kegiatan;
use App\KegiatanLama;
use App\KegRealisasi;
use App\KegRealisasiLama;
use App\KegTargetLama;
use App\KegTarget;
use App\SpjTarget;
use App\SpjTargetLama;
use App\SpjRealisasi;
use App\SpjRealisasiLama;

class DataController extends Controller
{
    //
    public function index()
    {
        $keg = Kegiatan::count();
        $keg_target = KegTarget::count();
        $keg_realisasi = KegRealisasi::count();
        $spj_target = SpjTarget::count();
        $spj_realisasi = SpjRealisasi::count();
        //$keg_lama = DB::connection('mysql2')->select("select * from kegiatan");
        $keg_lama = KegiatanLama::count();
        $keg_target_lama = KegTargetLama::count();
        $keg_realisasi_lama = KegRealisasiLama::count();
        $spj_target_lama = SpjTargetLama::count();
        $spj_realisasi_lama = SpjRealisasiLama::count();
        //dd($keg_lama);
        return view('db.index',['keg'=>$keg,'keg_lama'=>$keg_lama,'keg_target'=>$keg_target,'keg_target_lama'=>$keg_target_lama,'keg_realisasi'=>$keg_realisasi,'keg_realisasi_lama'=>$keg_realisasi_lama,'spj_target'=>$spj_target,'spj_realisasi'=>$spj_realisasi,'spj_target_lama'=>$spj_target_lama,'spj_realisasi_lama'=>$spj_realisasi_lama]);
    }
    public function Sinkron()
    {
        if (Auth::user()->level == 9)
        {
            if (request('force') <=0)
            {
                $force = 0;
            }
            else 
            {
                $force = request('force');
            }

            $keg = Kegiatan::count();
            if ($keg > 0)
            {
               //datanya sudah ada
                if ($force == 1)
                {
                    //paksa sinkron
                }
                else 
                {
                    $pesan_error="data sudah pernah di sinkronisasi";
                    $pesan_warna="danger";
                }
            }
            else
            {
                 //sinkron dimulai
                 $keg_lama = KegiatanLama::get();
                 $keg_target_lama = KegTargetLama::get();
                 $keg_realisasi_lama = KegRealisasiLama::get();
                 $spj_target_lama = SpjTargetLama::get();
                 $spj_realisasi_lama = SpjRealisasiLama::get();
                 //kegiatan
                 $data_keg = [];
                 $data_target = [];
                 $data_realisasi = [];
                 $data_spj = [];
                 $data_spj_realisasi = [];
                 //keg_id, keg_nama, keg_unitkerja, keg_start, keg_end, keg_jenis, keg_total_target, keg_target_satuan, keg_spj, keg_info, keg_dibuat_oleh, keg_diupdate_oleh, keg_dibuat_waktu, keg_diupdate_waktu
                 //keg_id	keg_nama	keg_unitkerja	keg_start	keg_end	keg_jenis	keg_total_target	keg_target_satuan	keg_spj	keg_info	keg_dibuat_oleh	keg_diupdate_oleh	created_at	updated_at
                 foreach ($keg_lama as $item_keg) {
                     $data_keg[]=[
                        'keg_id' => $item_keg->keg_id,
                        'keg_nama' => $item_keg->keg_nama,
                        'keg_unitkerja' => $item_keg->keg_unitkerja,
                        'keg_start' => $item_keg->keg_start,	
                        'keg_end' => $item_keg->keg_end,	
                        'keg_jenis' => $item_keg->keg_jenis,	
                        'keg_total_target' => $item_keg->keg_total_target,	
                        'keg_target_satuan' => $item_keg->keg_target_satuan,	
                        'keg_spj' => $item_keg->keg_spj,
                        'keg_info' => $item_keg->keg_info,	
                        'keg_dibuat_oleh' => $item_keg->keg_dibuat_oleh,	
                        'keg_diupdate_oleh' => $item_keg->keg_diupdate_oleh,	
                        'created_at' => $item_keg->keg_dibuat_waktu,	
                        'updated_at' => $item_keg->keg_diupdate_waktu,
                     ];
                 }
                 //keg_t_id, keg_id, keg_t_unitkerja, keg_t_target, keg_t_point_waktu, keg_t_point_jumlah, keg_t_point,keg_t_dibuat_oleh, keg_t_diupdate_oleh, keg_t_dibuat_waktu, keg_t_diupdate_waktu

                 //keg_t_id	keg_id	keg_t_unitkerja	keg_t_target	keg_t_point_waktu	keg_t_point_jumlah	keg_t_point	keg_t_dibuat_oleh	keg_t_diupdate_oleh	created_at	updated_at	
                 foreach ($keg_target_lama as $item_target) {
                     $data_target[]=[
                        'keg_t_id' => $item_target->keg_t_id,	
                        'keg_id' => $item_target->keg_id,	
                        'keg_t_unitkerja' => $item_target->keg_t_unitkerja,	
                        'keg_t_target' => $item_target->keg_t_target,	
                        'keg_t_point_waktu' => $item_target->keg_t_point_waktu,	
                        'keg_t_point_jumlah' => $item_target->keg_t_point_jumlah,	
                        'keg_t_point' => $item_target->keg_t_point,	
                        'keg_t_dibuat_oleh' => $item_target->keg_t_dibuat_oleh,	
                        'keg_t_diupdate_oleh' => $item_target->keg_t_diupdate_oleh,	
                        'created_at' => $item_target->keg_t_dibuat_waktu,
                        'updated_at' => $item_target->keg_t_diupdate_waktu,	
                     ];
                 }
                 //keg_d_id, keg_id, keg_d_unitkerja, keg_d_jumlah, keg_d_tgl, keg_d_jenis, keg_d_link_laci, keg_d_ket, keg_d_dibuat_oleh, keg_d_diupdate_oleh, keg_d_dibuat_waktu, keg_d_diupdate_waktu
                 //keg_r_id	keg_id	keg_r_unitkerja	keg_r_jumlah	keg_r_tgl	keg_r_jenis	keg_r_link	keg_r_ket	keg_r_dibuat_oleh	keg_r_diupdate_oleh	created_at	updated_at
                 foreach ($keg_realisasi_lama as $item_real) {
                     $data_realisasi[]=[
                        'keg_r_id' => $item_real->keg_d_id,
                        'keg_id' => $item_real->keg_id,
                        'keg_r_unitkerja' => $item_real->keg_d_unitkerja,
                        'keg_r_jumlah' => $item_real->keg_d_jumlah,
                        'keg_r_tgl' => $item_real->keg_d_tgl,
                        'keg_r_jenis' => $item_real->keg_d_jenis,
                        'keg_r_link' => $item_real->keg_d_link_laci,
                        'keg_r_ket' => $item_real->keg_d_ket,
                        'keg_r_dibuat_oleh' => $item_real->keg_d_dibuat_oleh,
                        'keg_r_diupdate_oleh' => $item_real->keg_d_diupdate_oleh,
                        'created_at' => $item_real->keg_d_dibuat_waktu,
                        'updated_at' => $item_real->keg_d_diupdate_waktu
                     ];
                 }
                 //keg_s_id, keg_id, keg_s_unitkerja, keg_s_target, keg_s_point_waktu, keg_s_point_jumlah, keg_s_point, keg_s_dibuat_oleh, keg_s_diupdate_oleh, keg_s_dibuat_waktu, keg_s_diupdate_waktu
                 //spj_t_id	keg_id	spj_t_unitkerja	spj_t_target	spj_t_point_waktu	spj_t_point_jumlah	spj_t_point	spj_t_dibuat_oleh	spj_t_diupdate_oleh	created_at	updated_at	
                 foreach ($spj_target_lama as $item_spj) {
                    $data_spj[] = [
                        'spj_t_id' => $item_spj->keg_s_id,
                        'keg_id' => $item_spj->keg_id,	
                        'spj_t_unitkerja' => $item_spj->keg_s_unitkerja,	
                        'spj_t_target' => $item_spj->keg_s_target,	
                        'spj_t_point_waktu' => $item_spj->keg_s_point_waktu,	
                        'spj_t_point_jumlah' => $item_spj->keg_s_point_jumlah,	
                        'spj_t_point' => $item_spj->keg_s_point,	
                        'spj_t_dibuat_oleh' => $item_spj->keg_s_dibuat_oleh,	
                        'spj_t_diupdate_oleh' => $item_spj->keg_s_diupdate_oleh,
                        'created_at' => $item_spj->keg_s_dibuat_waktu,	
                        'updated_at' => $item_spj->keg_s_diupdate_waktu	
                    ];
                 }
                 //spj_d_id, keg_id, spj_d_unitkerja, spj_d_jumlah, spj_d_tgl, spj_d_jenis, spj_d_link_laci, spj_d_ket, spj_d_dibuat_oleh, spj_d_diupdate_oleh, spj_d_dibuat_waktu, spj_d_diupdate_waktu
                 //spj_r_id	keg_id	spj_r_unitkerja	spj_r_jumlah	spj_r_tgl	spj_r_jenis	spj_r_link	spj_r_ket	spj_r_dibuat_oleh	spj_r_diupdate_oleh	created_at	updated_at
                 foreach ($spj_realisasi_lama as $item_spj_real) {
                    $data_spj_realisasi[] = [
                        'spj_r_id' => $item_spj_real->spj_d_id,	
                        'keg_id' => $item_spj_real->keg_id,	
                        'spj_r_unitkerja' => $item_spj_real->spj_d_unitkerja,	
                        'spj_r_jumlah' => $item_spj_real->spj_d_jumlah,	
                        'spj_r_tgl' => $item_spj_real->spj_d_tgl,	
                        'spj_r_jenis' => $item_spj_real->spj_d_jenis,	
                        'spj_r_link' => $item_spj_real->spj_d_link_laci,	
                        'spj_r_ket' => $item_spj_real->spj_d_ket,	
                        'spj_r_dibuat_oleh' => $item_spj_real->spj_d_dibuat_oleh,	
                        'spj_r_diupdate_oleh' => $item_spj_real->spj_d_diupdate_oleh,	
                        'created_at' => $item_spj_real->spj_d_dibuat_waktu,	
                        'updated_at' => $item_spj_real->spj_d_diupdate_waktu
                    ];
                 }
                 //dd($data_keg);
                 foreach (array_chunk($data_keg,1000) as $k)  
                    {
                        DB::table('m_keg')->insert($k); 
                    }
                 //DB::table('m_keg')->insert($data_keg);
                 foreach (array_chunk($data_target,1000) as $t)  
                    {
                        DB::table('m_keg_target')->insert($t); 
                    }
                 //DB::table('m_keg_target')->insert($data_target);
                 foreach (array_chunk($data_realisasi,1000) as $r)  
                    {
                        DB::table('m_keg_realisasi')->insert($r); 
                    }
                //DB::table('m_keg_realisasi')->insert($data_realisasi);
                foreach (array_chunk($data_spj,1000) as $s)  
                    {
                        DB::table('m_spj_target')->insert($s); 
                    }
                    foreach (array_chunk($data_spj_realisasi,1000) as $sr)  
                    {
                        DB::table('m_spj_realisasi')->insert($sr); 
                    }
                 $pesan_error="data sudah di sinkronisasi";
                 $pesan_warna="success";
            }
        }
        else
        {
            $pesan_error="tidak mempunyai hak untuk sinkoronisasi";
            $pesan_warna="danger";
        }
        Session::flash('message', $pesan_error);
        Session::flash('message_type', $pesan_warna);
        return redirect()->route('db.index');
    }
}

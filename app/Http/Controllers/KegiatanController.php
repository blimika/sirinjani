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

class KegiatanController extends Controller
{
    //
    public function index()
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
        //dd($dataKegiatan);
        return view('kegiatan.index',['dataKeg'=>$dataKegiatan,'dataUnitkerja'=>$dataUnit,'bulan'=>$bulan_filter,'tahun'=>$tahun_filter,'dataBulan'=>$data_bulan,'dataTahun'=>$data_tahun,'unit'=>request('unit')]);
    }

    public function bidang()
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
        else
        {
            $tahun_filter = request('tahun');
        }
        $dataUnit = UnitKerja::where([['unit_jenis','=','1'],['unit_eselon','=','3']])->get();
        $dataKegiatan = Kegiatan::leftJoin('t_unitkerja','m_keg.keg_unitkerja','=','t_unitkerja.unit_kode')
                        ->when(request('unit'),function ($query){
                            return $query->where('t_unitkerja.unit_parent','=',request('unit'));
                        })
                        ->when(request('bulan'),function ($query){
                            return $query->whereMonth('keg_start','=',request('bulan'));
                        })
                        ->whereYear('keg_start','=',$tahun_filter)
                        ->orderBy('m_keg.keg_unitkerja','asc')
                        ->orderBy('m_keg.keg_start','asc')
                        ->get();
        //dd($dataUnit);
        return view('kegiatan.bidang',['dataKeg'=>$dataKegiatan,'dataUnitkerja'=>$dataUnit,'bulan'=>request('bulan'),'tahun'=>$tahun_filter,'dataBulan'=>$data_bulan,'dataTahun'=>$data_tahun,'unit'=>request('unit')]);
    }
    public function tambah()
    {
        if (Auth::user()->level > 2)
        {
            //selain operator provinsi dan admin
            if (Auth::user()->level == 4 and Auth::user()->NamaWilayah->bps_jenis == 2)
            {
                //cek admin kabkota
                //peringatan tidak bisa mengakses halaman ini
                return view('kegiatan.warning',['keg_id'=>0]);
            }
            else
            {
                if (Auth::user()->level == 3)
                {
                    //operator provinsi list unitProv hanya di bidangnya
                    $unitProv = UnitKerja::where([['unit_jenis','=','1'],['unit_eselon','=','4'],['unit_parent','=',Auth::user()->kodeunit]])->get();
                }
                else
                {
                    //list semua unitProv eselon 4 di provinsi
                    $unitProv = UnitKerja::where([['unit_jenis','=','1'],['unit_eselon','=','4']])->get();
                }
                $unitTarget = UnitKerja::where([['unit_jenis','=','2'],['unit_eselon','=','3']])->get();
                $kegJenis = KegJenis::get();
                return view('kegiatan.tambah',[
                    'unitTarget'=>$unitTarget,
                    'unitProv'=>$unitProv,
                    'kegJenis'=>$kegJenis
                ]);
            }

        }
        else
        {
            //operator kabkota dan pemantau tidak bisa mengakses ini
            //beri info warning
            return view('kegiatan.warning',['keg_id'=>0]);
        }
    }
    public function simpan(Request $request)
    {
        //dd($request->all());
        //cek nama kegiatan di bidang tsb sudah pernah di input dngn judul yg sama
        $count = Kegiatan::where([
            ['keg_nama','=',trim($request->keg_nama)],
            ['keg_unitkerja','=',$request->keg_unitkerja]
            ])->count();
        if ($count > 0)
        {
            //sudah pernah ada kegiatan
            $pesan_error='Nama kegiatan (ini) sudah ada';
            $pesan_warna='danger';
        }
        else
        {
            $data = new Kegiatan();
            $data->keg_nama = trim($request->keg_nama);
            $data->keg_unitkerja = $request->keg_unitkerja;
            $data->keg_jenis = $request->keg_jenis;
            $data->keg_start = $request->keg_start;
            $data->keg_end = $request->keg_end;
            $data->keg_target_satuan = $request->keg_satuan;
            $data->keg_total_target = $request->keg_total_target;
            $data->keg_spj = $request->keg_spj;
            $data->keg_dibuat_oleh = Auth::user()->username;
            $data->keg_diupdate_oleh = Auth::user()->username;
            $data->save();

            $keg_id = $data->keg_id;
            $unit_nama = $data->Unitkerja->unit_nama;
            //target masing2 kabkota
            foreach ($request->keg_kabkota as $key => $v)
            {
                if ($v > 0)
                {
                    //ada isian targetnya
                    $target = $v;
                }
                else
                {
                    //target tetep di set nol
                    $target = 0;
                }
                $dataTarget = new KegTarget();
                $dataTarget->keg_id = $keg_id;
                $dataTarget->keg_t_unitkerja = $key;
                $dataTarget->keg_t_target = $target;
                $dataTarget->keg_t_point_waktu = 0;
                $dataTarget->keg_t_point_jumlah = 0;
                $dataTarget->keg_t_point = 0;
                $dataTarget->keg_t_dibuat_oleh = Auth::user()->username;
                $dataTarget->keg_t_diupdate_oleh = Auth::user()->username;
                $dataTarget->save();

                //jika target lebih besar 0 (nol)
                //kirim notif
                if ($target > 0)
                {
                    //jenisnotif kegiatan = 3
                    //cari dulu operator kabkotanya
                    $count_op = User::where([['kodeunit',$key],['level','>','1']])->count();
                    if ($count_op > 0)
                    {
                        //ada operator kabkotanya
                        //level > 1 (pemantau)
                        $nofif_isi = 'Kegiatan Baru ['.$keg_id.'] <i>'.trim($request->keg_nama).'</i> mulai tanggal '.Tanggal::HariPanjang($request->keg_start).' s.d. '.Tanggal::HariPanjang($request->keg_end).' dengan target '.$target.' '.$request->keg_satuan;
                        $data_notif = User::where([['kodeunit',$key],['level','>','1']])->get();
                        foreach ($data_notif as $item)
                        {
                            $notif = new Notifikasi();
                            $notif->keg_id = $keg_id;
                            $notif->notif_dari = Auth::user()->username;
                            $notif->notif_untuk = $item->username;
                            $notif->notif_isi = $nofif_isi;
                            $notif->notif_jenis = '3';
                            $notif->save();

                            //testing notif ke email
                            if (env('APP_MAIL_MODE') == true)
                            {
                                $data_keg = Kegiatan::where('keg_id',$keg_id)->first();
                                if ($data_keg->keg_spj == 1)
                                {
                                    $keg_spj = 'Ada';
                                }
                                else
                                {
                                    $keg_spj = 'Tidak';
                                }
                                $objEmail = new \stdClass();
                                $objEmail->keg_id = $keg_id;
                                $objEmail->keg_nama = $data_keg->keg_nama;
                                $objEmail->keg_jenis = $data_keg->JenisKeg->jkeg_nama;
                                $objEmail->keg_target = $target;
                                $objEmail->keg_satuan = $data_keg->keg_target_satuan;
                                $objEmail->keg_tgl_mulai = Tanggal::HariPanjang($data_keg->keg_start);
                                $objEmail->keg_tgl_selesai = Tanggal::HariPanjang($data_keg->keg_end);
                                $objEmail->keg_sm = $data_keg->Unitkerja->unit_nama;
                                $objEmail->keg_spj = $keg_spj;
                                $objEmail->keg_total_target = $data_keg->keg_total_target;
                                $objEmail->keg_tgl_dibuat = Tanggal::LengkapHariPanjang($data_keg->created_at);
                                $objEmail->keg_operator = $data_keg->keg_dibuat_oleh;
                                //coba email
                                $dataemail = $item->email;
                                //$dataemail = "pdyatmika@gmail.com";
                                Mail::to($dataemail)->send(new MailKegiatan($objEmail));

                            }
                            //batas testing
                        }
                    }
                }

            }
            if ($request->keg_spj == 1)
            {
                //ada permintaan spj
                //data spj di insert
                //spj_kabkota

                foreach ($request->spj_kabkota as $key => $v)
                {
                    if ($v > 0)
                    {
                        //ada isian targetnya
                        $target = $v;
                    }
                    else
                    {
                        //target tetep di set nol
                        $target = 0;
                    }
                    $dataSpj = new SpjTarget();
                    $dataSpj->keg_id = $keg_id;
                    $dataSpj->spj_t_unitkerja = $key;
                    $dataSpj->spj_t_target = $target;
                    $dataSpj->spj_t_point_waktu = 0;
                    $dataSpj->spj_t_point_jumlah = 0;
                    $dataSpj->spj_t_point = 0;
                    $dataSpj->spj_t_dibuat_oleh = Auth::user()->username;
                    $dataSpj->spj_t_diupdate_oleh = Auth::user()->username;
                    $dataSpj->save();
                }
            }
            $pesan_error='Kegiatan ini sudah di simpan';
            $pesan_warna='success';
            if (env('APP_AKTIVITAS_MODE') == true)
            {
                //catat aktivitas tambah kegiatan operator prov
                $data_log = new LogAktivitas();
                $data_log->log_username = Auth::user()->username;
                $data_log->log_ip = Generate::GetIpAddress();
                $data_log->log_jenis = 3;
                $data_log->log_useragent = Generate::GetUserAgent();
                $data_log->log_pesan = 'berhasil menambah kegiatan ['.$keg_id.'] '. trim($request->keg_nama) .' dengan SM ['. $request->keg_unitkerja .'] '. $unit_nama;
                $data_log->save();
                //batas catat aktivitas tambah kegiatan
            }
        }
        Session::flash('message', $pesan_error);
        Session::flash('message_type', $pesan_warna);
        return redirect()->route('kegiatan.list');
    }
    public function UpdateKegiatan(Request $request)
    {
        //dd($request->all());
        $count = Kegiatan::where('keg_id',$request->keg_id)->count();
        if ($count > 0)
        {
            $data = Kegiatan::where('keg_id',$request->keg_id)->first();
            $data->keg_nama = trim($request->keg_nama);
            $data->keg_unitkerja = $request->keg_unitkerja;
            $data->keg_jenis = $request->keg_jenis;
            $data->keg_start = $request->keg_start;
            $data->keg_end = $request->keg_end;
            $data->keg_target_satuan = $request->keg_satuan;
            $data->keg_total_target = $request->keg_total_target;
            $data->keg_spj = $request->keg_spj;
            $data->keg_diupdate_oleh = Auth::user()->username;
            $data->update();

            //target masing2 kabkota
            foreach ($request->keg_kabkota as $key => $v)
            {
                if ($v > 0)
                {
                    //ada isian targetnya
                    $target = $v;
                }
                else
                {
                    //target tetep di set nol
                    $target = 0;
                }
                $t_count = KegTarget::where([['keg_id',$request->keg_id],['keg_t_unitkerja',$key]])->count();
                if ($t_count > 0)
                {
                    //unitkerja sudah ada update aja
                    $dataTarget = KegTarget::where([['keg_id',$request->keg_id],['keg_t_unitkerja',$key]])->first();
                    $dataTarget->keg_t_target = $target;
                    $dataTarget->keg_t_diupdate_oleh = Auth::user()->username;
                    $dataTarget->update();
                }
                else
                {
                    //unitkerja belum ada target input
                    $dataTarget = new KegTarget();
                    $dataTarget->keg_id = $request->keg_id;
                    $dataTarget->keg_t_unitkerja = $key;
                    $dataTarget->keg_t_target = $target;
                    $dataTarget->keg_t_point_waktu = 0;
                    $dataTarget->keg_t_point_jumlah = 0;
                    $dataTarget->keg_t_point = 0;
                    $dataTarget->keg_t_dibuat_oleh = Auth::user()->username;
                    $dataTarget->keg_t_diupdate_oleh = Auth::user()->username;
                    $dataTarget->save();
                }


            }
            if ($request->keg_spj == 1)
            {
                //ada permintaan spj
                //data spj di insert
                //spj_kabkota

                foreach ($request->spj_kabkota as $key => $v)
                {
                    if ($v > 0)
                    {
                        //ada isian targetnya
                        $target = $v;
                    }
                    else
                    {
                        //target tetep di set nol
                        $target = 0;
                    }
                    $s_count = SpjTarget::where([['keg_id',$request->keg_id],['spj_t_unitkerja',$key]])->count();
                    if ($s_count > 0)
                    {
                        //spj target sudah ada hnay update saja
                        $dataSpj = SpjTarget::where([['keg_id',$request->keg_id],['spj_t_unitkerja',$key]])->first();
                        $dataSpj->spj_t_target = $target;
                        $dataSpj->spj_t_diupdate_oleh = Auth::user()->username;
                        $dataSpj->update();
                    }
                    else
                    {
                        $dataSpj = new SpjTarget();
                        $dataSpj->keg_id = $request->keg_id;
                        $dataSpj->spj_t_unitkerja = $key;
                        $dataSpj->spj_t_target = $target;
                        $dataSpj->spj_t_point_waktu = 0;
                        $dataSpj->spj_t_point_jumlah = 0;
                        $dataSpj->spj_t_point = 0;
                        $dataSpj->spj_t_dibuat_oleh = Auth::user()->username;
                        $dataSpj->spj_t_diupdate_oleh = Auth::user()->username;
                        $dataSpj->save();
                    }
                }
            }
            else
            {
                //hapus semua target SPJ
                $dataSpj = SpjTarget::where('keg_id',$request->keg_id)->delete();
            }
            $pesan_error='Kegiatan ini sudah di update';
            $pesan_warna='success';
        }
        else
        {
            //kegiatan ada di update
            $pesan_error='Nama kegiatan (ini) tidak ada';
            $pesan_warna='danger';
        }

        Session::flash('message', $pesan_error);
        Session::flash('message_type', $pesan_warna);
        return redirect()->route('kegiatan.list');

    }
    public function hapusKegiatan(Request $request)
    {
        $count = Kegiatan::where('keg_id','=',$request->keg_id)->count();
        $arr = array(
            'status'=>false,
            'hasil'=>'data kegiatan ini tidak tersedia'
        );
        if ($count>0)
        {
            if (Auth::user()->level > 4 or Auth::user()->level == 3)
            {
                //user admin atau operator provinsi
                $data = Kegiatan::where('keg_id',$request->keg_id)->first();
                if (Auth::user()->level > 4 or $data->Unitkerja->unit_parent == Auth::user()->kodeunit)
                {
                    //admin atau operator provinsi sesuai unitkodenya
                    $nama = $data->keg_nama;
                    $unit_kode = $data->keg_unitkerja;
                    $unit_nama = $data->Unitkerja->unit_nama;
                    $keg_spj = $data->keg_spj;
                    $data->delete();
                    $target = KegTarget::where('keg_id',$request->keg_id)->delete();
                    $realisasi = KegRealisasi::where('keg_id',$request->keg_id)->delete();
                    if ($keg_spj==1)
                    {
                        $spj = SpjTarget::where('keg_id',$request->keg_id)->delete();
                        $spjrealisasi = SpjRealisasi::where('keg_id',$request->keg_id)->delete();
                    }
                    //hapus notifikasi juga
                    Notifikasi::where('keg_id',$request->keg_id)->delete();
                    //batas hapus
                    $arr = array(
                        'status'=>true,
                        'hasil'=>'Data kegiatan '.$nama.' dari '.$unit_nama.' berhasil dihapus beserta target dan realisasinya'
                    );
                    if (env('APP_AKTIVITAS_MODE') == true)
                    {
                        //catat aktivitas hapus kegiatan operator prov
                        $data_log = new LogAktivitas();
                        $data_log->log_username = Auth::user()->username;
                        $data_log->log_ip = Generate::GetIpAddress();
                        $data_log->log_jenis = 3;
                        $data_log->log_useragent = Generate::GetUserAgent();
                        $data_log->log_pesan = 'berhasil menghapus kegiatan ['.$request->keg_id.'] '. trim($nama) .' dengan SM ['. $unit_kode .'] '. $unit_nama;
                        $data_log->save();
                        //batas catat aktivitas hapus kegiatan
                    }
                }
                else
                {
                    //error selain unitkode yg beda
                    $arr = array(
                        'status'=>false,
                        'hasil'=>'Operator Provinsi ('.Auth::user()->nama.') tidak mempunyai hak untuk menghapus kegiatan '.$data->keg_nama
                    );
                }
            }


        }
        return Response()->json($arr);
    }
    public function DetilKegiatan($kegId)
    {
        $count = Kegiatan::where('keg_id',$kegId)->count();
        if ($count > 0)
        {
            //kegiatan ada
            $status = true;
            $dataKegiatan = Kegiatan::where('keg_id',$kegId)->first();
            //$dataTarget = KegTarget::where([['keg_id',$kegId],['keg_t_target','>',0]])->get();
            //$dataRealisasi = KegRealisasi::where('keg_id',$kegId)->get();
        }
        else
        {
            //kegiatan tidak ada
            //tampilan error 404
            $status = false;
            $dataKegiatan='';
        }
        //dd($dataKegiatan->Target);
        return view('kegiatan.detil.index',['status'=>$status,'dataKegiatan'=>$dataKegiatan]);
    }
    public function editKegiatan($kegId)
    {
        if (Auth::user()->level > 2)
        {
            //selain operator provinsi dan admin
            if (Auth::user()->level == 4 and Auth::user()->NamaWilayah->bps_jenis == 2)
            {
                //cek admin kabkota
                //peringatan tidak bisa mengakses halaman ini
                return view('kegiatan.warning',['keg_id'=>$kegId]);
            }
            else
            {
                $dataKegiatan = Kegiatan::where('keg_id',$kegId)->first();
                if (Auth::user()->level == 3)
                {
                    //operator provinsi list unitProv hanya di bidangnya
                    if ($dataKegiatan->Unitkerja->unit_parent != Auth::user()->kodeunit)
                    {
                         //peringatan tidak bisa mengedit kegiatan ini
                         return view('kegiatan.warning',['keg_id'=>$kegId]);
                    }
                    $unitProv = UnitKerja::where([['unit_jenis','=','1'],['unit_eselon','=','4'],['unit_parent','=',Auth::user()->kodeunit]])->get();
                }
                else
                {
                    //list semua unitProv eselon 4 di provinsi
                    $unitProv = UnitKerja::where([['unit_jenis','=','1'],['unit_eselon','=','4']])->get();
                }
                //dd($dataKegiatan);
                //$unitTarget = UnitKerja::where([['unit_jenis','=','2'],['unit_eselon','=','3']])->get();
                $kegJenis = KegJenis::get();
                return view('kegiatan.edit',[
                    'unitProv'=>$unitProv,
                    'kegJenis'=>$kegJenis,
                    'dataKegiatan'=>$dataKegiatan
                ]);
            }

        }
        else
        {
            //operator kabkota dan pemantau tidak bisa mengakses ini
            //beri info warning
            return view('kegiatan.warning',['keg_id'=>$kegId]);
        }
    }
    public function cariKegiatan($kegId)
    {
        $count = Kegiatan::where('keg_id',$kegId)->count();
        $arr = array(
            'status'=>false,
            'hasil'=>'Data kegiatan ini tidak tersedia'
        );
        if ($count > 0)
        {
            //unitkerja
            $data = Kegiatan::where('keg_id',$kegId)->first();
            if ($data->keg_spj==1)
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
                'keg_nama'=>$data->keg_nama,
                'keg_unitkerja'=>$data->keg_unitkerja,
                'keg_unitkerja_nama'=>$data->Unitkerja->unit_nama,
                'keg_target'=>$data->keg_total_target,
                'keg_satuan'=>$data->keg_target_satuan,
                'keg_start'=>$data->keg_start,
                'keg_start_nama'=>Tanggal::HariPanjang($data->keg_start),
                'keg_end'=>$data->keg_end,
                'keg_end_nama'=>Tanggal::HariPanjang($data->keg_end),
                'keg_jenis'=>$data->keg_jenis,
                'keg_jenis_nama'=>$data->JenisKeg->jkeg_nama,
                'keg_spj'=>$data->keg_spj,
                'keg_spj_nama'=>$spj,
                'keg_info'=>$data->keg_info,
                'keg_dibuat_oleh'=>$data->keg_dibuat_oleh,
                'keg_diupdate_oleh'=>$data->keg_diupdate_oleh,
                'created_at'=>$data->created_at,
                'updated_at'=>$data->updated_at
            );
        }
        return Response()->json($arr);
    }
    public function CariRealisasi($kegrid)
    {
        $count = KegRealisasi::where('keg_r_id',$kegrid)->count();
        $arr = array(
            'status'=>false,
            'hasil'=>'Realisasi kegiatan ini tidak tersedia'
        );
        if ($count > 0)
        {
            //unitkerja
            $data = KegRealisasi::where('keg_r_id',$kegrid)->first();
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
                'keg_r_id'=>$data->keg_r_id,
                'keg_r_unitkerja'=>$data->keg_r_unitkerja,
                'keg_r_unitkerja_nama'=>$data->Unitkerja->unit_nama,
                'keg_r_jumlah'=>$data->keg_r_jumlah,
                'keg_r_tgl'=>$data->keg_r_tgl,
                'keg_r_tgl_nama'=>Tanggal::HariPanjang($data->keg_r_tgl),
                'keg_r_jenis'=>$data->keg_r_jenis,
                'keg_r_jenis_nama'=>$data->JenisRealisasi->rkeg_nama,
                'keg_r_link'=>$data->keg_r_link,
                'keg_r_ket'=>$data->keg_r_ket,
                'keg_r_dibuat_oleh'=>$data->keg_r_dibuat_oleh,
                'keg_r_diupdate_oleh'=>$data->keg_r_diupdate_oleh,
                'created_at'=>$data->created_at,
                'updated_at'=>$data->updated_at
            );
        }
        return Response()->json($arr);
    }
    public function kirimKegiatan(Request $request)
    {
        //dd($request->all());
        $count = Kegiatan::where('keg_id',$request->keg_id)->count();
        if ($count > 0)
        {
            //kegiatan ini ada
            $data = new KegRealisasi();
            $data->keg_id = $request->keg_id;
            $data->keg_r_unitkerja = $request->keg_r_unitkerja;
            $data->keg_r_tgl = $request->keg_r_tgl;
            $data->keg_r_jumlah = $request->keg_r_jumlah;
            $data->keg_r_jenis = 1;
            $data->keg_r_link = $request->keg_r_link;
            $data->keg_r_ket = $request->keg_r_ket;
            $data->keg_r_dibuat_oleh = Auth::user()->username;
            $data->keg_r_diupdate_oleh = Auth::user()->username;
            $data->save();

            $data_keg = Kegiatan::where('keg_id',$request->keg_id)->first();
            $nofif_isi = '['.$request->keg_id.'] ada pengiriman dari '.Auth::user()->username .' sebanyak '. $request->keg_r_jumlah .' '.$data_keg->keg_target_satuan.' tanggal '.Tanggal::HariPanjang($request->keg_r_tgl).' dengan keterangan '.$request->keg_r_ket;
            $data_user = User::where([['kodeunit',$data_keg->Unitkerja->unit_parent],['level','>','1']])->get();
            foreach ($data_user as $item)
            {
                $notif = new Notifikasi();
                $notif->keg_id = $request->keg_id;
                $notif->notif_dari = Auth::user()->username;
                $notif->notif_untuk = $item->username;
                $notif->notif_isi = $nofif_isi;
                $notif->notif_jenis = '1';
                $notif->save();
            }

            if (env('APP_AKTIVITAS_MODE') == true)
            {
                //catat pengiriman oleh operator kabkota
                $data_log = new LogAktivitas();
                $data_log->log_username = Auth::user()->username;
                $data_log->log_ip = Generate::GetIpAddress();
                $data_log->log_jenis = 4;
                $data_log->log_useragent = Generate::GetUserAgent();
                $data_log->log_pesan = 'berhasil menambah pengiriman untuk kegiatan ['.$request->keg_id.'] '. trim($data_keg->keg_nama) .' tanggal '.Tanggal::HariPanjang($request->keg_r_tgl).' sebanyak '. $request->keg_r_jumlah .' '.$data_keg->keg_target_satuan;
                $data_log->save();
                //batas pengiriman oleh operator kabkota
            }
            $pesan_error="Pengiriman oleh ". $data->Unitkerja->unit_nama.' sudah disimpan';
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
    public function UpdatePengiriman(Request $request)
    {
        //dd($request->all());
        $count = KegRealisasi::where('keg_r_id',$request->keg_r_id)->count();
        if ($count > 0)
        {
            //realisasi kegiatan ini ada
            $data = KegRealisasi::where('keg_r_id',$request->keg_r_id)->first();
            $data->keg_r_tgl = $request->keg_r_tgl;
            $data->keg_r_jumlah = $request->keg_r_jumlah;
            $data->keg_r_link = $request->keg_r_link;
            $data->keg_r_ket = $request->keg_r_ket;
            $data->keg_r_diupdate_oleh = Auth::user()->username;
            $data->update();

            $pesan_error="Konfirmasi pengiriman oleh ". $data->Unitkerja->unit_nama.' sudah diupdate';
            $pesan_warna="success";
        }
        else
        {
            //realiasi kegiatan ini tidak ada
            $pesan_error="Realiasi kegiatan ini tidak ada";
            $pesan_warna="danger";
        }
        Session::flash('message', $pesan_error);
        Session::flash('message_type', $pesan_warna);
        return redirect()->route('kegiatan.detil',$request->keg_id);
    }
    public function HapusPengiriman(Request $request)
    {
        $count = KegRealisasi::where('keg_r_id','=',$request->id)->count();
        $arr = array(
            'status'=>false,
            'hasil'=>'data realiasi pengiriman ini tidak tersedia'
        );
        if ($count>0)
        {
            $data = KegRealisasi::where('keg_r_id','=',$request->id)->first();
            $data_keg = Kegiatan::where('keg_id',$data->keg_id)->first();
            $keg_r_jumlah = $data->keg_r_jumlah;
            $nama = $data->Unitkerja->unit_nama;
            $tgl = Tanggal::Panjang($data->keg_r_tgl);
            $data->delete();
            $arr = array(
                'status'=>true,
                'hasil'=>'Data pengiriman oleh '.$nama.' tanggal '.$tgl.' berhasil dihapus'
            );
            if (env('APP_AKTIVITAS_MODE') == true)
            {
                //catat hapus pengiriman
                $data_log = new LogAktivitas();
                $data_log->log_username = Auth::user()->username;
                $data_log->log_ip = Generate::GetIpAddress();
                $data_log->log_jenis = 4;
                $data_log->log_useragent = Generate::GetUserAgent();
                $data_log->log_pesan = 'berhasil menghapus pengiriman untuk kegiatan ['.$data_keg->keg_id.'] '. trim($data_keg->keg_nama) .' tanggal '.$tgl.' sebanyak '. $keg_r_jumlah .' '.$data_keg->keg_target_satuan;
                $data_log->save();
                //batas hapus pengiriman
            }
        }
        return Response()->json($arr);
    }
    public function terimaKegiatan(Request $request)
    {
        //dd($request->all());
        $count = Kegiatan::where('keg_id',$request->keg_id)->count();
        if ($count > 0)
        {
            //kegiatan ini ada
            //buat realisasi
            //dan nilai di tabel keg_target
            $data = new KegRealisasi();
            $data->keg_id = $request->keg_id;
            $data->keg_r_unitkerja = $request->keg_r_unitkerja;
            $data->keg_r_tgl = $request->keg_r_tgl;
            $data->keg_r_jumlah = $request->keg_r_jumlah;
            $data->keg_r_jenis = 2;
            $data->keg_r_ket = Auth::user()->username;
            $data->keg_r_dibuat_oleh = Auth::user()->username;
            $data->keg_r_diupdate_oleh = Auth::user()->username;
            $data->save();
            $waktu_simpan_penerimaan = $data->created_at;
            $nilai = Generate::NilaiKegRealiasi($request->keg_id,$request->keg_r_unitkerja);
            //update nilai KegTarget
            $dataNilai = KegTarget::where([
				['keg_id',$request->keg_id],
				['keg_t_unitkerja',$request->keg_r_unitkerja],
            ])->first();
            $dataNilai->keg_t_point_waktu = $nilai['nilai_waktu'];
            $dataNilai->keg_t_point_jumlah = $nilai['nilai_volume'];
            $dataNilai->keg_t_point = $nilai['nilai_total'];
            $dataNilai->keg_t_diupdate_oleh = Auth::user()->username;
            $dataNilai->update();
            $target_kabkota = $dataNilai->keg_t_target;
            $data_keg = Kegiatan::where('keg_id',$request->keg_id)->first();
            $nofif_isi = '['.$request->keg_id.'] ada penerimaan oleh '.Auth::user()->username .' tanggal '.Tanggal::HariPanjang($request->keg_r_tgl).' sebanyak '. $request->keg_r_jumlah .' '.$data_keg->keg_target_satuan;
            $data_user = User::where([['kodeunit',$request->keg_r_unitkerja],['level','>','1']])->get();
            foreach ($data_user as $item)
            {
                $notif = new Notifikasi();
                $notif->keg_id = $request->keg_id;
                $notif->notif_dari = Auth::user()->username;
                $notif->notif_untuk = $item->username;
                $notif->notif_isi = $nofif_isi;
                $notif->notif_jenis = '2';
                $notif->save();
                //testing notif ke email
                if (env('APP_MAIL_MODE') == true)
                {
                    /*
                    <p>Detil Kegiatan yang diterima :<br/>
                    <b>ID :</b>&nbsp;{{ $objEmail->keg_id}}<br/>
                    <b>Judul :</b>&nbsp;{{ $objEmail->keg_nama }}<br/>
                    <b>Jenis :</b>&nbsp;{{ $objEmail->keg_jenis }}<br/>
                    <b>Target diterima :</b>&nbsp;{{ $objEmail->keg_diterima }} &nbsp;{{ $objEmail->keg_satuan }}<br/>
                    <b>Tgl diterima :</b>&nbsp;{{ $objEmail->keg_tgl_diterima }}<br/>
                    <b>Subject Matter :</b>&nbsp;{{ $objEmail->keg_sm }}<br/>
                    </p>
                    </div>
                    <div>
                        <p>Info Tambahan :<br/>
                        <b>Target Kabkota :</b>&nbsp;{{ $objEmail->keg_target_kabkota}} &nbsp;{{ $objEmail->keg_satuan }}<br/>
                        <b>Tanggal dibuat :</b>&nbsp;{{ $objEmail->keg_tgl_dibuat }}<br/>
                        <b>Operator Penerima :</b>&nbsp;{{ $objEmail->keg_operator }}<br/>
                    </p>
                    </div>
                    <br/>
                    */
                    //$data_keg = Kegiatan::where('keg_id',$keg_id)->first();
                    $objEmail = new \stdClass();
                    $objEmail->keg_id = $data_keg->keg_id;
                    $objEmail->keg_nama = $data_keg->keg_nama;
                    $objEmail->keg_jenis = $data_keg->JenisKeg->jkeg_nama;
                    $objEmail->keg_diterima = $request->keg_r_jumlah;
                    $objEmail->keg_satuan = $data_keg->keg_target_satuan;
                    $objEmail->keg_tgl_diterima = Tanggal::HariPanjang($request->keg_r_tgl);
                    $objEmail->keg_sm = $data_keg->Unitkerja->unit_nama;
                    $objEmail->keg_target_kabkota = $target_kabkota;
                    $objEmail->keg_tgl_dibuat = Tanggal::LengkapHariPanjang($waktu_simpan_penerimaan);
                    $objEmail->keg_operator = Auth::user()->username;
                    //coba email
                    $dataemail = $item->email;
                    //$dataemail = "pdyatmika@gmail.com";
                    Mail::to($dataemail)->send(new MailPenerimaan($objEmail));

                }
                //batas testing
            }
            if (env('APP_AKTIVITAS_MODE') == true)
            {
                //catat penerimaan oleh operator provinsi
                $data_log = new LogAktivitas();
                $data_log->log_username = Auth::user()->username;
                $data_log->log_ip = Generate::GetIpAddress();
                $data_log->log_jenis = 5;
                $data_log->log_useragent = Generate::GetUserAgent();
                $data_log->log_pesan = 'berhasil menambah penerimaan untuk kegiatan ['.$request->keg_id.'] '. trim($data_keg->keg_nama) .' tanggal '.Tanggal::HariPanjang($request->keg_r_tgl).' sebanyak '. $request->keg_r_jumlah .' '.$data_keg->keg_target_satuan;
                $data_log->save();
                //batas penerimaan oleh operator provinsi
            }
            $pesan_error="Konfirmasi penerimaan dari ". $data->Unitkerja->unit_nama.' sudah disimpan';
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
    public function HapusPenerimaan(Request $request)
    {
        //dd($request->all());
        $count = KegRealisasi::where('keg_r_id','=',$request->id)->count();
        $arr = array(
            'status'=>false,
            'hasil'=>'data realiasi konfirmasi penerimaan ini tidak tersedia'
        );
        if ($count>0)
        {
            $data = KegRealisasi::where('keg_r_id','=',$request->id)->first();
            $nama = $data->Unitkerja->unit_nama;
            $keg_id = $data->keg_id;
            $keg_r_unitkerja = $data->keg_r_unitkerja;
            $keg_r_jumlah = $data->keg_r_jumlah;
            $tgl = Tanggal::Panjang($data->keg_r_tgl);
            $data->delete();

            $nilai = Generate::NilaiKegRealiasi($keg_id,$keg_r_unitkerja);
            //update nilai KegTarget
            $dataNilai = KegTarget::where([
				['keg_id',$keg_id],
				['keg_t_unitkerja',$keg_r_unitkerja],
            ])->first();
            $dataNilai->keg_t_point_waktu = $nilai['nilai_waktu'];
            $dataNilai->keg_t_point_jumlah = $nilai['nilai_volume'];
            $dataNilai->keg_t_point = $nilai['nilai_total'];
            $dataNilai->keg_t_diupdate_oleh = Auth::user()->username;
            $dataNilai->update();
            $arr = array(
                'status'=>true,
                'hasil'=>'Data konfirmasi penerimaan oleh '.$nama.' tanggal '.$tgl.' berhasil dihapus'
            );
            if (env('APP_AKTIVITAS_MODE') == true)
            {
                $data_keg = Kegiatan::where('keg_id',$keg_id)->first();
                //catat hapus penerimaan oleh operator provinsi
                $data_log = new LogAktivitas();
                $data_log->log_username = Auth::user()->username;
                $data_log->log_ip = Generate::GetIpAddress();
                $data_log->log_jenis = 5;
                $data_log->log_useragent = Generate::GetUserAgent();
                $data_log->log_pesan = 'berhasil menghapus penerimaan untuk kegiatan ['.$keg_id.'] '. trim($data_keg->keg_nama) .' tanggal '.$tgl.' sebanyak '. $keg_r_jumlah .' '.$data_keg->keg_target_satuan;
                $data_log->save();
                //batas hapus penerimaan oleh operator provinsi
            }
        }
        return Response()->json($arr);
    }
    public function UpdatePenerimaan(Request $request)
    {
        //dd($request->all());
        $count = KegRealisasi::where('keg_r_id',$request->keg_r_id)->count();
        if ($count > 0)
        {
            //realisasi kegiatan ini ada
            $data = KegRealisasi::where('keg_r_id',$request->keg_r_id)->first();
            $data->keg_r_tgl = $request->keg_r_tgl;
            $data->keg_r_jumlah = $request->keg_r_jumlah;
            $data->keg_r_ket = Auth::user()->username;
            $data->keg_r_diupdate_oleh = Auth::user()->username;
            $data->update();

            $nilai = Generate::NilaiKegRealiasi($data->keg_id,$data->keg_r_unitkerja);
            //update nilai KegTarget
            $dataNilai = KegTarget::where([
				['keg_id',$data->keg_id],
				['keg_t_unitkerja',$data->keg_r_unitkerja],
            ])->first();
            $dataNilai->keg_t_point_waktu = $nilai['nilai_waktu'];
            $dataNilai->keg_t_point_jumlah = $nilai['nilai_volume'];
            $dataNilai->keg_t_point = $nilai['nilai_total'];
            $dataNilai->keg_t_diupdate_oleh = Auth::user()->username;
            $dataNilai->update();
            $pesan_error="Konfirmasi penerimaan dari ". $data->Unitkerja->unit_nama.' sudah diupdate';
            $pesan_warna="success";
        }
        else
        {
            //realiasi kegiatan ini tidak ada
            $pesan_error="Realisasi konfirmasi penerimaan tidak ada";
            $pesan_warna="danger";
        }
        Session::flash('message', $pesan_error);
        Session::flash('message_type', $pesan_warna);
        return redirect()->route('kegiatan.detil',$request->keg_id);
    }
    public function UpdateInfo(Request $request)
    {
        //dd($request->all());
        $count = Kegiatan::where('keg_id',$request->keg_id)->count();
        if ($count > 0)
        {
            $data = Kegiatan::where('keg_id',$request->keg_id)->first();
            $data->keg_info = trim($request->keg_info);
            $data->keg_diupdate_oleh = Auth::user()->username;
            $data->update();

            $pesan_error='Info lanjutan kegiatan ('.$data->keg_nama.') pada '.$data->Unitkerja->unit_nama.' sudah di update';
            $pesan_warna='success';
        }
        else
        {
            //kegiatan ada di update
            $pesan_error='Nama kegiatan (ini) tidak ada';
            $pesan_warna='danger';
        }

        Session::flash('message', $pesan_error);
        Session::flash('message_type', $pesan_warna);
        return redirect()->route('kegiatan.detil',$request->keg_id);
    }
}

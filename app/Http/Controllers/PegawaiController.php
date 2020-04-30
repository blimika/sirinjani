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
use App\Helpers\Generate;

class PegawaiController extends Controller
{
    //
    public function index()
    {
        $data_wilayah = KodeWilayah::all();
        $dataPegawai = User::when(request('wilayah'),function ($query){
            return $query->where('kodebps','=',request('wilayah'));
        })->get();
        $dataLevel = KodeLevel::where('level_id','<','9')->get();
        return view('pegawai.index',['dataLevel'=>$dataLevel,'dataWilayah'=>$data_wilayah,'dataPegawai'=>$dataPegawai,'wilayah'=>request('wilayah')]);
    }
    public function cekCommunity(Request $request)
    {
       $arr = array(
           'error'=>true,
           'pesan'=>'Username tidak valid'
       );
       $h = new CommunityBPS($request->peg_username,$request->peg_password);
       if ($h->errorLogin==false) {
        $arr = array(
            'error'=>false,
            'pesan'=>'Login berhasil'
        );
       }
       return Response()->json($arr);
    }
    public function syncData(Request $request)
    {
        //dd($request->all());
        //cek dulu username berhasil tidak login
        //cek apakah provinsi atau kabkota
        $h = new CommunityBPS($request->peg_username,$request->peg_password);
        if ($h->errorLogin==false)
        {
            //cek dulu apakah tipe wilayah 
            $wilayah = KodeWilayah::where('bps_kode','=',$request->wilayah)->first();
            if ($wilayah->bps_jenis==1)
            {
                //pegawai provinsi
                $hasil = $h->get_list_pegawai_provinsi($request->wilayah);
                $tot=0;
                if ($hasil) {
                    //$banyak = count($hasil);
                    for ($i=0;$i<count($hasil);$i++)
                    {
                        if ($i==0) {
                            //pasti kepala
                            if ($hasil[0]!=false) {
                                //cek kepala bps ada atau tidak
                                $count_peg = User::where('nipbps','=',$hasil[0]['nipbps'])->count();
                                $kode_unit = UnitKerja::where('unit_nama','=',$hasil[$i]['satuankerja'])->first();
                                if ($kode_unit)
                                    {
                                        if ($kode_unit->unit_eselon < 4)
                                        {    
                                            $unit_kode = $kode_unit->unit_kode;
                                        }
                                        else 
                                        {
                                            $unit_kode = $kode_unit->unit_parent;
                                        }
                                    }
                                    else 
                                    { 
                                        $unit_kode = NULL;
                                    }
                                if ($count_peg>0) {
                                    //jika sudah ada update isiannya nama, satuan, urlfoto
                                    $data = User::where('nipbps','=',$hasil[$i]['nipbps'])->first();
                                    $data->nama = $hasil[$i]['nama'];
                                    $data->satuankerja = $hasil[$i]['satuankerja'];
                                    $data->urlfoto = $hasil[$i]['urlfoto'];
                                    $data->level = '1';
                                    $data->kodeunit = $unit_kode;
                                    $data->kodebps = $request->wilayah;
                                    $data->update();
                                    $tot++;
                                }
                                else {
                                    //belum ada
                                    /*
                                    'nama'=>$nama,
                                    'nipbps'=>$nipbps,
                                    'nippanjang'=>$nippanjang,
                                    'email'=>$email,
                                    'username'=>$username,
                                    'jabatan'=>$jabatan,
                                    'satuankerja'=>$satuankerja,
                                    'alamatkantor'=>$alamatkantor,
                                    'urlfoto'=>$urlfoto
                                    */
                                    $data = new User();
                                    $data->nama = $hasil[0]['nama'];
                                    $data->nipbps = $hasil[0]['nipbps'];
                                    $data->nipbaru = $hasil[0]['nippanjang'];
                                    $data->email = $hasil[0]['email'];
                                    $data->username = $hasil[0]['username'];
                                    $data->jabatan = $hasil[0]['jabatan'];
                                    $data->satuankerja = $hasil[0]['satuankerja'];
                                    $data->urlfoto = $hasil[0]['urlfoto'];
                                    $data->jk = substr($hasil[0]['nippanjang'],-4,1);
                                    $data->level = '1';
                                    $data->kodeunit = $unit_kode;
                                    $data->kodebps = $request->wilayah;
                                    $data->password = bcrypt('null');
                                    $data->save();
                                    $tot++;
                                }                 
                            }
                        }
                        else {
                            //langsung simpan
                            for ($j=0;$j<count($hasil[$i]);$j++)
                                {
                                    $count_peg = User::where('nipbps','=',$hasil[$i][$j]['nipbps'])->count();
                                    $kode_unit = UnitKerja::where('unit_nama','=',$hasil[$i][$j]['satuankerja'])->first();
                                    if ($kode_unit)
                                    {
                                        if ($kode_unit->unit_eselon < 4)
                                        {    
                                            $unit_kode = $kode_unit->unit_kode;
                                        }
                                        else 
                                        {
                                            $unit_kode = $kode_unit->unit_parent;
                                        }
                                    }
                                    else 
                                    { 
                                        $unit_kode = NULL;
                                    }
                                    if ($count_peg>0) {
                                        //jika sudah ada update isiannya nama, satuan, urlfoto
                                        $data = User::where('nipbps','=',$hasil[$i][$j]['nipbps'])->first();
                                        $data->nama = $hasil[$i][$j]['nama'];
                                        $data->satuankerja = $hasil[$i][$j]['satuankerja'];
                                        $data->urlfoto = $hasil[$i][$j]['urlfoto'];
                                        $data->kodeunit = $unit_kode;
                                        $data->kodebps = $request->wilayah;
                                        $data->update();
                                        $tot++;
                                    }
                                    else {
                                        //belum ada
                                        /*
                                        'nama'=>$nama,
                                        'nipbps'=>$nipbps,
                                        'nippanjang'=>$nippanjang,
                                        'email'=>$email,
                                        'username'=>$username,
                                        'jabatan'=>$jabatan,
                                        'satuankerja'=>$satuankerja,
                                        'alamatkantor'=>$alamatkantor,
                                        'urlfoto'=>$urlfoto
                                        */
                                        $data = new User();
                                        $data->nama = $hasil[$i][$j]['nama'];
                                        $data->nipbps = $hasil[$i][$j]['nipbps'];
                                        $data->nipbaru = $hasil[$i][$j]['nippanjang'];
                                        $data->email = $hasil[$i][$j]['email'];
                                        $data->username = $hasil[$i][$j]['username'];
                                        $data->jabatan = $hasil[$i][$j]['jabatan'];
                                        $data->satuankerja = $hasil[$i][$j]['satuankerja'];
                                        $data->urlfoto = $hasil[$i][$j]['urlfoto'];
                                        $data->jk = substr($hasil[$i][$j]['nippanjang'],-4,1);
                                        $data->level = '1';
                                        $data->kodeunit = $unit_kode;
                                        $data->kodebps = $request->wilayah;
                                        $data->password = bcrypt('null');
                                        $data->save();
                                        $tot++;
                                    }   
                                }
                        }
                    }
                    $pesan_error='Data pegawai '.$wilayah->bps_nama.' sebanyak '.$tot.' sudah disync';
                    $pesan_warna='success';
                }
                else {
                    $pesan_error='Data tidak tersedia';
                    $pesan_warna='danger';
                }
                //batas provinsi
            }
            else 
            {
                //sync pegawai kabkota
                $hasil = $h->get_list_pegawai_kabkot($request->wilayah);
                $tot=0;
                $unit_kode = $request->wilayah.'0';
                //dd($hasil);
                if ($hasil) 
                {
                    for ($i=0;$i<count($hasil);$i++)
                    {
                        $count_peg = User::where('nipbps','=',$hasil[$i]['nipbps'])->count();
                        if ($hasil[$i]['satuankerja']=="BPS Kabupaten/Kota")
                        {
                            $jabatan = 'Kepala';
                            $satuankerja = $hasil[$i]['alamatkantor'];
                        }
                        elseif ($hasil[$i]['satuankerja']=="KSK")
                        {
                            $jabatan = 'KSK';
                            $satuankerja = 'KSK';
                        }
                        else 
                        {
                            $jabatan = $hasil[$i]['jabatan'];
                            $satuankerja = $hasil[$i]['satuankerja'];
                        }

                        if ($count_peg>0) {
                            //jika sudah ada update isiannya nama, satuan, urlfoto
                            $data = User::where('nipbps','=',$hasil[$i]['nipbps'])->first();
                            $data->nama = $hasil[$i]['nama'];
                            $data->satuankerja = $satuankerja;
                            $data->urlfoto = $hasil[$i]['urlfoto'];
                            $data->jabatan = $jabatan;
                            $data->kodeunit = $unit_kode;
                            $data->kodebps = $request->wilayah;
                            $data->update();
                            $tot++;
                        }
                        else {
                            //belum ada
                            /*
                            'nama'=>$nama,
                            'nipbps'=>$nipbps,
                            'nippanjang'=>$nippanjang,
                            'email'=>$email,
                            'username'=>$username,
                            'jabatan'=>$jabatan,
                            'satuankerja'=>$satuankerja,
                            'alamatkantor'=>$alamatkantor,
                            'urlfoto'=>$urlfoto
                            */
                            $data = new User();
                            $data->nama = $hasil[$i]['nama'];
                            $data->nipbps = $hasil[$i]['nipbps'];
                            $data->nipbaru = $hasil[$i]['nippanjang'];
                            $data->email = $hasil[$i]['email'];
                            $data->username = $hasil[$i]['username'];
                            $data->jabatan = $jabatan;
                            $data->satuankerja = $satuankerja;
                            $data->urlfoto = $hasil[$i]['urlfoto'];
                            $data->jk = substr($hasil[$i]['nippanjang'],-4,1);
                            $data->level = '1';
                            $data->kodeunit = $unit_kode;
                            $data->kodebps = $request->wilayah;
                            $data->password = bcrypt('null');
                            $data->save();
                            $tot++;
                        }  
                    }
                    $pesan_error='BERHASIL: Data pegawai '.$wilayah->bps_nama.' sebanyak '.$tot.' pegawai sudah disinkronisasi';
                    $pesan_warna = 'success';
                }
                else {
                    $pesan_error='Data tidak tersedia';
                    $pesan_warna = 'danger';
                }
                //batas sync peg kabkota
            }
            //batas true berhasil login
        }
        else
        {
            $pesan_error="ERROR : Username/Password community BPS tidak benar!!";
            $pesan_warna='danger';
        }
        Session::flash('message', $pesan_error);
        Session::flash('message_type', $pesan_warna);
        return redirect()->route('pegawai.list');
    }
    public function CariPegawai($nipbps)
    {
        $count = User::where('nipbps','=',$nipbps)->count();
        $arr = array(
            'status'=>false,
            'hasil'=>'Data pegawai tidak tersedia'
        );
        if ($count > 0) 
        {
            //ada nip pegawai ini
            $data = User::where('nipbps','=',$nipbps)->first();
            if ($data->lastlogin != "")
            {
                $lastlog_nama = Carbon::parse($data->lastlogin)->isoFormat('dddd, D MMMM Y H:mm');
            }
            else
            {
                $lastlog_nama = 'belum pernah login';
            }
            if ($data->lastip != "")
            {
                $lastip = $data->lastip;
            }
            else
            {
                $lastip = 'belum pernah login';
            }
            $arr = array(
                'status'=>true,
                'peg_id'=>$data->id,
                'nama'=>$data->nama,
                'nipbps'=>$data->nipbps,
                'nipbaru'=>$data->nipbaru,
                'nipbarupecah'=>Generate::PecahNip($data->nipbaru),
                'email'=>$data->email,
                'username'=>$data->username,
                'kodeunit'=>$data->kodeunit,
                'kodebps'=>$data->kodebps,
                'satuankerja'=>$data->satuankerja,
                'urlfoto'=>$data->urlfoto,
                'jk'=>$data->jk,
                'nohp'=>$data->nohp,
                'aktif'=>$data->aktif,
                'level'=>$data->level,
                'level_nama'=>$data->Level->level_nama,
                'isLokal'=>$data->isLokal,
                'lastip'=>$lastip,
                'lastlogin'=>$data->lastlogin,
                'lastlogin_nama'=>$lastlog_nama,
                'namaunit'=>$data->Unitkerja->unit_nama,
                'tgl_dibuat'=>$data->created_at
            );
        }
        return Response()->json($arr);
    }
    public function FlagPegawai(Request $request)
    {
        $count = User::where('id','=',$request->id)->count();
        $arr = array(
            'status'=>false,
            'hasil'=>'Data pegawai tidak tersedia'
        );
        if ($count>0)
        {
            $data=User::where('id','=',$request->id)->first();
            if ($request->flag==1)
            {
                $aktif = 0;
            }
            else 
            {
                $aktif = 1;
            }
            $data->aktif = $aktif;
            $data->update();
            $arr = array(
                'status'=>true,
                'hasil'=>'Flag Pegawai sudah diubah'
            );
        }
        return Response()->json($arr);
    }
    public function UpdatePegawai(Request $request)
    {
        //dd($request->all());
        $count = User::where('id','=',$request->peg_id)->count();
        if ($count > 0) 
        {
            //pegawai ada
            $data = User::where('id','=',$request->peg_id)->first();
            $data->level = $request->peg_level;
            $data->nohp = $request->peg_nohp;
            $data->update();
            $pesan_error='BERHASIL : Data Pegawai an. '.$request->peg_nama .' berhasil diupdate';
            $pesan_warna='success';
        }
        else
        {
            $pesan_error="ERROR : NIPBPS Pegawai tidak tersedia!!";
            $pesan_warna='danger';
        }
        Session::flash('message', $pesan_error);
        Session::flash('message_type', $pesan_warna);
        return redirect()->route('pegawai.list');
    }
    public function UpdateLokal(Request $request)
    {
        dd($request->all());
    }
}

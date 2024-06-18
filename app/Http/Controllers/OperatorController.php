<?php

namespace App\Http\Controllers;

use App\HakAkses;
use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\KodeWilayah;
use App\KodeLevel;
use App\UnitKerja;
use Excel;
use App\Helpers\Generate;

class OperatorController extends Controller
{
    //
    public function list()
    {
        $data_wilayah = KodeWilayah::all();
        //dd($data_wilayah);
        $dataFungsi = Unitkerja::where([['unit_jenis','=','1'],['unit_eselon','<','4']])->get();
        //level
        // 1=pemantau, operator kab, 3=admin kabb, 4=operator prov, 5=adminprov
        if (Auth::user()->role > 5)
        {
            //superadmin
            $dataOperator = User::when(request('wilayah'),function ($query){
                return $query->where('kodebps','=',request('wilayah'));
            })->get();
            $dataLevel = KodeLevel::get();
        }
        else
        {
            //selain superadmin
            $dataOperator = User::where('kodebps','=',Auth::user()->kodebps)->get();
            $dataLevel = KodeLevel::where('level_id','<','9')->whereIn('level_jenis', array(Auth::user()->NamaWilayah->bps_jenis, 3))->get();

        }
        //dd($dataLevel);
        //$dataUnitkerja = UnitKerja::where([['unit_eselon','=','3'],['unit_jenis','=','1']])->get();

        return view('operator.index',['dataFungsi'=>$dataFungsi,'dataLevel'=>$dataLevel,'dataWilayah'=>$data_wilayah,'dataOperator'=>$dataOperator,'wilayah'=>request('wilayah')]);
    }
    public function PerbaikiRole()
    {
        // 1=pemantau, 2=operator kab, 3=oper prov, 4=admin kabkota, 5=adminprov 9=super-->> lama
        // 1=pemantau, 2=operator kab, 3=admin kab, 4=operator prov, 5=adminprov 9=super-->> baru
        if (Auth::user()->level > 5)
        {
            //superadmin
            //ubah kode level
            $data = User::get();
            $total = 0;
            $admin_kab = 0;
            $op_prov = 0;
            $lain = 0;
            $new_hak_akses = 0;
            foreach ($data as $item) {
                if ($item->role == 1 && $item->level > 1)
                {
                    $d = User::where('id',$item->id)->first();
                    if ($item->level == 3)
                    {
                        $new_role = 4;
                        $op_prov++;
                    }
                    elseif ($item->level == 4)
                    {
                        $new_role = 3;
                        $admin_kab++;
                    }
                    else
                    {
                        $new_role = $item->level;
                        $lain++;
                    }
                    $d->role = $new_role;
                    $d->update();
                }
                //hak akses
                $h = HakAkses::where([['hak_username',$item->username],['hak_kodeunit',$item->kodeunit]])->first();
                if (!$h)
                {
                    $ha = new HakAkses();
                    $ha->hak_userid = $item->id;
                    $ha->hak_username = $item->username;
                    $ha->hak_kodeunit = $item->kodeunit;
                    $ha->hak_role = $item->role;
                    $ha->save();
                    $new_hak_akses++;
                }
                //batas hak akses
                $total++;
            }
            $arr = array(
                'status'=>true,
                'hasil'=>'Berhasil, Total user ('.$total.'), Admin Kab ('.$admin_kab.'), Operator Prov ('.$op_prov.'), Lainnya ('.$lain.') Hak Akses ('.$new_hak_akses.')'
            );
        }
        else
        {
            $arr = array(
                'status'=>false,
                'hasil'=>'Anda tidak memiliki hak akses'
            );
        }

        return Response()->json($arr);
    }
    public function cekOperator($username)
    {
        $count = User::where('username','=',$username)->count();
        $arr = array(
            'status'=>true,
            'hasil'=>'username operator tersedia'
        );
        if ($count > 0)
        {
            $arr = array(
                'status'=>false,
                'hasil'=>'username operator tidak tersedia'
            );
        }
        return Response()->json($arr);
    }
    public function cariOperator($id)
    {
        $count = User::where('id','=',$id)->count();
        $arr = array(
            'status'=>false,
            'hasil'=>'Data operator tidak tersedia'
        );
        if ($count > 0)
        {
            //ada user
            $data = User::where('id',$id)->first();
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
            $data_hak = HakAkses::where('hak_userid',$id)->get();
            if ($data_hak->count() > 0)
            {
                $hak_akses_data = array();
                foreach ($data_hak as $item)
                {
                    $hak_akses_data[] = array(
                        'id'=>$item->id,
                        'hak_userid'=>$item->hak_userid,
                        'hak_username'=>$item->hak_username,
                        'hak_nama_lengkap'=>$item->User->nama,
                        'hak_kodeunit'=>$item->hak_kodeunit,
                        'hak_kodeunit_nama'=>$item->TimKerja->unit_nama,
                        'hak_role'=>$item->hak_role,
                        'hak_role_nama'=>$item->Role->level_nama,
                        'created_at'=>$item->created_at,
                        'created_at_nama'=>Carbon::parse($item->created_at)->isoFormat('dddd, D MMMM Y H:mm'),
                        'updated_at'=>$item->updated_at,
                        'updated_at_nama'=>Carbon::parse($item->updated_at)->isoFormat('dddd, D MMMM Y H:mm')
                    );
                }
                $hak_akses = array(
                    'status'=>true,
                    'jumlah_record'=> $data_hak->count(),
                    'data' => $hak_akses_data
                );
            }
            else
            {
                $hak_akses = array(
                    'status'=>false,
                    'data'=>'belum ada hak akses'
                );
            }
            $arr = array(
                'status'=>true,
                'id'=>$data->id,
                'nama'=>$data->nama,
                'email'=>$data->email,
                'username'=>$data->username,
                'kodeunit'=>$data->kodeunit,
                'namaunit'=>$data->Unitkerja->unit_nama,
                'kodebps'=>$data->kodebps,
                'kodebps_nama'=>$data->NamaWilayah->bps_nama,
                'nohp'=>$data->nohp,
                'aktif'=>$data->aktif,
                'level'=>$data->level,
                'level_nama'=>$data->Level->level_nama,
                'role'=>$data->role,
                'role_nama'=>$data->Role->level_nama,
                'lastip'=>$lastip,
                'lastlogin'=>$data->lastlogin,
                'lastlogin_nama'=>$lastlog_nama,
                'created_at'=>$data->created_at,
                'updated_at'=>$data->updated_at,
                'hak_akses'=>$hak_akses
            );
        }
        return Response()->json($arr);
    }
    public function OperatorHapus(Request $request)
    {
        $count = User::where('id','=',$request->id)->count();
        $arr = array(
            'status'=>false,
            'hasil'=>'Data operator tidak tersedia'
        );
        if ($count>0)
        {
            $data = User::where('id','=',$request->id)->first();
            $nama = $data->nama;
            $username = $data->username;
            $data->delete();
            //hapus juga di hakakses
            $hapus_hak_akses = HakAkses::where('hak_userid',$request->id)->delete();
            $arr = array(
                'status'=>true,
                'hasil'=>'Data operator '.$nama.' ('.$username.') berhasil dihapus'
            );
        }
        return Response()->json($arr);
    }
    public function FlagOperator(Request $request)
    {
        $count = User::where('id','=',$request->id)->count();
        $arr = array(
            'status'=>false,
            'hasil'=>'Data operator tidak tersedia'
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
                'hasil'=>'Flag operator sudah diubah'
            );
        }
        return Response()->json($arr);
    }
    public function FlagLiatCkp(Request $request)
    {
        $count = User::where('id','=',$request->id)->count();
        $arr = array(
            'status'=>false,
            'hasil'=>'Data operator tidak tersedia'
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
            $data->flag_liatckp = $aktif;
            $data->update();
            $arr = array(
                'status'=>true,
                'hasil'=>'Flag Liat CKP sudah diubah'
            );
        }
        return Response()->json($arr);
    }
    public function SuperSimpan(Request $request)
    {
        //dd($request->all());
        /*
        array:10 [▼
            "_token" => "MM68boW5WHcj50uIFg91CmqW1tiSs8ZAFjRvRRZD"
            "wilayah" => "5200"
            "operator_level" => "1"
            "unitkode_prov" => "52510"
            "operator_nama" => "test"
            "operator_username" => "test"
            "operator_password" => "test"
            "operator_ulangi_password" => "test"
            "operator_email" => "test@gdafa.com"
            "operator_no_wa" => "0998892384923"
            ]
        */
        if (Auth::user()->role == 9)
        {
            //cek username ada tidak
            $count = User::where('username',$request->operator_username)->count();
            if ($count == 0)
            {
                //simpan
                //cek password dan ulangi password
                if ($request->operator_password == $request->operator_ulangi_password)
                {
                    if ($request->unitkode_prov)
                    {
                        //ada
                        $kodeunit = $request->unitkode_prov;
                    }
                    else
                    {
                        $kodeunit = $request->wilayah.'0';
                    }
                    $data = new User();
                    $data->nama = $request->operator_nama;
                    $data->password = bcrypt($request->operator_password);
                    $data->email = $request->operator_email;
                    $data->username  = $request->operator_username;
                    $data->kodeunit = $kodeunit;
                    $data->kodebps = $request->wilayah;
                    $data->nohp = trim($request->operator_no_wa);
                    $data->level = $request->operator_level;
                    $data->role = $request->operator_level;
                    $data->aktif = 1;
                    $data->save();
                    $pesan_error='Operator <b>'.$request->operator_nama.' ('.$request->operator_username.') </b> sudah disimpan';
                    $pesan_warna='success';
                }
                else
                {
                    $pesan_error='Operator password dan operator ulangi password tidak sama';
                    $pesan_warna='danger';
                }
            }
            else
            {
                //user sudah di pakai
                $pesan_error='Username <b>'.$request->operator_username.'</b> sudah terpakai, gunakan username yang lain';
                $pesan_warna='danger';
            }
        }
        else
        {
            $pesan_error='Anda tidak mempunyai akses terhadap aksi ini';
            $pesan_warna='danger';
        }
        Session::flash('message', $pesan_error);
        Session::flash('message_type', $pesan_warna);
        return redirect()->route('operator.list');
    }
    public function SuperUpdate(Request $request)
    {
        //dd($request->all());
        /*
        array:9 [▼
        "_token" => "2lWZtx9LoZ4BWuhSBxy2MzulT0dSSSH4zoCBZK3P"
        "wilayah" => "5200"
        "operator_level" => "5"
        "unitkode_prov" => "52510"
        "operator_nama" => "super"
        "operator_username" => "super"
        "operator_email" => "super@super.com"
        "operator_no_wa" => "089898881231"
        "operator_id" => "53"
        ]
        */
        if (Auth::user()->role == 9)
        {
            //cek id operator ada tidak
            $count = User::where('id',$request->operator_id)->count();
            if ($count > 0)
            {
                //simpan
                //cek password dan ulangi password

                if ($request->unitkode_prov)
                {
                    //ada
                    $kodeunit = $request->unitkode_prov;
                }
                else
                {
                    $kodeunit = $request->wilayah.'0';
                }
                $data =  User::where('id',$request->operator_id)->first();
                $data->nama = $request->operator_nama;
                $data->email = $request->operator_email;
                //$data->username  = $request->operator_username;
                $data->kodeunit = $kodeunit;
                $data->kodebps = $request->wilayah;
                $data->nohp = trim($request->operator_no_wa);
                $data->level = $request->operator_level;
                $data->role = $request->operator_level;
                $data->aktif = 1;
                $data->update();
                $pesan_error='Operator <b>'.$request->operator_nama.' ('.$request->operator_username.') </b> berhasil di update';
                $pesan_warna='success';
            }
            else
            {
                //user sudah di pakai
                $pesan_error='Operator <b>'.$request->operator_username.'</b> tidak ada';
                $pesan_warna='danger';
            }
        }
        else
        {
            $pesan_error='Anda tidak mempunyai akses terhadap aksi ini';
            $pesan_warna='danger';
        }
        Session::flash('message', $pesan_error);
        Session::flash('message_type', $pesan_warna);
        return redirect()->route('operator.list');
    }
    public function GantiPassword(Request $request)
    {
        //dd($request->all());
        /*
        array:5 [▼
        "_token" => "2lWZtx9LoZ4BWuhSBxy2MzulT0dSSSH4zoCBZK3P"
        "operator_id" => "49"
        "operator_nama" => "Anang Zakaria"
        "operator_password_baru" => "test"
        "operator_password_baru_ulangi" => "test"
        ]
        */
        $count = User::where('id',$request->operator_id)->count();
        if ($count > 0)
        {
            //operator ada
            if ($request->operator_password_baru != $request->operator_password_baru_ulangi)
            {
                $pesan_error="ERROR : Password baru dengan ulangi password baru tidak sama !!";
                $pesan_warna='danger';
            }
            else
            {
                $data = User::where('id',$request->operator_id)->first();
                $data->password = bcrypt($request->operator_password_baru);
                $data->update();
                $pesan_error='BERHASIL : Password Operator an. <b>'.$data->nama .' ('.$data->username.')</b> berhasil diganti';
                $pesan_warna='success';
            }
        }
        else
        {
            $pesan_error="ERROR : data operator tidak tersedia!!";
            $pesan_warna='danger';
        }
        Session::flash('message', $pesan_error);
        Session::flash('message_type', $pesan_warna);
        return redirect()->route('operator.list');
    }
    public function AdminProvSimpan(Request $request)
    {
        //dd($request->all());
        /*
        array:9 [▼
        "_token" => "OMoyuBlNBfpfhwsbjIW9gOjE8gliflCpxxCCQRzK"
        "operator_level" => "3"
        "unitkode_prov" => "52520"
        "operator_nama" => "dfafdasfdsa"
        "operator_username" => "admin52001"
        "operator_password" => "aa"
        "operator_ulangi_password" => "aa"
        "operator_email" => "aa@dfadfa.com"
        "operator_no_wa" => "00324932432"
        ]
        wilayah pakai wilayah admin yg add
        */
        if (Auth::user()->role == 5)
        {
            //cek username ada tidak
            $count = User::where('username',$request->operator_username)->count();
            if ($count == 0)
            {
                //simpan
                //cek password dan ulangi password
                if ($request->operator_password == $request->operator_ulangi_password)
                {
                    $data = new User();
                    $data->nama = $request->operator_nama;
                    $data->password = bcrypt($request->operator_password);
                    $data->email = $request->operator_email;
                    $data->username  = $request->operator_username;
                    $data->kodeunit = $request->unitkode_prov;
                    $data->kodebps = Auth::user()->kodebps;
                    $data->nohp = trim($request->operator_no_wa);
                    $data->level = $request->operator_level;
                    $data->role = $request->operator_level;
                    $data->aktif = 1;
                    $data->save();
                    $pesan_error='Operator <b>'.$request->operator_nama.' ('.$request->operator_username.') </b> sudah disimpan';
                    $pesan_warna='success';
                }
                else
                {
                    $pesan_error='Operator password dan operator ulangi password tidak sama';
                    $pesan_warna='danger';
                }
            }
            else
            {
                //user sudah di pakai
                $pesan_error='Username <b>'.$request->operator_username.'</b> sudah terpakai, gunakan username yang lain';
                $pesan_warna='danger';
            }
        }
        else
        {
            $pesan_error='Anda tidak mempunyai akses terhadap aksi ini';
            $pesan_warna='danger';
        }
        Session::flash('message', $pesan_error);
        Session::flash('message_type', $pesan_warna);
        return redirect()->route('operator.list');
    }
    public function AdminProvUpdate(Request $request)
    {
        //dd($request->all());
        /*
        array:8 [▼
        "_token" => "OMoyuBlNBfpfhwsbjIW9gOjE8gliflCpxxCCQRzK"
        "operator_level" => "5"
        "unitkode_prov" => "52520"
        "operator_nama" => "Admin Sosial Baru"
        "operator_username" => "adminsos"
        "operator_email" => "admin@sosial.bpsntb.id"
        "operator_no_wa" => "089239238432"
        "operator_id" => "56"
        ]
        */
        if (Auth::user()->role == 5)
        {
            //cek id operator ada tidak
            $count = User::where('id',$request->operator_id)->count();
            if ($count > 0)
            {
                //simpan
                //cek password dan ulangi password
                $data =  User::where('id',$request->operator_id)->first();
                $data->nama = $request->operator_nama;
                $data->email = $request->operator_email;
                //$data->username  = $request->operator_username;
                $data->kodeunit = $request->unitkode_prov;
                $data->nohp = trim($request->operator_no_wa);
                $data->level = $request->operator_level;
                $data->role = $request->operator_level;
                $data->aktif = 1;
                $data->update();
                $pesan_error='Operator <b>'.$request->operator_nama.' ('.$request->operator_username.') </b> berhasil di update';
                $pesan_warna='success';
            }
            else
            {
                //user sudah di pakai
                $pesan_error='Operator <b>'.$request->operator_username.'</b> tidak ada';
                $pesan_warna='danger';
            }
        }
        else
        {
            $pesan_error='Anda tidak mempunyai akses terhadap aksi ini';
            $pesan_warna='danger';
        }
        Session::flash('message', $pesan_error);
        Session::flash('message_type', $pesan_warna);
        return redirect()->route('operator.list');
    }
    public function AdminKabSimpan(Request $request)
    {
        //dd($request->all());
        /*
        array:8 [▼
        "_token" => "MNfx5FYUtMMfjv8IFukE7hVgLFjq5aGdnSZ6RtYY"
        "operator_level" => "4"
        "operator_nama" => "AA"
        "operator_username" => "admin52010"
        "operator_password" => "op"
        "operator_ulangi_password" => "op"
        "operator_email" => "aa@dafaminka.com"
        "operator_no_wa" => "2342343242"
        ]
        */
        if (Auth::user()->role == 3)
        {
            //cek username ada tidak
            $count = User::where('username',$request->operator_username)->count();
            if ($count == 0)
            {
                //simpan
                //cek password dan ulangi password
                if ($request->operator_password == $request->operator_ulangi_password)
                {
                    $data = new User();
                    $data->nama = $request->operator_nama;
                    $data->password = bcrypt($request->operator_password);
                    $data->email = $request->operator_email;
                    $data->username  = $request->operator_username;
                    $data->kodeunit = Auth::user()->kodebps."0";
                    $data->kodebps = Auth::user()->kodebps;
                    $data->nohp = trim($request->operator_no_wa);
                    $data->level = $request->operator_level;
                    $data->role = $request->operator_level;
                    $data->aktif = 1;
                    $data->save();
                    $pesan_error='Operator <b>'.$request->operator_nama.' ('.$request->operator_username.') </b> sudah disimpan';
                    $pesan_warna='success';
                }
                else
                {
                    $pesan_error='Operator password dan operator ulangi password tidak sama';
                    $pesan_warna='danger';
                }
            }
            else
            {
                //user sudah di pakai
                $pesan_error='Username <b>'.$request->operator_username.'</b> sudah terpakai, gunakan username yang lain';
                $pesan_warna='danger';
            }
        }
        else
        {
            $pesan_error='Anda tidak mempunyai akses terhadap aksi ini';
            $pesan_warna='danger';
        }
        Session::flash('message', $pesan_error);
        Session::flash('message_type', $pesan_warna);
        return redirect()->route('operator.list');
    }
    public function AdminKabUpdate(Request $request)
    {
        //dd($request->all());
        /*
        array:7 [▼
        "_token" => "MNfx5FYUtMMfjv8IFukE7hVgLFjq5aGdnSZ6RtYY"
        "operator_level" => "4"
        "operator_nama" => "AA Baru"
        "operator_username" => "admin52010"
        "operator_email" => "aa@dafaminka.com"
        "operator_no_wa" => "2342343242"
        "operator_id" => "57"
        ]
        */
        if (Auth::user()->role == 4)
        {
            //cek id operator ada tidak
            $count = User::where('id',$request->operator_id)->count();
            if ($count > 0)
            {
                //simpan
                //cek password dan ulangi password
                $data =  User::where('id',$request->operator_id)->first();
                $data->nama = $request->operator_nama;
                $data->email = $request->operator_email;
                //$data->username  = $request->operator_username;
                $data->nohp = trim($request->operator_no_wa);
                $data->level = $request->operator_level;
                $data->role = $request->operator_level;
                $data->aktif = 1;
                $data->update();
                $pesan_error='Operator <b>'.$request->operator_nama.' ('.$request->operator_username.') </b> berhasil di update';
                $pesan_warna='success';
            }
            else
            {
                //user sudah di pakai
                $pesan_error='Operator <b>'.$request->operator_username.'</b> tidak ada';
                $pesan_warna='danger';
            }
        }
        else
        {
            $pesan_error='Anda tidak mempunyai akses terhadap aksi ini';
            $pesan_warna='danger';
        }
        Session::flash('message', $pesan_error);
        Session::flash('message_type', $pesan_warna);
        return redirect()->route('operator.list');
    }
    public function UpdateHakAkses(Request $request)
    {
        //dd($request->all());
        if (Auth::user()->role >= 5)
        {
            //cek username ada tidak
            $data = User::where('id',$request->hak_opid)->first();
            if ($data)
            {
                //kosongkan dulu hak aksesnya, input baru
                $kosong_data = HakAkses::where('hak_userid',$request->hak_opid)->delete();
                //cek kodeunit utama dulu
                //array map di ubah dulu, antisipasi sql injection
                $data_hak_akses = array_map('intval', $request->hak_akses);
                //dd($data_hak_akses);
                foreach ($data_hak_akses as $item)
                {
                    //dd($item);
                    $data_baru = HakAkses::where('hak_userid',$request->hak_opid)->where('hak_kodeunit',$item)->first();
                    if (!$data_baru)
                    {
                        $data_new = new HakAkses();
                        $data_new->hak_userid = $request->hak_opid;
                        $data_new->hak_username = $data->username;
                        $data_new->hak_kodeunit = $item;
                        $data_new->hak_role = $data->role;
                        $data_new->save();
                    }
                }
                //cek unit utama sudah masuk belum
                $data_baru = HakAkses::where('hak_userid',$request->hak_opid)->where('hak_kodeunit',$data->kodeunit)->first();
                if (!$data_baru)
                {
                    $data_new = new HakAkses();
                    $data_new->hak_userid = $request->hak_opid;
                    $data_new->hak_username = $data->username;
                    $data_new->hak_kodeunit = $data->kodeunit;
                    $data_new->hak_role = $data->role;
                    $data_new->save();
                }
                $pesan_error='Operator <b>'.$data->nama.' ('.$data->username.') </b> berhasil di update hak aksesnya';
                $pesan_warna='success';
            }
            else
            {
                //user sudah di pakai
                $pesan_error='(ERROR) Operator tidak tersedia';
                $pesan_warna='danger';
            }
        }
        else
        {
            $pesan_error='Anda tidak mempunyai akses terhadap aksi ini';
            $pesan_warna='danger';
        }
        Session::flash('message', $pesan_error);
        Session::flash('message_type', $pesan_warna);
        return redirect()->route('operator.list');
    }
}

<?php

namespace App\Http\Controllers;

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
use Illuminate\Support\Facades\Hash;

class MyProfileController extends Controller
{
    //
    public function MyProfile()
    {
        return view('profiles.index');
    }
    public function UpdateProfile(Request $request)
    {
        //dd($request->all());
        $data = User::where('id',Auth::user()->id)->first();
        $data->nama = $request->operator_nama;
        $data->email = $request->operator_email;
        $data->nohp = $request->operator_no_wa;
        $data->update();
        Session::flash('message', 'Data berhasil diupdate');
        Session::flash('message_type', 'success');
        return redirect()->route('my.profile');
    }
    public function GantiPassword(Request $request)
    {

        if (Hash::check($request->passwd_lama, Auth::user()->password))
        {
            if ($request->passwd_baru == $request->passwd_baru_ulangi)
            {
                $data = User::where('id',Auth::user()->id)->first();
                $data->password = bcrypt($request->passwd_baru);
                $data->update();
                Session::flash('message', '<b>BERHASIL</b> Password berhasil diganti. Silakan login ulang untuk menggunakan password baru');
                Session::flash('message_type', 'success');
            }
            else
            {
                Session::flash('message', '<b>ERROR</b> Password baru dengan Konfirmasi password baru tidak sama');
                Session::flash('message_type', 'danger');
            }
        }
        else
        {
            Session::flash('message', '<b>ERROR</b> Password lama tidak sama. password belum berubah');
            Session::flash('message_type', 'danger');
        }
        return redirect()->route('my.profile');
    }
}

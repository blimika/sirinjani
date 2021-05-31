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

class LogAktivitasController extends Controller
{
    //
    public function ListLog()
    {
        $data = LogAktivitas::orderBy('created_at','desc')->get();
        return view('aktivitas.index',[
            'dataLog'=>$data,
        ]);
    }
}

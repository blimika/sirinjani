<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Api;
use Carbon\Carbon;
use Telegram\Bot\Keyboard\Keyboard;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Http;
use App\User;
use Illuminate\Support\Facades\Validator;
use Telegram\Bot\FileUpload\InputFile;
use App\Helpers\WebAkses;
use App\Notifikasi;
use App\JenisNotifikasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\Generate;
use App\Helpers\Tanggal;
use Illuminate\Support\Str;
use App\Kegiatan;

class NotifikasiController extends Controller
{
    //
    /*
    Notifikasi dikirim ke email dan telegram operator
    1. Kegiatan Baru
    kirim ke operator kabkota dan admin kabkota
    2. Pengiriman
    kirim ke operator provinsi SM nya
    3. Penerimaan
    kirim ke operator kabkota dan admin kabkota yang mengirim
    4. Update Kegiatan
    kirim ke operator kabkota yang di update targetnya
    5. Lainnya
    Operator Provinsi hapus kegiatan
    */
    protected $telegram;
    protected $chat_id;
    protected $username;
    protected $text;
    public function __construct()
    {
        $this->telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
    }

    public function WebHook(Request $request)
    {

    }
    public function getMe()
    {
        $response = $this->telegram->getMe();
        //return $response;
        return view('admin.getme',['respon'=>$response]);
    }
    public function WebhookInfo()
    {
        $h = new WebAkses();
        $response = $h->webinfo();

        //return $response;
        return view('admin.botstatus',['respon'=>$response]);
    }
    public function setWebHook()
    {
        $url = env('TELEGRAM_WEBHOOK_URL') . '/' . env('TELEGRAM_HASH_URL') . '/webhook';
        $response = $this->telegram->setWebhook(['url' => $url]);
        //dd($response);
        return view('admin.setwebhook',['respon'=>$response]);
    }
    public function OffWebHook()
    {
        $h = new WebAkses();
        $response = $h->resetwebhook();

        //return $response;
        return view('admin.setwebhook',['respon'=>$response]);
    }
    public function list()
    {
        $data = Notifikasi::where('notif_untuk',Auth::user()->username)->orderBy('notif_flag','asc')->orderBy('created_at','desc')->get();
        //dd($data);
        return view('notif.list',[
            'dataNotif'=>$data
        ]);
    }
    public function getNotif($id)
    {
        $count = Notifikasi::where('id',$id)->count();
        $arr = array(
            'status'=>false,
            'hasil'=>'Data notifikasi tidak tersedia'
        );
        if ($count > 0)
        {
            $data = Notifikasi::where('id',$id)->first();
            if ($data->notif_flag == 0)
            {
                $notif_flag = 'Belum terbaca';
            }
            else
            {
                $notif_flag = 'Sudah dibaca';
            }
            $cek_keg = Kegiatan::where('keg_id',$data->keg_id)->count();
            if ($cek_keg > 0)
            {
                $keg_nama = $data->Kegiatan->keg_nama;
            }
            else
            {
                $keg_nama = 'Kegiatan ini telah terhapus';
            }
            $arr = array(
                'status'=>true,
                'notif_id'=>$data->id,
                'notif_dari'=>$data->notif_dari,
                'notif_untuk'=>$data->notif_untuk,
                'notif_keg_id'=>$data->keg_id,
                'notif_keg_nama'=>$keg_nama,
                'notif_isi'=>$data->notif_isi,
                'notif_isi_pendek'=>Str::limit($data->notif_isi, 50, ' (...)'),
                'notif_created_at_nama'=>Tanggal::LengkapHariPanjang($data->created_at),
                'notif_updated_at_nama'=>Tanggal::LengkapHariPanjang($data->updated_at),
                'notif_flag'=>$data->notif_flag,
                'notif_flag_nama'=>$notif_flag,
                'notif_jenis'=>$data->notif_jenis,
                'notif_jenis_nama'=>$data->JenisNotif->jnotif_nama,
                'created_at'=>$data->created_at,
                'updated_at'=>$data->updated_at
            );
            //set notifikasi terbaca
            $data->notif_flag = 1;
            $data->update();
        }
        return Response()->json($arr);
    }
    public function HapusNotif(Request $request)
    {
        $count = Notifikasi::where('id',$request->id)->count();
        $arr = array(
            'status'=>false,
            'hasil'=>'Data notifikasi tidak tersedia'
        );
        if ($count>0)
        {
            $data = Notifikasi::where('id',$request->id)->first();
            $notif_dari = $data->notif_dari;
            $data->delete();
            $arr = array(
                'status'=>true,
                'hasil'=>'Data notifikasi dari '.$notif_dari.' berhasil dihapus'
            );
        }
        return Response()->json($arr);
    }
    public function BotListTelegram()
    {
        return view('telegram.index');
    }
}

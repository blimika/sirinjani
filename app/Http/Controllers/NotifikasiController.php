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
}

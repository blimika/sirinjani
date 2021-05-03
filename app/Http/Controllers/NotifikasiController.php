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

class NotifikasiController extends Controller
{
    //
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
        return view('notif.list');
    }
}

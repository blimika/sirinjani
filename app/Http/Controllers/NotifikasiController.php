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
use App\LogPosisi;

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
    protected $user_tg;
    protected $text;
    protected $nama;
    protected $first_name;
    protected $keyboard;
    protected $message_id;
    protected $msg_id;
    protected $update_id;
    public function __construct()
    {
        $this->telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
        $this->keyboard_awal = [
            ['💾 Binding']
        ];
        $this->keyboard_menu = [
            ['Deadline Kegiatan','Cari Kegiatan','Detil Kegiatan'],
            ['📺 Profil','Ganti Password','Unbinding']
        ];
    }

    public function WebHook(Request $request)
    {
        $update = $this->telegram->getWebhookUpdate();
        if ($update->isType('callback_query'))
        {
            //calback_query
        }
        else
        {
            //tanpa callback_query
            if (isset($request['edited_message']))
            {
                //cek apakah message ini di edit
                $this->chat_id = $request['edited_message']['chat']['id'];
                $this->first_name = $request['edited_message']['from']['first_name'];
                $this->text = $request['edited_message']['text'];
                $this->message_id = $request['edited_message']['message_id'];
                $this->waktu_kirim = $request['edited_message']['date'];
                $this->update_id = $request['update_id'];
                if (isset($request['edited_message']['reply_to_message']['forward_date']))
                {
                    $this->forward_date = $request['edited_message']['reply_to_message']['forward_date'];
                }
                else
                {
                    $this->forward_date = $request['edited_message']['date'];
                }
                if (array_key_exists("username",$request['edited_message']['from']))
                {
                    $this->user_tg = $request['edited_message']['from']['username'];
                }
                else
                {
                    $this->user_tg = $this->first_name;
                }
            }
            else
            {
                //tanpa edited_message
                //message biasa
                $this->chat_id = $request['message']['chat']['id'];
                $this->first_name = $request['message']['from']['first_name'];
                $this->text = $request['message']['text'];
                $this->message_id = $request['message']['message_id'];
                $this->waktu_kirim = $request['message']['date'];
                $this->update_id = $request['update_id'];

                if (isset($request['message']['reply_to_message']['forward_date']))
                {
                    $this->forward_date = $request['message']['reply_to_message']['forward_date'];
                }
                else
                {
                    $this->forward_date = $request['message']['date'];
                }
                if (array_key_exists("username",$request['message']['from']))
                {
                    $this->user_tg = $request['message']['from']['username'];
                }
                else
                {
                    $this->user_tg = $this->first_name;
                }
            }
            //cek test commandnya

            switch ($this->text) {
                case '/start':
                    $this->MenuDepan();
                    break;
                case '💾 Binding':
                    $this->BindingAkun();
                    break;
                case '📺 Profil':
                    $this->ProfilAkun();
                    break;
                default:
                    $this->CekInputan();
                    break;
            }
        }

    }
    //cek telegram
    public function MenuDepan()
    {
        //cek dulu chat_id sudah terbinding dengan operator / tidak
        $count = User::where('chatid_tg','=',$this->chat_id)->count();
        if ($count > 0)
        {
            //sudah terbinding
            $message = '### 🔎 SiRinjani Bot Telegram 🔍 ###' .chr(10);
            $message .= '<b>BPS Provinsi Nusa Tenggara Barat</b>' .chr(10) .chr(10);
            $reply_markup = Keyboard::make([
                'keyboard' => $this->keyboard_menu,
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ]);
            $response = Telegram::sendMessage([
                'chat_id' => $this->chat_id,
                'text' => $message,
                'parse_mode'=> 'HTML',
                'reply_markup' => $reply_markup
            ]);
            $messageId = $response->getMessageId();
        }
        else
        {
            //belum terbinding
            $message = '### 🔎 SiRinjani Bot Telegram 🔍 ###' .chr(10);
            $message .= '<b>BPS Provinsi Nusa Tenggara Barat</b>' .chr(10) .chr(10);
            $message .= 'Untuk dapat menggunakan bot ini silakan terlebih dahulu melakukan koneksi antara telegram dengan sistem <b>SiRinjani v2.0</b> dengan menekan tombol <b>Binding</b>' .chr(10);

            $reply_markup = Keyboard::make([
                'keyboard' => $this->keyboard_awal,
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ]);
            $response = Telegram::sendMessage([
                'chat_id' => $this->chat_id,
                'text' => $message,
                'parse_mode'=> 'HTML',
                'reply_markup' => $reply_markup
            ]);
            $messageId = $response->getMessageId();
        }
    }
    public function BindingAkun()
    {
        $message = '### 💾 Binding ###' .chr(10);
        $message .= 'Silakan masukkan Token Telegram dari aplikasi <b>SiRinjani v.2</b>' .chr(10);
        $message .= 'dari menu Profil > Token Telegram' .chr(10);

        LogPosisi::create([
            'user_tg' => $this->user_tg,
            'chatid_tg' => $this->chat_id,
            'command' => __FUNCTION__,
            'msg_id' => $this->message_id,
            'update_id' => $this->update_id,
            'waktu_tg' => $this->waktu_kirim
        ]);

        $response = Telegram::sendMessage([
            'chat_id' => $this->chat_id,
            'text' => $message,
            'parse_mode'=> 'HTML',
        ]);
        $messageId = $response->getMessageId();
    }
    public function ProfilAkun()
    {

    }
    public function CekInputan()
    {
        //cek logposisi dulu
        $cek = LogPosisi::where('chatid_tg','=',$this->chat_id)->count();
        if ($cek > 0)
        {
            //ambil komen terakhir
            $tg = LogPosisi::where('chatid_tg','=',$this->chat_id)->latest("updated_at")->first();
            if ($tg->command == 'Profil')
            {

            }
            elseif ($tg->command == 'BindingAkun')
            {
                //cek dulu token dgn user
                $count = User::where('token_tg',$this->text)->count();
                if ($count > 0)
                {
                    //ada dan langsung update user_tg dan chat_id
                    $data = User::where('token_tg',$this->text)->first();
                    $data->user_tg = $this->user_tg;
                    $data->chatid_tg = $this->chat_id;
                    $data->update();

                    LogPosisi::create([
                        'user_tg' => $this->user_tg,
                        'chatid_tg' => $this->chat_id,
                        'command' => 'MenuDepan',
                        'msg_id' => $this->message_id,
                        'update_id' => $this->update_id,
                        'waktu_tg' => $this->waktu_kirim
                    ]);

                    $message ='';
                    $message .='<b>Token telegram valid.</b> akun telegram dan akun <b>SiRinjani</b> sudah terhubung. anda akan menerima notifikasi pemberitahuan dari aplikasi <b>SiRinjani</b>.' . chr(10) .chr(10);
                    $reply_markup = Keyboard::make([
                        'keyboard' => $this->keyboard_menu,
                        'resize_keyboard' => true,
                        'one_time_keyboard' => true
                    ]);
                    $response = Telegram::sendMessage([
                        'chat_id' => $this->chat_id,
                        'text' => $message,
                        'parse_mode'=> 'HTML',
                        'reply_markup' => $reply_markup
                    ]);
                    $messageId = $response->getMessageId();
                }
                else
                {
                    //token tidak valid silakan ulangi
                    $message ='';
                    $message .='<b>Token telegram tidak valid.</b> 😁' . chr(10) .chr(10);

                    $response = Telegram::sendMessage([
                        'chat_id' => $this->chat_id,
                        'text' => $message,
                        'parse_mode'=> 'HTML'
                    ]);
                    $this->BindingAkun();
                }
            }
            else
            {
                //command tidak dikenal
                $message ='';
                $message .='Permintaan kamu tidak diproses 😁' . chr(10);

                $response = Telegram::sendMessage([
                    'chat_id' => $this->chat_id,
                    'text' => $message,
                    'parse_mode'=> 'HTML'
                ]);
            }
        }
        else
        {
            $this->MenuDepan();
        }
    }
    //batas telegram
    public function GetMeBot()
    {
        $response = $this->telegram->getMe();
        //return $response;
        //dd($response);
        return view('telegram.getme',['respon'=>$response]);
    }
    public function BotStatus()
    {
        $h = new WebAkses();
        $response = $h->webinfo();
        //dd($response);
        //return $response;
        return view('telegram.status',['respon'=>$response]);
    }
    public function setWebHook()
    {
        $url_site = env('TELEGRAM_WEBHOOK_URL') . '/' . env('TELEGRAM_HASH_URL') . '/webhook';
        $h = new WebAkses();
        $response = $h->setwebhook($url_site);
        //$response = $this->telegram->setWebhook(['url' => $url]);
        //dd($response);
        return view('telegram.setwebhook',['respon'=>$response]);
    }
    public function OffWebHook()
    {
        $h = new WebAkses();
        $response = $h->resetwebhook();

        //return $response;
        return view('telegram.offwebhook',['respon'=>$response]);
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

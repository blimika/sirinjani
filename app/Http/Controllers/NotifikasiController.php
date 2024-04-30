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
    protected $chan_notif_id;
    protected $chan_log_id;
    protected $message;
    public function __construct()
    {
        $this->telegram = new Api(env('TELEGRAM_BOT_TOKEN'));
        $this->chan_notif_id = env('TELEGRAM_CHAN_NOTIF');
        $this->chan_log_id = env('TELEGRAM_CHAN_LOG');
        $this->keyboard_awal = [
            ['🔐 Binding']
        ];
        $this->keyboard_menu = [
            ['📃 Deadline Kegiatan','📊 Peringkat','🎗 Informasi'],
            ['📺 Profil','🔑 Putuskan Koneksi']
        ];
        $this->keyboard_peringkat = [
            ['📅 Bulan Berjalan','📚 Tahunan'],
            ['🚫 Kembali']
        ];
        $this->keyboard_unbind = [
            ['👍 Yakin','🚫 Kembali']
        ];
        $this->keyboard_batal = [
            ['🚫 Kembali']
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
                case '🔐 Binding':
                    $this->BindingAkun();
                    break;
                case '🔑 Putuskan Koneksi':
                    $this->UnbindAkun();
                    break;
                case '📺 Profil':
                    $this->ProfilAkun();
                    break;
                case '📊 Peringkat':
                    $this->PilihPeringkat();
                    break;
                case '🎗 Informasi':
                    $this->InformasiBot();
                    break;
                case '📃 Deadline Kegiatan':
                    $this->DeadlineKegiatan();
                    break;
                case '🚫 Kembali':
                    $this->Batalkan();
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
            $message .= '---------------------------------' .chr(10);
            $message .= 'Saluran notifikasi : @sirinjani' .chr(10);
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
    public function DeadlineKegiatan()
    {
        LogPosisi::create([
            'user_tg' => $this->user_tg,
            'chatid_tg' => $this->chat_id,
            'command' => __FUNCTION__,
            'msg_id' => $this->message_id,
            'update_id' => $this->update_id,
            'waktu_tg' => $this->waktu_kirim
        ]);
        $cek_total = Kegiatan::whereBetween('keg_end',array(\Carbon\Carbon::now()->format('Y-m-d'), \Carbon\Carbon::now()->addWeek()->format('Y-m-d')))->orderBy('keg_end')->count();
        $message ='';
        if ($cek_total > 0)
        {
            $item_per_hal = 15;
            $i=1;
            if ($cek_total > $item_per_hal)
            {
                $hal = ceil($cek_total/$item_per_hal);
                if ($hal > 5)
                {
                    $hal = 5;
                }
                for ($j = 1 ; $j <= $hal; $j++)
                {
                    //display per halaman
                    $data = Kegiatan::whereBetween('keg_end',array(\Carbon\Carbon::now()->format('Y-m-d'), \Carbon\Carbon::now()->addWeek()->format('Y-m-d')))->orderBy('keg_end')->skip((($j-1)*$item_per_hal))->take($item_per_hal)->get();
                    $message = '<b>📑📑📑 KEGIATAN MENDEKATI BATAS WAKTU 📑📑📑</b>' .chr(10);
                    $message .= '----------------------------------------------------------------------' .chr(10);
                    $message .= '💾 Halaman '.$j .chr(10);
                    $message .= '----------------------------------------------------------------------' .chr(10);
                    foreach ($data as $item)
                    {
                        $message .= '🗂 <b>'. $item->keg_nama .'</b>'.chr(10);
                        $message .= '🗓 Tanggal : <i>'.Tanggal::Panjang($item->keg_end).'</i>'.chr(10);
                        $message .= '✉️ Pengiriman : '.number_format(($item->RealisasiKirim->sum('keg_r_jumlah')/$item->keg_total_target)*100,2,",",".").'%' .chr(10);
                        $message .= '📨 Penerimaan : '.number_format(($item->RealisasiTerima->sum('keg_r_jumlah')/$item->keg_total_target)*100,2,",",".").'%' .chr(10);
                        $message .= '📎 Link : <a href="'.route('kegiatan.detil',$item->keg_id).'"> Kegiatan detil</a>' .chr(10);
                        $message .= '---------------------------------------------'.chr(10);
                    }
                    $reply_markup = Keyboard::make([
                        'keyboard' => $this->keyboard_menu,
                        'resize_keyboard' => true,
                        'one_time_keyboard' => true
                    ]);
                    $response = Telegram::sendMessage([
                        'chat_id' => $this->chat_id,
                        'text' => $message,
                        'parse_mode'=> 'HTML',
                        'disable_web_page_preview'=>true,
                        'reply_markup' => $reply_markup
                    ]);
                    $messageId = $response->getMessageId();
                }
            }
            else
            {
                //kurang dari item_per_hal
                $data = Kegiatan::whereBetween('keg_end',array(\Carbon\Carbon::now()->format('Y-m-d'), \Carbon\Carbon::now()->addWeek()->format('Y-m-d')))->orderBy('keg_end')->get();
                $message = '<b>📑📑📑 KEGIATAN MENDEKATI BATAS WAKTU 📑📑📑</b>' .chr(10);
                $message .= '----------------------------------------------------------------------' .chr(10);
                foreach ($data as $item)
                {
                    $message .= '🗂 <b>'. $item->keg_nama .'</b>'.chr(10);
                    $message .= '🗓 Tanggal : <i>'.Tanggal::Panjang($item->keg_end).'</i>'.chr(10);
                    $message .= '✉️ Pengiriman : '.number_format(($item->RealisasiKirim->sum('keg_r_jumlah')/$item->keg_total_target)*100,2,",",".").'%' .chr(10);
                    $message .= '📨 Penerimaan : '.number_format(($item->RealisasiTerima->sum('keg_r_jumlah')/$item->keg_total_target)*100,2,",",".").'%' .chr(10);
                    $message .= '📎 Link : <a href="'.route('kegiatan.detil',$item->keg_id).'"> Kegiatan detil</a>' .chr(10);
                    $message .= '---------------------------------------------'.chr(10);
                }
                $reply_markup = Keyboard::make([
                    'keyboard' => $this->keyboard_menu,
                    'resize_keyboard' => true,
                    'one_time_keyboard' => true
                ]);
                $response = Telegram::sendMessage([
                    'chat_id' => $this->chat_id,
                    'text' => $message,
                    'parse_mode'=> 'HTML',
                    'disable_web_page_preview'=>true,
                    'reply_markup' => $reply_markup
                ]);
                $messageId = $response->getMessageId();
            }
        }
        else
        {
            $message .= '<b>Belum ada kegiatan yang mendekati batas waktu</b>' .chr(10);
        }
    }
    public function InformasiBot()
    {
        $message = '### INFORMASI ###' .chr(10);
        $message .= 'Bot ini merupakan Bot telegram dari aplikasi <b>SiRinjani</b>' .chr(10);
        $message .= 'Saluran notifikasi : @sirinjani' .chr(10);

        LogPosisi::create([
            'user_tg' => $this->user_tg,
            'chatid_tg' => $this->chat_id,
            'command' => __FUNCTION__,
            'msg_id' => $this->message_id,
            'update_id' => $this->update_id,
            'waktu_tg' => $this->waktu_kirim
        ]);

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
    public function UnbindAkun()
    {
        LogPosisi::create([
            'user_tg' => $this->user_tg,
            'chatid_tg' => $this->chat_id,
            'command' => __FUNCTION__,
            'msg_id' => $this->message_id,
            'update_id' => $this->update_id,
            'waktu_tg' => $this->waktu_kirim
        ]);
        $message = '### UNBIND AKUN ###' .chr(10);
        $message .= 'Apakah anda yakin?, Pilih menu' .chr(10);

        $reply_markup = Keyboard::make([
            'keyboard' => $this->keyboard_unbind,
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
    public function ProfilAkun()
    {
        //tampilkan profil akun
        //sudah terbinding
        LogPosisi::create([
            'user_tg' => $this->user_tg,
            'chatid_tg' => $this->chat_id,
            'command' => __FUNCTION__,
            'msg_id' => $this->message_id,
            'update_id' => $this->update_id,
            'waktu_tg' => $this->waktu_kirim
        ]);
        $cek = User::where('chatid_tg',$this->chat_id)->count();
        if ($cek > 0)
        {
            //ada dan sudah terbinding
            $data = User::where('chatid_tg',$this->chat_id)->first();
            $message = '<b>### PROFIL AKUN ###</b>' .chr(10);
            $message .= '🆔 ID Telegram : <b>'.$this->chat_id.'</b>'.chr(10);
            $message .= '👤 User Telegram : <b>'.$this->user_tg.'</b>' .chr(10);
            $message .= '🟢 username : <b>'.$data->username.'</b>' .chr(10);
            $message .= '🟢 Nama : <b>'.$data->nama.'</b>' .chr(10);
            $message .= '🟢 Email : <b>'.$data->email.'</b>'.chr(10);
            $message .= '🟢 Unitkerja : <b>'.$data->Unitkerja->unit_nama.'</b>'.chr(10);
            $message .= '🟢 Level : <b>'.$data->Level->level_nama.'</b>'.chr(10);
            $message .= '🟢 Last login : <b>'.$data->lastlogin.'</b>'.chr(10);
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
            $this->MenuDepan();
        }

    }
    public function PilihPeringkat()
    {
        LogPosisi::create([
            'user_tg' => $this->user_tg,
            'chatid_tg' => $this->chat_id,
            'command' => __FUNCTION__,
            'msg_id' => $this->message_id,
            'update_id' => $this->update_id,
            'waktu_tg' => $this->waktu_kirim
        ]);

        $message ='';
        $message .= 'Silakan pilih menu' .chr(10);


        $reply_markup = Keyboard::make([
            'keyboard' => $this->keyboard_peringkat,
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
    public function Batalkan()
    {
        $message ='';
        $message .= 'Okay..Kembali ke awal 😢' .chr(10);

        LogPosisi::where('chatid_tg',$this->chat_id)->delete();
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
    public function CekInputan()
    {
        //cek logposisi dulu
        $cek = LogPosisi::where('chatid_tg','=',$this->chat_id)->count();
        if ($cek > 0)
        {
            //ambil komen terakhir
            $tg = LogPosisi::where('chatid_tg','=',$this->chat_id)->latest("updated_at")->first();
            if ($tg->command == 'PilihPeringkat')
            {
                //peringkat bulan
                //peringkat tahunan
                $data_bulan = array(
                    1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'
                );
                if ($this->text == '📚 Tahunan')
                {
                    //tahunan
                    /*
                    +"keg_t_unitkerja": "52720"
                    +"unit_nama": "BPS Kota Bima"
                    +"keg_jml_target": "2819"
                    +"point_waktu": "1014.0000"
                    +"point_jumlah": "1015.0000"
                    +"point_total": "1014.7000"
                    +"point_rata": "4.55022422"
                    +"keg_jml": 223
                    */
                    $bulan_filter=(int) date('m');
                    $tahun_filter=date('Y');
                    $data = DB::table('m_keg')
                    ->leftJoin('m_keg_target','m_keg.keg_id','=','m_keg_target.keg_id')
                    ->leftJoin(DB::raw("(select unit_kode as unit_kode_prov, unit_nama as unit_nama_prov, unit_parent as unit_parent_prov from t_unitkerja where unit_jenis='1') as unit_prov"),'m_keg.keg_unitkerja','=','unit_prov.unit_kode_prov')
                    ->leftJoin('t_unitkerja','m_keg_target.keg_t_unitkerja','=','t_unitkerja.unit_kode')
                    ->when(request('unit'),function ($query){
                        return $query->where('unit_prov.unit_parent_prov','=',request('unit'));
                    })
                    ->whereMonth('m_keg.keg_end','<=',$bulan_filter)
                    ->whereYear('m_keg.keg_end','=',$tahun_filter)
                    ->where('m_keg_target.keg_t_target','>','0')
                    ->select(DB::raw("m_keg_target.keg_t_unitkerja,t_unitkerja.unit_nama, sum(m_keg_target.keg_t_target) as keg_jml_target, sum(m_keg_target.keg_t_point_waktu) as point_waktu, sum(m_keg_target.keg_t_point_jumlah) as point_jumlah, sum(m_keg_target.keg_t_point) as point_total, avg(m_keg_target.keg_t_point) as point_rata, count(*) as keg_jml"))
                    ->groupBy('m_keg_target.keg_t_unitkerja')
                    ->orderBy('point_rata','desc')
                    ->orderBy('keg_jml_target','desc')
                    ->orderBy('keg_jml','desc')
                    ->orderBy('m_keg_target.keg_t_unitkerja','asc')
                    ->get();
                    LogPosisi::create([
                        'user_tg' => $this->user_tg,
                        'chatid_tg' => $this->chat_id,
                        'command' => 'PilihPeringkat',
                        'msg_id' => $this->message_id,
                        'update_id' => $this->update_id,
                        'waktu_tg' => $this->waktu_kirim
                    ]);

                    $message ='';
                    $message .= '<b>🏆🏆🏆 Peringkat Tahunan sampai bulan '.$data_bulan[$bulan_filter].' '.$tahun_filter.' 🏆🏆🏆</b>' .chr(10);
                    $message .= '----------------------------------------------------------' .chr(10);
                    $message .= 'Keadaan : '. Tanggal::LengkapHariPanjang(\Carbon\Carbon::now()) .chr(10);
                    $message .= '----------------------------------------------------------' .chr(10);
                    $i = 1;
                    foreach ($data as $item)
                    {
                        if ($i == 1)
                        {
                            $message .= '🥇 <b>'.$item->unit_nama.'</b>'.chr(10);
                            $message .= '🗳 Kegiatan : '.$item->keg_jml .chr(10);
                            $message .= '📂 Target : '. $item->keg_jml_target .chr(10);
                            $message .= '🎖 Poin : '. number_format($item->point_rata,2,".",",") .chr(10);
                            $message .= '----------------------------------------------------------' .chr(10);
                        }
                        elseif ($i == 2)
                        {
                            $message .= '🥈 <b>'.$item->unit_nama.'</b>' .chr(10);
                            $message .= '🗳 Kegiatan : '.$item->keg_jml .chr(10);
                            $message .= '📂 Target : '. $item->keg_jml_target .chr(10);
                            $message .= '🎖 Poin : '. number_format($item->point_rata,2,".",",") .chr(10);
                            $message .= '----------------------------------------------------------' .chr(10);
                        }
                        elseif ($i == 3)
                        {
                            $message .= '🥉 <b>'.$item->unit_nama.'</b>' .chr(10);
                            $message .= '🗳 Kegiatan : '.$item->keg_jml .chr(10);
                            $message .= '📂 Target : '. $item->keg_jml_target .chr(10);
                            $message .= '🎖 Poin : '. number_format($item->point_rata,2,".",",") .chr(10);
                            $message .= '----------------------------------------------------------' .chr(10);
                        }
                        else
                        {
                            $message .= '🎗 <b>'.$item->unit_nama.'</b>' .chr(10);
                            $message .= '🗳 Kegiatan : '.$item->keg_jml .chr(10);
                            $message .= '📂 Target : '. $item->keg_jml_target .chr(10);
                            $message .= '🎖 Poin : '. number_format($item->point_rata,2,".",",") .chr(10);
                            $message .= '----------------------------------------------------------' .chr(10);
                        }
                        $i++;
                    }
                    $reply_markup = Keyboard::make([
                        'keyboard' => $this->keyboard_peringkat,
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
                    //bulanan
                    /*
                    0 => {#1334 ▼
                    +"bulan": 6
                    +"tahun": 2021
                    +"keg_t_unitkerja": "52720"
                    +"unit_nama": "BPS Kota Bima"
                    +"keg_jml_target": "333"
                    +"point_waktu": "30.0000"
                    +"point_jumlah": "30.0000"
                    +"point_total": "30.0000"
                    +"point_rata": "1.25000000"
                    +"keg_jml": 24
                    */
                    //set bulan dan tahun sekarang
                    $bulan_filter=(int) date('m');
                    $tahun_filter=date('Y');
                    $data = DB::table('m_keg')
                    ->leftJoin('m_keg_target','m_keg.keg_id','=','m_keg_target.keg_id')
                    ->leftJoin(DB::raw("(select unit_kode as unit_kode_prov, unit_nama as unit_nama_prov, unit_parent as unit_parent_prov from t_unitkerja where unit_jenis='1') as unit_prov"),'m_keg.keg_unitkerja','=','unit_prov.unit_kode_prov')
                    ->leftJoin('t_unitkerja','m_keg_target.keg_t_unitkerja','=','t_unitkerja.unit_kode')
                    ->when(request('unit'),function ($query){
                        return $query->where('unit_prov.unit_parent_prov','=',request('unit'));
                    })
                    ->whereMonth('m_keg.keg_end','=',$bulan_filter)
                    ->whereYear('m_keg.keg_end','=',$tahun_filter)
                    ->where('m_keg_target.keg_t_target','>','0')
                    ->select(DB::raw("month(m_keg.keg_end) as bulan, year(m_keg.keg_end) as tahun,m_keg_target.keg_t_unitkerja,t_unitkerja.unit_nama, sum(m_keg_target.keg_t_target) as keg_jml_target, sum(m_keg_target.keg_t_point_waktu) as point_waktu, sum(m_keg_target.keg_t_point_jumlah) as point_jumlah, sum(m_keg_target.keg_t_point) as point_total, avg(m_keg_target.keg_t_point) as point_rata, count(*) as keg_jml"))
                    ->groupBy('m_keg_target.keg_t_unitkerja')
                    ->orderBy('point_rata','desc')
                    ->orderBy('keg_jml_target','desc')
                    ->orderBy('keg_jml','desc')
                    ->orderBy('m_keg_target.keg_t_unitkerja','asc')
                    ->get();

                    $message ='';
                    $message .= '<b>🏆🏆🏆 Peringkat Bulan '.$data_bulan[$bulan_filter].' '.$tahun_filter.' 🏆🏆🏆</b>' .chr(10);
                    $message .= '----------------------------------------------------------' .chr(10);
                    $message .= 'Keadaan : '. Tanggal::LengkapHariPanjang(\Carbon\Carbon::now()) .chr(10);
                    $message .= '----------------------------------------------------------' .chr(10);
                    $i = 1;
                    foreach ($data as $item)
                    {
                        if ($i == 1)
                        {
                            $message .= '🥇 <b>'.$item->unit_nama.'</b>'.chr(10);
                            $message .= '🗳 Kegiatan : '.$item->keg_jml .chr(10);
                            $message .= '📂 Target : '. $item->keg_jml_target .chr(10);
                            $message .= '🎖 Poin : '. number_format($item->point_rata,2,".",",") .chr(10);
                            $message .= '----------------------------------------------------------' .chr(10);
                        }
                        elseif ($i == 2)
                        {
                            $message .= '🥈 <b>'.$item->unit_nama.'</b>' .chr(10);
                            $message .= '🗳 Kegiatan : '.$item->keg_jml .chr(10);
                            $message .= '📂 Target : '. $item->keg_jml_target .chr(10);
                            $message .= '🎖 Poin : '. number_format($item->point_rata,2,".",",") .chr(10);
                            $message .= '----------------------------------------------------------' .chr(10);
                        }
                        elseif ($i == 3)
                        {
                            $message .= '🥉 <b>'.$item->unit_nama.'</b>' .chr(10);
                            $message .= '🗳 Kegiatan : '.$item->keg_jml .chr(10);
                            $message .= '📂 Target : '. $item->keg_jml_target .chr(10);
                            $message .= '🎖 Poin : '. number_format($item->point_rata,2,".",",") .chr(10);
                            $message .= '----------------------------------------------------------' .chr(10);
                        }
                        else
                        {
                            $message .= '🎗 <b>'.$item->unit_nama.'</b>' .chr(10);
                            $message .= '🗳 Kegiatan : '.$item->keg_jml .chr(10);
                            $message .= '📂 Target : '. $item->keg_jml_target .chr(10);
                            $message .= '🎖 Poin : '. number_format($item->point_rata,2,".",",") .chr(10);
                            $message .= '----------------------------------------------------------' .chr(10);
                        }
                        $i++;
                    }

                    $reply_markup = Keyboard::make([
                        'keyboard' => $this->keyboard_peringkat,
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
                    $message .='<b>Token telegram valid.</b> akun telegram dan akun <b>SiRinjani</b> sudah terhubung. anda dapat menggunakan menu dibawah ini untuk terkoneksi dengan aplikasi <b>SiRinjani</b>.' . chr(10) .chr(10);
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
            elseif ($tg->command == 'UnbindAkun')
            {
                LogPosisi::create([
                    'user_tg' => $this->user_tg,
                    'chatid_tg' => $this->chat_id,
                    'command' => 'MenuDepan',
                    'msg_id' => $this->message_id,
                    'update_id' => $this->update_id,
                    'waktu_tg' => $this->waktu_kirim
                ]);
                $count = User::where('chatid_tg',$this->chat_id)->count();
                if ($count > 0)
                {
                    $data = User::where('chatid_tg',$this->chat_id)->first();
                    $data->user_tg = NULL;
                    $data->chatid_tg = NULL;
                    $data->token_tg = NULL;
                    $data->save();
                    LogPosisi::where('chatid_tg',$this->chat_id)->delete();
                    $message ='';
                    $message .='<b>akun sudah di unbind</b>. silakan generate ulang token telegram di Aplikasi <b>SiRinjani</b> kembali untuk menggunakan bot telegram ini' .chr(10);
                    $reply_markup = Keyboard::make([
                        'keyboard' => $this->keyboard_awal,
                        'resize_keyboard' => true,
                        'one_time_keyboard' => true
                    ]);
                }
                else
                {
                    $message ='';
                    $message .='<b>error waktu unbind</b>. silakan pilih menu' .chr(10);
                    $reply_markup = Keyboard::make([
                        'keyboard' => $this->keyboard_menu,
                        'resize_keyboard' => true,
                        'one_time_keyboard' => true
                    ]);
                }
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
                //command tidak dikenal
                LogPosisi::create([
                    'user_tg' => $this->user_tg,
                    'chatid_tg' => $this->chat_id,
                    'command' => 'MenuDepan',
                    'msg_id' => $this->message_id,
                    'update_id' => $this->update_id,
                    'waktu_tg' => $this->waktu_kirim
                ]);
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
            //command tidak dikenal
            LogPosisi::create([
                'user_tg' => $this->user_tg,
                'chatid_tg' => $this->chat_id,
                'command' => 'MenuDepan',
                'msg_id' => $this->message_id,
                'update_id' => $this->update_id,
                'waktu_tg' => $this->waktu_kirim
            ]);
            $message ='';
            $message .='Permintaan kamu tidak diproses 😁' . chr(10);

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

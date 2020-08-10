<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Session;
use App\User;
use App\Helpers\CommunityBPS;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function username()
    {
        return 'username';
    }
    public function getUserIpAddr()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_REAL_IP']))
            $ipaddress = $_SERVER['HTTP_X_REAL_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';    
        return $ipaddress;
     }
    public function showLoginForm()
    {
        return view('login.index');
    }
    
    protected function authenticate(Request $request)
    {
        $count = User::where([['username','=',$request->username],['aktif','=',1]])->count();
        if ($count>0)
        {
            $dd_cek_username = User::where([['username','=',$request->username],['aktif','=',1]])->first();
             //cek pake auth login
             $this->validate($request, [
                $this->username() => 'required|string',
                'password' => 'required|string',
            ]);
            
            if (auth()->attempt(['username' => $request->username, 'password' => $request->password, 'aktif' => 1])) {
                //JIKA BERHASIL, MAKA REDIRECT KE HALAMAN HOME
                return view('depan');
            }
            //JIKA SALAH, MAKA KEMBALI KE LOGIN DAN TAMPILKAN NOTIFIKASI 
            return redirect()->route('login')->withErrors('Password tidak benar!');
        }
        else {
            //tidak ada username
            //return view('login.index')->withError('Username tidak terdaftar');
            return redirect()->route('login')->withErrors('Username tidak terdaftar atau tidak aktif');
        }
        
    }
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }
    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request),
            $request->filled('remember')
        );
    }
    protected function credentials(Request $request)
    {
        //return $request->only($this->username(), 'password', 'aktif' => 1);
        return ['username' => $request->{$this->username()}, 'password' => $request->password, 'aktif' => 1];
    }    
    
    protected function sendFailedLoginResponse(Request $request)
    {
        $errors = [$this->username() => trans('auth.failed')];

        // Load user from database
        $user = User::where($this->username(), $request->{$this->username()})->first();

        // Check if user was successfully loaded, that the password matches
        // and active is not 1. If so, override the default error message.
        if ($user && \Hash::check($request->password, $user->password) && $user->aktif != 1) {
            $errors = [$this->username() => trans('auth.belumaktif')];
        }

        if ($request->expectsJson()) {
            return response()->json($errors, 422);
        }
        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors($errors);
    }
    
    public function authenticated(Request $request, $user)
    {
        //catat lastlogin dan ip   
        $user->lastlogin = Carbon::now()->toDateTimeString();
        $user->lastip = $this->getUserIpAddr();
        $user->save();        
    }
    
}

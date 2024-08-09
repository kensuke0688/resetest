<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
        use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected function redirectTo()
    {
        // もしユーザーが店舗代表者であれば、代表者用のダッシュボードにリダイレクト
        if (Auth::user()->isRepresentative()) {
            return '/admin.representative'; // ここに店舗代表者用のダッシュボードのパスを指定
        }

        // それ以外のユーザーは通常のリダイレクト先へ
        return '/home';
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}

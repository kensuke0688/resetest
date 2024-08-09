<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Userモデルを追加
use App\Models\Favorite;
use App\Models\Reservation; 
use App\Models\Review;

class UserController extends Controller
{
    public function myPage()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'ログインが必要です');
        }
        
        $userId = Auth::id();
        $reservations = $user->reservations()->orderBy('date', 'desc')->get(); // ユーザーの予約情報を日付の降順で取得
        $favorites = Favorite::where('user_id', $user->id)->with('restaurant')->get(); // お気に入り店舗を取得
        $reviews = Review::where('user_id', $userId)->with('restaurant')->get();

        return view('mypage', compact('reservations', 'favorites', 'reviews'));
    }

    public function makeAdmin($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return redirect()->back()->with('error', 'ユーザーが見つかりませんでした。');
        }

        $user->is_admin = true;
        $user->save();

        return redirect()->back()->with('success', 'ユーザーを管理者に設定しました。');
    }
}
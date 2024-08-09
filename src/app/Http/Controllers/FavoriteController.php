<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Favorite;
use App\Models\Restaurant;

class FavoriteController extends Controller
{
    public function store(Request $request)
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $restaurantId = $request->input('restaurant_id');
            
            $existingFavorite = Favorite::where('user_id', $userId)
                                        ->where('restaurant_id', $restaurantId)
                                        ->first();
            
            if ($existingFavorite) {
                return redirect()->route('restaurants.index')->with('info', 'この店舗は既にお気に入りに追加されています');
            }
            
            Favorite::create([
                'user_id' => $userId,
                'restaurant_id' => $restaurantId
            ]);
            
            return redirect()->route('restaurants.index')->with('success', 'お気に入りに追加しました');
        } else {
            return redirect()->route('login')->with('error', 'ログインが必要です');
        }
    }

    public function toggle(Request $request)
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $restaurantId = $request->input('restaurant_id');
            
            $existingFavorite = Favorite::where('user_id', $userId)
                                        ->where('restaurant_id', $restaurantId)
                                        ->first();
            
            if ($existingFavorite) {
                $existingFavorite->delete();
                return redirect()->back()->with('favorite', false);
            } else {
                Favorite::create([
                    'user_id' => $userId,
                    'restaurant_id' => $restaurantId
                ]);
                return redirect()->back()->with('favorite', true);
            }
        } else {
            return redirect()->route('login')->with('error', 'ログインが必要です');
        }
    }

    public function myPage()
    {
        $user = Auth::user();
        $reservations = $user->reservations;
        $favorites = Favorite::where('user_id', $user->id)->with('restaurant')->get();

        return view('mypage', compact('reservations', 'favorites'));
    }
}

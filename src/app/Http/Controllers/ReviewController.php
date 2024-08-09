<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'reservation_id' => 'required|integer',
            'restaurant_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:255',
        ]);

        // レビューを作成して保存
        $review = new Review();
        $review->user_id = Auth::id(); // ログインユーザのIDを設定
        $review->reservation_id = $request->reservation_id;
        $review->restaurant_id = $request->restaurant_id;
        $review->rating = $request->rating;
        $review->comment = $request->comment;
        $review->save();

        // 成功したらリダイレクトなどを行う
        return redirect()->back()->with('success', 'レビューを投稿しました！');
    }
}
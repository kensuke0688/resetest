<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.admin');
    }

    public function create()
    {
    // ここで必要なデータを取得
        $restaurants = Restaurant::all(); // 例: 全てのレストランを取得

        return view('admin.admin', compact('restaurants'));
    }
}

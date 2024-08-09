<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 
use App\Models\Representative;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Hash;

class RepresentativeController extends Controller
{
    public function dashboard()
    {
        return view('representative');
    }
    
    public function index()
    {
        $representatives = User::where('role', 'representative')->get();
        return view('admin.admin', compact('representatives'));
        return view('representative');
    }
    
    public function create()
    {
        return view('admin.create', compact('restaurants'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'store_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:representatives,email',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'store_name' => $request->store_name,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'representative', // ここでroleを設定
        ]);

        $validatedData['password'] = Hash::make($request->password);
        $validatedData['role'] = 'representative';


        return redirect()->route('representatives.index')
            ->with('success', '店舗代表者が作成されました');
    }
}
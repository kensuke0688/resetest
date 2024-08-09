<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\Area;
use App\Models\Genre;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function index(Request $request)
    {
        $query = Restaurant::query();

        if ($request->filled('area')) {
            $query->where('area_id', $request->area);
        }

        if ($request->filled('genre')) {
            $query->where('genre_id', $request->genre);
        }

        if ($request->filled('name')) {
            $query->where('name', 'LIKE', '%' . $request->name . '%');
        }

        $restaurants = $query->get();

        return view('restaurants.index', compact('restaurants'));
    }

    public function show($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        return view('restaurants.detail', compact('restaurant'));
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $image = $request->file('image');
        $imagePath = $request->file('image')->store('restaurant_images', 's3');

        $restaurantId = $request->input('restaurant_id');
        $restaurant = Restaurant::findOrFail($restaurantId);
        $restaurant->image_url = $imagePath;
        $restaurant->save();

        return back()->with('success', '画像がアップロードされました。');
    }

    public function create()
    {
        $areas = Area::all();
        $genres = Genre::all();
        return view('representative', compact('areas', 'genres'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'area_id' => 'required|exists:areas,id',
            'genre_id' => 'required|exists:genres,id',
            'image' => 'required|image|max:2048',
        ]);

        $restaurant = new Restaurant();
        $restaurant->name = $request->name;
        $restaurant->description = $request->description;
        $restaurant->area_id = $request->area_id;
        $restaurant->genre_id = $request->genre_id;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('restaurants', 'public');
            $restaurant->image = $path;
        }

        auth()->user()->restaurants()->save($restaurant);

        return redirect()->route('restaurants.index')->with('success', '店舗情報が作成されました。');
    }

    public function edit(Restaurant $restaurant)
    {
        $areas = Area::all();
        $genres = Genre::all();
        return view('representative', compact('restaurant', 'areas', 'genres'));
    }

    public function update(Request $request, Restaurant $restaurant)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'area_id' => 'required|exists:areas,id',
            'genre_id' => 'required|exists:genres,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $restaurant->name = $request->name;
        $restaurant->description = $request->description;
        $restaurant->area_id = $request->area_id;
        $restaurant->genre_id = $request->genre_id;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('restaurants', 'public');
            $restaurant->image = $path;
        }

        $restaurant->save();

        return redirect()->route('restaurants.index')->with('success', '店舗情報が更新されました。');
    }

    public function manage($id = null)
    {
        $restaurant = $id ? Restaurant::findOrFail($id) : null;
        $areas = Area::all();
        $genres = Genre::all();
        return view('representative', compact('restaurant', 'areas', 'genres'));
    }

    public function save(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'area_id' => 'required|integer',
            'genre_id' => 'required|integer',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->id) {
            $restaurant = Restaurant::findOrFail($request->id);
            $restaurant->update($validatedData);
        } else {
            Restaurant::create($validatedData);
        }

        return redirect()->route('restaurant.manage')->with('success', 'Restaurant saved successfully.');
    }

    public function storeImage(Request $request, $id)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $restaurant = Restaurant::find($id);
        if (!$restaurant) {
            return back()->with('error', 'Restaurant not found.');
        }

        $imageName = time().'.'.$request->image->extension();  
        $request->image->storeAs('public/images/restaurants', $imageName);

        $restaurant->image_url = 'storage/images/restaurants/' . $imageName;
        $restaurant->save();

        return back()->with('success', 'Image uploaded successfully.');
    }
}
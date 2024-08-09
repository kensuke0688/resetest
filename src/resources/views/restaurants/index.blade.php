@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="search-container">
    <form action="{{ route('restaurants.index') }}" method="GET" class="search-form">
        @csrf
        <label for="area"></label>
        <select id="area" name="area" onchange="this.form.submit()">
            <option value="">All area</option>
            <option value="1" {{ request('area') == '1' ? 'selected' : '' }}>東京</option>
            <option value="2" {{ request('area') == '2' ? 'selected' : '' }}>大阪</option>
            <option value="3" {{ request('area') == '3' ? 'selected' : '' }}>福岡</option>
        </select>

        <label for="genre"></label>
        <select id="genre" name="genre" onchange="this.form.submit()">
            <option value="">All genre</option>
            <option value="1" {{ request('genre') == '1' ? 'selected' : '' }}>焼肉</option>
            <option value="2" {{ request('genre') == '2' ? 'selected' : '' }}>寿司</option>
            <option value="3" {{ request('genre') == '3' ? 'selected' : '' }}>居酒屋</option>
            <option value="4" {{ request('genre') == '4' ? 'selected' : '' }}>イタリアン</option>
            <option value="5" {{ request('genre') == '5' ? 'selected' : '' }}>ラーメン</option>
        </select>

        <label for="name"></label>
        <input type="text" id="name" name="name" value="{{ request('name') }}">
    </form>
</div>

<div class="restaurant-list">
    @foreach ($restaurants as $restaurant)
        <div class="restaurant-item">
            <img src="{{ asset($restaurant->image_url) }}" alt="{{ $restaurant->name }}">
            <h2>{{ $restaurant->name }}</h2>
            <p class="area">#{{ $restaurant->area->name }}</p>
            <p class="genre">#{{ $restaurant->genre->name }}</p>
            @auth
                <form action="{{ route('restaurants.upload', $restaurant->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="image" class="form-control">
                    <button type="submit" class="btn btn-primary mt-2">Upload Image</button>
                </form>
            @endauth

            <div class="actions">
                <a href="{{ route('restaurants.show', ['id' => $restaurant->id]) }}" class="details-button">詳しく見る</a>
                @auth
                <a href="{{ route('payment.page') }}" class="payment-button">決済ページへ</a>
                @endauth
                
                <form action="{{ route('favorites.toggle') }}" method="POST">
                    @csrf
                    <input type="hidden" name="restaurant_id" value="{{ $restaurant->id }}">
                    <button type="submit" class="favorite-button">
                        <span class="heart {{ $restaurant->favorites()->where('user_id', Auth::id())->exists() ? 'red' : '' }}">
                            {{ $restaurant->favorites()->where('user_id', Auth::id())->exists() ? '❤️' : '♡' }}
                        </span>
                    </button>
                </form>
            </div>
        </div>
    @endforeach
</div>
@endsection
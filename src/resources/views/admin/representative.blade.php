@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/representative.css') }}">
@endsection

@section('content')
<div class="form-container">
    <h1>{{ isset($restaurant) ? '店舗情報更新' : '店舗情報作成' }}</h1>
    <form action="{{ route('restaurant.save') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($restaurant))
            <input type="hidden" name="id" value="{{ $restaurant->id }}">
        @endif
        <div class="form-group">
            <label for="name">店舗名</label>
            <input type="text" id="name" name="name" value="{{ $restaurant->name ?? '' }}" required>
        </div>
        <div class="form-group">
            <label for="description">説明</label>
            <textarea id="description" name="description" rows="4" required>{{ $restaurant->description ?? '' }}</textarea>
        </div>
        <div class="form-group">
            <label for="area_id">エリア</label>
            <select id="area_id" name="area_id" required>
                <option value="">エリアを選択してください</option>
                @foreach ($areas as $area)
                    <option value="{{ $area->id }}" {{ (isset($restaurant) && $restaurant->area_id == $area->id) ? 'selected' : '' }}>{{ $area->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="genre_id">ジャンル</label>
            <select id="genre_id" name="genre_id" required>
                <option value="">ジャンルを選択してください</option>
                @foreach ($genres as $genre)
                    <option value="{{ $genre->id }}" {{ (isset($restaurant) && $restaurant->genre_id == $genre->id) ? 'selected' : '' }}>{{ $genre->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="image">店舗画像</label>
            <input type="file" id="image" name="image" accept="image/*" {{ isset($restaurant) ? '' : 'required' }}>
            @if(isset($restaurant) && $restaurant->image)
                <img src="{{ asset('storage/' . $restaurant->image) }}" alt="Restaurant Image" width="100">
            @endif
        </div>
        <button type="submit">{{ isset($restaurant) ? '更新する' : '作成する' }}</button>
    </form>
</div>
@endsection
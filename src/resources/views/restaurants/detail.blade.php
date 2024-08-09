@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection
@section('content')
<div class="restaurant-details">
    <div class="restaurant-info-container">
        <div class="restaurant-info">
            <h1>{{ $restaurant->name }}</h1>
            <img src="{{ $restaurant->image_url }}" alt="{{ $restaurant->name }}">
            <p><strong></strong> {{ $restaurant->area->name }}</p>
            <p><strong></strong> {{ $restaurant->genre->name }}</p>
            <p>{{ $restaurant->description }}</p>
        </div>
    </div>
    <div class="reservation-form-container">
        @if (Auth::check())
            @livewire('reservation-form', ['restaurant' => $restaurant])
        @else
            <p>予約をするにはログインが必要です。<a href="{{ route('login') }}">ログイン</a>してください。</p>
        @endif
    </div>
</div>
@endsection

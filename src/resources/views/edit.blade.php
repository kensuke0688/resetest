@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/edit.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="restaurant-info-container">
        <div class="restaurant-info">
            <h1>{{ $restaurant->name }}</h1>
            <img src="{{ $restaurant->image_url }}" alt="{{ $restaurant->name }}">
            <p><strong>エリア:</strong> {{ $restaurant->area->name }}</p>
            <p><strong>ジャンル:</strong> {{ $restaurant->genre->name }}</p>
            <p>{{ $restaurant->description }}</p>
        </div>
    </div>

    <div class="reservation-form-container">
        <h2>予約変更</h2>
        <form action="{{ route('reservations.update', $reservation->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="date">日付</label>
                <input type="date" name="date" id="date" class="form-control" value="{{ old('date', $reservation->date) }}" required>
            </div>

            <div class="form-group">
                <label for="time">時間</label>
                <input type="time" name="time" id="time" class="form-control" value="{{ old('time', $reservation->time) }}" required>
            </div>

            <div class="form-group">
                <label for="guests">人数</label>
                <input type="number" name="guests" id="guests" class="form-control" value="{{ old('guests', $reservation->guests) }}" required>
            </div>

            <button type="submit" class="btn btn-primary">予約変更</button>
        </form>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection

@section('content')
<div class="container login-form-container">
    <div class="login-form">
        <h1 class="login-form__heading">お知らせメールを送る</h1>

        @if(session('success'))
            <div class="login-form__success-message">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.notification.send') }}" class="login-form__inner">
            @csrf
            <div class="login-form__group">
                <label for="subject" class="login-form__label">件名</label>
                <input type="text" class="login-form__input" id="subject" name="subject" required>
            </div>
            <div class="login-form__group">
                <label for="message" class="login-form__label">メッセージ</label>
                <textarea class="login-form__input" id="message" name="message" required></textarea>
            </div>
            <div class="login-form__btn-container">
                <button type="submit" class="login-form__btn">送信</button>
            </div>
        </form>
    </div>
</div>
@endsection
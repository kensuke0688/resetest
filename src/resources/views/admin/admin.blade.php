@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection

@section('content')
<div class="notification-button-container">
            <a href="{{ route('admin.notification') }}" class="btn btn-primary">お知らせメールを送る</a>

        </div>
<div class="container login-form-container">
    <div class="login-form">
        <h1 class="login-form__heading">Create store representative</h1>

        @if(session('success'))
            <div class="login-form__success-message">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('representatives.store') }}" class="login-form__inner">
            @csrf
            <div class="login-form__group">
                <label for="store_name" class="login-form__label">店舗名</label>
                <select class="login-form__input" id="store_name" name="store_name" required>
                    <option value="仙人">仙人</option>
                    <option value="牛助">牛助</option>
                    <option value="戦慄">戦慄</option>
                    <option value="ルーク">ルーク</option>
                    <option value="志摩">志摩</option>
                    <option value="香">香</option>
                    <option value="JJ">JJ</option>
                    <option value="ラーメン極み">ラーメン極み</option>
                    <option value="鳥雨">鳥雨</option>
                    <option value="築地色合">築地色合</option>
                    <option value="晴海">晴海</option>
                    <option value="三子">三子</option>
                    <option value="八戒">八戒</option>
                    <option value="福助">福助</option>
                    <option value="ラー北">ラー北</option>
                    <option value="翔">翔</option>
                    <option value="経緯">経緯</option>
                    <option value="漆">漆</option>
                    <option value="THE TOOL">THE TOOL</option>
                    <option value="木船">木船</option>
                </select>
            </div>
            <div class="login-form__group">
                <label for="name" class="login-form__label">代表者氏名</label>
                <input type="text" class="login-form__input" id="name" name="name" required>
            </div>
            <div class="login-form__group">
                <label for="email" class="login-form__label">メールアドレス</label>
                <input type="email" class="login-form__input" id="email" name="email" required>
            </div>
            <div class="login-form__group">
                <label for="password" class="login-form__label">パスワード</label>
                <input type="password" class="login-form__input" id="password" name="password" required>
            </div>
            <div class="login-form__btn-container">
                <button type="submit" class="login-form__btn">店舗代表者を追加する</button>
            </div>
        </form>
    </div>
</div>
@endsection
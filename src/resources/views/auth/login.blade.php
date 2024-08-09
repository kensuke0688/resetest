@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="login-form-container">
    <div class="login-form">
        <h2 class="login-form__heading content__heading">Login</h2>
        <div class="login-form__inner">
            <form class="login-form__form" action="/login" method="post">
                @csrf
                <div class="login-form__group">
                    <label class="login-form__label" for="email">
                        <i class="fas fa-envelope"></i>
                    </label>
                    <input class="login-form__input" type="email" name="email" id="email" placeholder="Email">
                    <p class="login-form__error-message">
                        @error('email')
                        {{ $message }}
                        @enderror
                    </p>
                </div>

                <div class="login-form__group">
                    <label class="login-form__label" for="password">
                        <i class="fas fa-lock"></i>
                    </label>
                    <input class="login-form__input" type="password" name="password" id="password" placeholder="Password">
                    <p class="login-form__error-message">
                        @error('password')
                        {{ $message }}
                        @enderror
                    </p>
                </div>
                <div class="login-form__btn-container">
                    <input class="login-form__btn btn" type="submit" value="ログイン">
                </div>
            </form>
        </div>
    </div>
</div>
@endsection('content')
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="register-form-container">
    <div class="register-form">
        <h2 class="register-form__heading content__heading">Registration</h2>
        <div class="register-form__inner">
            <form class="register-form__form" action="/register" method="post">
                @csrf
                <div class="register-form__group">
                    <label class="register-form__label" for="name">
                        <i class="fas fa-user"></i> <!-- 人間マークのアイコン -->
                    </label>
                    <input class="register-form__input" type="text" name="name" id="name" placeholder="Username">
                    <p class="register-form__error-message">
                        @error('name')
                        {{ $message }}
                        @enderror
                    </p>
                </div>

                <div class="register-form__group">
                    <label class="register-form__label" for="email">
                        <i class="fas fa-envelope"></i> <!-- メールマークのアイコン -->
                    </label>
                    <input class="register-form__input" type="email" name="email" id="email" placeholder="Email">
                    <p class="register-form__error-message">
                        @error('email')
                        {{ $message }}
                        @enderror
                    </p>
                </div>
                <div class="register-form__group">
                    <label class="register-form__label" for="password">
                        <i class="fas fa-lock"></i> <!-- 鍵マークのアイコン -->
                    </label>
                    <input class="register-form__input" type="password" name="password" id="password" placeholder="Password">
                    <p class="register-form__error-message">
                        @error('password')
                        {{ $message }}
                        @enderror
                    </p>
                </div>
                <div class="register-form__btn-container">
                    <input class="register-form__btn btn" type="submit" value="登録">
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

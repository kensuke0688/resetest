<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Rese</title>
    @yield('css')
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @livewireStyles
</head>

<body>
    <header class="header">
        <input id="drawer_input" class="drawer_hidden" type="checkbox">
        <label for="drawer_input" class="drawer_open"><span></span></label>
        <div class="logo">Rese</div>
        <nav class="nav_content">
            <ul class="nav_list">
                <li class="nav_item"><a href="{{ route('restaurants.index') }}">Home</a></li>
                @if(Auth::check())
                <!-- ログインしている場合 -->
                <li class="nav_item"><a href="{{ route('mypage') }}">Mypage</a></li>
                <li class="nav_item">
                    <form class="form" action="{{ route('logout') }}" method="post">
                        @csrf
                        <button type="submit" class="logout_button">Logout</button>
                    </form>
                </li>
                @else
                <!-- ログインしていない場合 -->
                <li class="nav_item"><a href="{{ route('register') }}">Registration</a></li>
                <li class="nav_item"><a href="{{ route('login') }}">Login</a></li>
                @endif
            </ul>
        </nav>
    </header>

    <div class="container">
        @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @livewireScripts
    @yield('scripts')
</body>

</html>
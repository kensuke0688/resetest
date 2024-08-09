@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    /* 追加のスタイルがあればここに記述 */
    .modal {
        display: none; /* 最初は非表示 */
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.5); /* オーバーレイの色と透明度 */
    }

    .modal-content {
        background-color: #fefefe;
        padding: 20px;
        left: 50%;
        top: 50%;
        border: 1px solid #888;
        width: 80%;
        max-width: 500px; /* モーダルの最大幅 */
        position: relative; /* 相対位置指定 */
        top: 50%; /* 上端を中央に */
        left: 50%; /* 左端を中央に */
        transform: translate(-50%, -50%); /* 中央配置 */
    }

    .modal-content h2{
        color: black;
    }

    .modal-content textarea{
        display: flex;
        flex-direction: row-reverse;
        font-size: 2em;
        justify-content: center;
        margin-bottom: 20px;
        width: 100%;
        min-height: 150px;
    }

    .modal-close {
        position: absolute;
        top: 20px;
        right: 20px;
        background-color: transparent;
        border: 1px solid black;
        font-size: 1.5rem;
        cursor: pointer;
        color: black;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: border-color 0.3s, color 0.3s;
    }

    .modal-close:hover,
    .modal-close:focus {
        color: black;
        text-decoration: none;
    }

    .star-rating {
        display: flex;
        flex-direction: row-reverse;
        font-size: 2em;
        justify-content: center;
        margin-bottom: 20px;
    }

    .star-rating input {
        display: none;
    }

    .star-rating label {
        color: #ccc;
        cursor: pointer;
    }

    .star-rating input:checked ~ label {
        color: #f5b301;
    }

    .star-rating label:hover,
    .star-rating label:hover ~ label {
        color: #f5b301;
    }

    /* チェックボックスを使ったモーダルのトリガー */
    .modal-toggle {
        display: none;
    }

    /* モーダルを開いた時の背景スタイル */
    .modal-toggle:checked + .modal {
        display: block;
    }

    /* モーダルの位置調整 */
    .modal-toggle:checked + .modal .modal-content {
        transform: translate(-50%, -50%);
        top: 50%;
        left: 50%;
    }

    .modal-open-button {
        cursor: pointer;
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 10px 80px;
        border-radius: 5px;
        color: #00008B;
        text-decoration: underline;
    }
</style>
@endsection

@section('content')
<div class="container">
    <!-- 予約情報セクション -->
    <div class="reservation-section">
        <h2>予約状況</h2>
        @if($reservations->isEmpty())
            <p>予約情報はありません。</p>
        @else
            <ul>
                @foreach($reservations as $index => $reservation)
                    <li>
                        <i class="fas fa-clock"></i> <strong>予約{{ $index + 1 }}</strong><br>
                        <strong>Shop</strong> {{ $reservation->restaurant->name }}<br>
                        <strong>Date</strong> {{ $reservation->date }}<br>
                        <strong>Time</strong> {{ $reservation->time }}<br>
                        <strong>Number</strong> {{ $reservation->guests }}人

                        <div class="qr-code">
                            <img src="{{ $reservation->qrCode }}" alt="QR Code">
                        </div>

                        <!-- 予約変更ボタン -->
                        <a href="{{ route('reservations.edit', ['id' => $reservation->id]) }}" class="edit-button">
                            <i class="fas fa-edit"></i> 予約変更
                        </a>
                        
                        <!-- 予約削除ボタン -->
                        <form action="{{ route('reservations.destroy', ['id' => $reservation->id]) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-button">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>

                        @if(strtotime($reservation->date . ' ' . $reservation->time) < strtotime(now()))
                        <label for="modal{{ $reservation->id }}" class="modal-open-button">レビューを投稿</label>
                        <input type="checkbox" id="modal{{ $reservation->id }}" class="modal-toggle">

                        <!-- モーダル内容 -->
                        <div class="modal">
                            <div class="modal-content">
                                <button for="modal{{ $reservation->id }}" class="modal-close">&times;</button>
                                <h2>レビューを投稿</h2>
                                <form action="{{ route('reviews.store') }}" method="POST" id="reviewForm">
                                    @csrf
                                    <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">
                                    <input type="hidden" name="restaurant_id" value="{{ $reservation->restaurant_id }}">

                                    <div class="star-rating">
                                        <input type="radio" id="5-stars-{{ $reservation->id }}" name="rating" value="5" />
                                        <label for="5-stars-{{ $reservation->id }}" class="star">&#9733;</label>
                                        <input type="radio" id="4-stars-{{ $reservation->id }}" name="rating" value="4" />
                                        <label for="4-stars-{{ $reservation->id }}" class="star">&#9733;</label>
                                        <input type="radio" id="3-stars-{{ $reservation->id }}" name="rating" value="3" />
                                        <label for="3-stars-{{ $reservation->id }}" class="star">&#9733;</label>
                                        <input type="radio" id="2-stars-{{ $reservation->id }}" name="rating" value="2" />
                                        <label for="2-stars-{{ $reservation->id }}" class="star">&#9733;</label>
                                        <input type="radio" id="1-star-{{ $reservation->id }}" name="rating" value="1" />
                                        <label for="1-star-{{ $reservation->id }}" class="star">&#9733;</label>
                                    </div>

                                    <div class="form-group">
                                        <label for="comment-{{ $reservation->id }}">コメント</label>
                                        <textarea name="comment" id="comment-{{ $reservation->id }}" class="form-control" rows="4"></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-primary">投稿する</button>
                                </form>
                            </div>
                        </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <!-- お気に入り店舗セクション -->
    <div class="favorite-section">
        <h2 class="content__title">{{ Auth::user()->name }}さん</h2>
        <h2>お気に入り店舗</h2>
        @if($favorites->isEmpty())
            <p>お気に入り店舗はありません。</p>
        @else
            <div class="favorite-restaurants">
                @foreach($favorites as $favorite)
                    @php
                        $restaurant = $favorite->restaurant;
                    @endphp
                    <div class="restaurant-item">
                        <img src="{{ $restaurant->image_url }}" alt="{{ $restaurant->name }}">
                        <h2>{{ $restaurant->name }}</h2>
                        <p class="location">#{{ $restaurant->area->name }}</p>
                        <p class="genre">#{{ $restaurant->genre->name }}</p>
                        <div class="actions">
                            <a href="{{ route('restaurants.show', ['id' => $restaurant->id]) }}" class="details-button">詳しく見る</a>
                            <form action="{{ route('favorites.store') }}" method="POST" id="favoriteForm{{ $restaurant->id }}">
                                @csrf
                                <input type="hidden" name="restaurant_id" value="{{ $restaurant->id }}">
                                <button type="button" class="favorite-button" onclick="toggleFavorite({{ $restaurant->id }})">
                                    <span class="heart {{ $restaurant->favorites()->where('user_id', Auth::id())->exists() ? 'red' : '' }}">
                                        {{ $restaurant->favorites()->where('user_id', Auth::id())->exists() ? '❤️' : '♡' }}
                                    </span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<script>

    


    // モーダルを閉じる関数
    function closeModal() {
        document.getElementById('modalToggle').checked = false;
    }
</script>
@endsection
<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RepresentativeController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\QRController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\NotificationController;

Auth::routes(['verify' => true]);

// レストランのルート
Route::get('/restaurants', [RestaurantController::class, 'index'])->name('restaurants.index');
Route::get('/restaurants/{id}', [RestaurantController::class, 'show'])->name('restaurants.show');
Route::get('/restaurants/search', [RestaurantController::class, 'index'])->name('restaurants.search');
Route::post('/restaurants/{id}/upload', [RestaurantController::class, 'storeImage'])->name('restaurants.upload');

// お気に入りのルート
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/favorites', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::post('/favorites/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::get('/mypage', [UserController::class, 'mypage'])->name('mypage');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/reservations/{id}', [ReservationController::class, 'show'])->name('reservations.show');
    Route::post('/reservations/store', [ReservationController::class, 'store'])->name('reservations.store');
    Route::delete('/reservations/{id}', [ReservationController::class, 'destroy'])->name('reservations.destroy');
    Route::get('/reservations/{id}/edit', [ReservationController::class, 'edit'])->name('reservations.edit');
    Route::put('/reservations/{id}', [ReservationController::class, 'update'])->name('reservations.update');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/qr-code/{text}', [QRController::class, 'generate'])->name('qr-code.generate');
});

// 完了ページのルート
Route::get('/done', function () {
    return view('done');
})->name('done');

// サンクスページのルート
Route::get('/thanks', function () {
    return view('thanks');
})->name('thanks');

// 登録フォームの表示ルート
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
// 登録処理のルート
Route::post('/register', [RegisterController::class, 'register']);

// ログインフォームの表示ルート
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

// ログイン処理のルート
Route::post('/login', [AuthController::class, 'login']);

// 不要なルートの削除
Route::get('/restaurants/{restaurant}', function (App\Models\Restaurant $restaurant) {
    return view('restaurants.detail', compact('restaurant'));
})->name('restaurant.show');

// 管理者機能のルート
Route::group(['middleware' => ['auth', 'admin']], function () {
    // 管理者のみがアクセスできるルート
    Route::get('/admin', [AdminController::class, 'index']);
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/admin/representatives', [RepresentativeController::class, 'index'])->name('representatives.index');
    Route::get('/admin/representatives/create', [RepresentativeController::class, 'create'])->name('representatives.create');
    Route::post('/admin/representatives', [RepresentativeController::class, 'store'])->name('representatives.store');
    Route::get('/admin/representatives/{representative}', [RepresentativeController::class, 'show'])->name('representatives.show');
    Route::get('/admin/representatives/{representative}/edit', [RepresentativeController::class, 'edit'])->name('representatives.edit');
    Route::put('/admin/representatives/{representative}', [RepresentativeController::class, 'update'])->name('representatives.update');
    Route::delete('/admin/representatives/{representative}', [RepresentativeController::class, 'destroy'])->name('representatives.destroy');
    Route::get('/admin/notification', [NotificationController::class, 'showForm'])->name('admin.notification');
    Route::post('/admin/notification', [NotificationController::class, 'sendNotification'])->name('admin.notification.send');
});

Route::group(['middleware' => ['auth', 'representative']], function () {
    // レストラン代表者のみがアクセスできるルート
    Route::get('/representative', [RepresentativeController::class, 'index'])->name('representative.index');
    Route::get('/', [RepresentativeController::class, 'dashboard'])->name('representative.dashboard');
});

Route::group(['middleware' => ['auth']], function () {
    // 認証されたユーザーがアクセスできるルート
    Route::get('/user', [UserController::class, 'index']);
});

Route::get('/payment', function () {
    return view('payment');
})->name('payment.page');

Route::post('/payment', [PaymentController::class, 'handlePayment'])->name('payment.handle');
Route::get('/payment', [PaymentController::class, 'showPaymentPage'])->name('payment.page');
Route::post('/payment', [PaymentController::class, 'handlePayment'])->name('payment.handle');


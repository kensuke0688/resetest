<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ReservationRequest;
use BaconQrCode\Writer;
use BaconQrCode\Renderer\Image\Png;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Renderer\ImageRenderer;
use App\Http\Controllers\QRController;


class ReservationController extends Controller
{
    public function store(ReservationRequest $request)
    {
        // ユーザーがログインしていない場合、ログインページにリダイレクト
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', '予約を作成するにはログインしてください。');
        }

        // バリデーションの実行
        $validatedData = $request->validate([
            'restaurant_id' => 'required',
            'date' => 'required|date',
            'time' => 'required',
            'guests' => 'required|integer|min:1',
        ]);

        // 予約データを保存する処理
        $reservation = new Reservation();
        $reservation->user_id = Auth::id();
        $reservation->restaurant_id = $validatedData['restaurant_id'];
        $reservation->date = $validatedData['date'];
        $reservation->time = $validatedData['time'];
        $reservation->guests = $validatedData['guests'];

        // 保存が成功したかどうかの確認
        if ($reservation->save()) {
            return redirect()->route('done')->with('success', '予約が完了しました。');
        } else {
            // 保存に失敗した場合のエラーハンドリング
            return back()->withErrors(['msg' => '予約の保存に失敗しました。']);
        }
    }

    public function show($id)
    {
        $reservation = Reservation::findOrFail($id);
        return view('reservations.store', compact('reservations'));
    }

    public function destroy($id, Request $request)
    {
        $reservation = Reservation::find($id);

        if ($reservation && $reservation->user_id == Auth::id()) {
            $reservation->delete();

            return redirect()->route('mypage')->with('success', '予約が正常に削除されました');
        }
    }

    public function edit($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation = Reservation::with('restaurant')->findOrFail($id);

        if(auth()->user()->id !== $reservation->user_id){
            abort(403);
        }

        $restaurant = $reservation->restaurant;

        return view('edit', compact('reservation', 'restaurant'));
    }

    public function update(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        if(auth()->user()->id !==$reservation->user_id){
            abort(403);
        }

        $request->validate([
            'date' => 'required|date',
            'time' => 'required',
            'guests' => 'required|integer|min:1',
        ]);

        $reservation->date = $request->input('date');
        $reservation->time = $request->input('time');
        $reservation->guests = $request->input('guests');
        $reservation->save();

        return redirect()->route('mypage')->with('success', '予約が更新されました');
    }

    public function mypage()
    {
        $reservations = auth()->user()->reservations;
        $favorites = auth()->user()->favorites;

        foreach ($reservations as $reservation) {
            $qrCodeData = url('/reservation/' . $reservation->id);
            $reservation->qrCode = $this->generateQrCode($qrCodeData);
        }

        return view('mypage', compact('reservations', 'favorites'));
    }
}

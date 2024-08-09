<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;

class ReservationForm extends Component
{
    public $restaurant;
    public $date;
    public $time;
    public $guests;

    protected $rules = [
        'date' => 'required|date',
        'time' => 'required',
        'guests' => 'required|integer|min:1',
    ];

    public function render()
    {
        return view('livewire.reservation-form');
    }

    public function submit()
    {
        $this->validate();

        $reservation = new Reservation();
        $reservation->user_id = Auth::id();
        $reservation->restaurant_id = $this->restaurant['id'];
        $reservation->date = $this->date;
        $reservation->time = $this->time;
        $reservation->guests = $this->guests;

        if ($reservation->save()) {
            session()->flash('message', '予約が完了しました。');
            return redirect(route('done'));
        } else {
            session()->flash('message', '予約の保存に失敗しました。');
        }
    }
}

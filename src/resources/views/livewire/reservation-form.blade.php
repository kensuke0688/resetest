<div class="reservation-form">
    <h2>予約</h2>
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif
    <form wire:submit.prevent="submit">
        @csrf
        <input type="hidden" name="restaurant_id" wire:model="restaurant.id">
        <div class="form-group">
            <label for="date"></label>
            <input type="date" id="date" wire:model="date" required>
            @error('date') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label for="time"></label>
            <input type="time" id="time" wire:model="time" step="1800" required>
            @error('time') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label for="guests"></label>
            <input type="number" id="guests" wire:model="guests" min="1" required>
            @error('guests') <span class="error">{{ $message }}</span> @enderror
        </div>
        <div class="reservation-info">
            <p>Shop <span>{{ $restaurant->name }}</span></p>
            <p>Date <span>{{ $date }}</span></p>
            <p>Time <span>{{ $time }}</span></p>
            <p>Number <span>{{ $guests }}</span></p>
        </div>
        <button type="submit">予約する</button>
    </form>
</div>

@extends('layout')

@section('title', 'Checkout | '.config('app.name'))

@section('content')
    <div class="intro">
        <p class="muted">Almost there</p>
        <h1>Checkout</h1>
        <p class="muted">This starter records a demo order; it does not collect payment details or process payments.</p>
    </div>

    <div class="panel narrow">
        <form method="POST" action="{{ route('checkout.store') }}">
            @csrf
            <div class="field">
                <label for="customer_name">Full name</label>
                <input id="customer_name" name="customer_name" value="{{ old('customer_name') }}" autocomplete="name" required maxlength="120">
                @error('customer_name') <p class="muted">{{ $message }}</p> @enderror
            </div>
            <div class="field">
                <label for="customer_email">Email address</label>
                <input id="customer_email" type="email" name="customer_email" value="{{ old('customer_email') }}" autocomplete="email" required maxlength="255">
                @error('customer_email') <p class="muted">{{ $message }}</p> @enderror
            </div>
            <div class="field">
                <label for="shipping_address">Shipping address</label>
                <textarea id="shipping_address" name="shipping_address" rows="4" autocomplete="street-address" required maxlength="1000">{{ old('shipping_address') }}</textarea>
                @error('shipping_address') <p class="muted">{{ $message }}</p> @enderror
            </div>
            <div class="summary">
                <div class="row"><strong>Order total</strong><strong>${{ number_format($total / 100, 2) }}</strong></div>
                <p class="muted" style="margin: 8px 0 18px">Taxes and shipping are not included.</p>
                <button type="submit">Place demo order</button>
                <a class="button secondary" href="{{ route('cart.index') }}">Back to cart</a>
            </div>
        </form>
    </div>
@endsection

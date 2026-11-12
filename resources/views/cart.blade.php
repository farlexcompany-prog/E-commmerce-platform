@extends('layout')

@section('title', 'Your cart | '.config('app.name'))

@section('content')
    <div class="intro">
        <p class="muted">Your selection</p>
        <h1>Your cart</h1>
    </div>

    @if ($items->isEmpty())
        <section class="panel">
            <p>Your cart is empty.</p>
            <a class="button" href="{{ route('store') }}">Continue shopping</a>
        </section>
    @else
        <div class="stack">
            @foreach ($items as $item)
                <section class="panel cart-item">
                    <div>
                        <h2>{{ $item['product']->name }}</h2>
                        <span class="muted">${{ number_format((float) $item['product']->price, 2) }} each</span>
                    </div>
                    <form class="inline" method="POST" action="{{ route('cart.update', $item['product']) }}">
                        @csrf
                        @method('PATCH')
                        <label class="muted" for="quantity-{{ $item['product']->id }}">Qty</label>
                        <input id="quantity-{{ $item['product']->id }}" type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="99" required>
                        <button class="button secondary" type="submit">Update</button>
                    </form>
                    <div class="row">
                        <strong>${{ number_format($item['line_total'] / 100, 2) }}</strong>
                        <form method="POST" action="{{ route('cart.destroy', $item['product']) }}">
                            @csrf
                            @method('DELETE')
                            <button class="button danger" type="submit">Remove</button>
                        </form>
                    </div>
                </section>
            @endforeach
        </div>
        <section class="panel summary">
            <div class="row"><strong>Subtotal</strong><strong>${{ number_format($total / 100, 2) }}</strong></div>
            <p class="muted" style="margin: 8px 0 18px">Shipping and taxes are not included in this demo.</p>
            <div class="row">
                <a class="button secondary" href="{{ route('store') }}">Continue shopping</a>
                <a class="button" href="{{ route('checkout') }}">Continue to checkout</a>
            </div>
        </section>
    @endif
@endsection

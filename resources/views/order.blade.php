@extends('layout')

@section('title', 'Order '.$order->order_number.' | '.config('app.name'))

@section('content')
    <div class="intro">
        <p class="muted">Order confirmation</p>
        <h1>Thanks for your order.</h1>
        <p>Your order number is <strong>{{ $order->order_number }}</strong>.</p>
    </div>

    <section class="panel stack">
        @foreach ($order->items as $item)
            <div class="row">
                <span>{{ $item->product_name }} <span class="muted">× {{ $item->quantity }}</span></span>
                <strong>${{ number_format((float) $item->line_total, 2) }}</strong>
            </div>
        @endforeach
        <div class="row summary"><strong>Total</strong><strong>${{ number_format((float) $order->total, 2) }}</strong></div>
        <p class="muted">Status: {{ ucfirst($order->status) }}. This demo order has not been paid or shipped.</p>
        <a class="button" href="{{ route('store') }}">Continue shopping</a>
    </section>
@endsection

@extends('layout')

@section('title', 'Order '.$order->order_number.' | '.config('store.name'))

@section('content')
    <div class="container page-shell">
        <nav class="breadcrumbs" aria-label="Breadcrumb"><a href="{{ route('store') }}">Home</a><span aria-hidden="true">/</span><span>Order status</span></nav>
        <div class="panel">
            <p class="eyebrow">{{ $pageContent->value('order', 'confirmation', 'eyebrow', 'Demo order reference') }}</p>
            <h1>Order {{ $order->order_number }}</h1>
            <p>{{ $pageContent->value('order', 'confirmation', 'description', 'This order is recorded as a demo only. It has not been paid, reviewed or dispatched.') }}</p>
            <div class="order-timeline" aria-label="Order progress">
                <div class="timeline-step is-current"><strong>Order recorded</strong><span>Demo record created</span></div>
                <div class="timeline-step {{ $order->status === 'pending_pharmacist_review' ? 'is-current' : '' }}"><strong>{{ $order->status === 'pending_pharmacist_review' ? 'Pharmacy review pending' : 'Pharmacy review' }}</strong><span>Not connected</span></div>
                <div class="timeline-step"><strong>Payment & dispatch</strong><span>Not connected</span></div>
            </div>
            @if ($order->status === 'pending_pharmacist_review')
                <div class="notice">Prescription upload received to private storage. No pharmacist has reviewed it in this demo.</div>
            @endif
            <section class="subsection">
                <h2>{{ $pageContent->value('order', 'confirmation', 'items_title', 'Items') }}</h2>
                @foreach ($order->items as $item)
                    <div class="summary-line"><span>{{ $item->product_name }} × {{ $item->quantity }}</span><strong>{{ config('store.currency_symbol') }}{{ number_format((float) $item->line_total, 2) }}</strong></div>
                @endforeach
                <div class="summary-line"><span>MRP total</span><span>{{ config('store.currency_symbol') }}{{ number_format((float) $order->mrp_total, 2) }}</span></div>
                <div class="summary-line"><span>Savings</span><span>− {{ config('store.currency_symbol') }}{{ number_format((float) $order->discount, 2) }}</span></div>
                <div class="summary-line"><span>Delivery</span><span>{{ (float) $order->delivery_fee === 0.0 ? 'FREE' : config('store.currency_symbol').number_format((float) $order->delivery_fee, 2) }}</span></div>
                <div class="summary-line summary-line--total"><span>Demo total</span><strong>{{ config('store.currency_symbol') }}{{ number_format((float) $order->total, 2) }}</strong></div>
            </section>
            <a class="button" href="{{ route('shop') }}">{{ $pageContent->value('order', 'confirmation', 'continue_button', 'Continue browsing') }}</a>
        </div>
    </div>
@endsection

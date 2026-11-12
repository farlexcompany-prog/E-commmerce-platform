@extends('admin.layout')

@section('title', 'Order '.$order->order_number)

@section('content')
    <nav class="admin-breadcrumbs" aria-label="Breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Orders</a><span aria-hidden="true">/</span><span>{{ $order->order_number }}</span>
    </nav>

    <div class="admin-page-heading admin-order-heading">
        <div>
            <p class="eyebrow">Order details</p>
            <h1>{{ $order->order_number }}</h1>
            <p>Placed {{ $order->created_at->format('d M Y, h:i A') }}</p>
        </div>
        <span class="admin-status admin-status--{{ \Illuminate\Support\Str::slug($order->status) }}">{{ \Illuminate\Support\Str::headline($order->status) }}</span>
    </div>

    <div class="admin-order-layout">
        <section class="admin-panel admin-order-products">
            <div class="admin-panel-heading">
                <h2>Products ordered</h2>
                <span>{{ $order->items->sum('quantity') }} item(s)</span>
            </div>
            <div class="admin-table-scroll">
                <table class="admin-orders-table">
                    <thead>
                        <tr><th scope="col">Product</th><th scope="col">Quantity</th><th scope="col">Unit price</th><th scope="col">Line total</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr>
                                <td><strong>{{ $item->product_name }}</strong></td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ config('store.currency_symbol') }}{{ number_format((float) $item->unit_price, 2) }}</td>
                                <td class="admin-amount">{{ config('store.currency_symbol') }}{{ number_format((float) $item->line_total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <dl class="admin-order-totals">
                <div><dt>MRP total</dt><dd>{{ config('store.currency_symbol') }}{{ number_format((float) $order->mrp_total, 2) }}</dd></div>
                <div><dt>Discount</dt><dd>−{{ config('store.currency_symbol') }}{{ number_format((float) $order->discount, 2) }}</dd></div>
                <div><dt>Delivery fee</dt><dd>{{ config('store.currency_symbol') }}{{ number_format((float) $order->delivery_fee, 2) }}</dd></div>
                <div class="admin-order-grand-total"><dt>Order total</dt><dd>{{ config('store.currency_symbol') }}{{ number_format((float) $order->total, 2) }}</dd></div>
            </dl>
        </section>

        <div class="admin-order-side">
            <section class="admin-panel admin-detail-card">
                <h2>Customer</h2>
                <dl>
                    <div><dt>Name</dt><dd>{{ $order->customer_name }}</dd></div>
                    <div><dt>Email</dt><dd><a href="mailto:{{ $order->customer_email }}">{{ $order->customer_email }}</a></dd></div>
                    @if ($order->customer_phone)<div><dt>Phone</dt><dd><a href="tel:{{ $order->customer_phone }}">{{ $order->customer_phone }}</a></dd></div>@endif
                </dl>
            </section>

            <section class="admin-panel admin-detail-card">
                <h2>Delivery</h2>
                <address>{{ $order->shipping_address }}@if ($order->pincode)<br>{{ $order->pincode }}@endif</address>
            </section>

            <section class="admin-panel admin-detail-card">
                <h2>Order information</h2>
                <dl>
                    <div><dt>Payment method</dt><dd>{{ \Illuminate\Support\Str::headline($order->payment_method ?? 'Not recorded') }}</dd></div>
                    <div><dt>Coupon</dt><dd>{{ $order->coupon_code ?: 'None' }}</dd></div>
                    <div><dt>Prescription</dt><dd>{{ $order->prescription_path ? 'Uploaded; pharmacist review is not configured here.' : 'Not attached' }}</dd></div>
                </dl>
                <p class="admin-demo-note">Payment and fulfilment are not connected in this demo.</p>
            </section>
        </div>
    </div>
@endsection

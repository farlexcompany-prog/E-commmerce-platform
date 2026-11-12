@extends('admin.layout')

@section('title', 'Orders')

@section('content')
    <div class="admin-page-heading">
        <div>
            <p class="eyebrow">Order management</p>
            <h1>Customer orders</h1>
            <p>Search who ordered, what they bought, and when the order was placed.</p>
        </div>
    </div>

    <section class="admin-stat-grid" aria-label="Order summary">
        <article class="admin-stat-card"><span>All orders</span><strong>{{ number_format($stats['total']) }}</strong></article>
        <article class="admin-stat-card"><span>Placed today</span><strong>{{ number_format($stats['today']) }}</strong></article>
        <article class="admin-stat-card"><span>Awaiting action</span><strong>{{ number_format($stats['attention']) }}</strong></article>
        <article class="admin-stat-card"><span>Demo order value</span><strong>{{ config('store.currency_symbol') }}{{ number_format((float) $stats['value'], 2) }}</strong><small>Payments are not connected.</small></article>
    </section>

    <section class="admin-panel">
        <form class="admin-filters" method="GET" action="{{ route('admin.dashboard') }}">
            <div class="admin-filter-search">
                <label for="order-search">Search orders</label>
                <input id="order-search" name="q" type="search" value="{{ $filters['q'] ?? '' }}" placeholder="Order no., customer, phone, email, or product">
            </div>
            <div>
                <label for="order-status">Status</label>
                <select id="order-status" name="status">
                    <option value="">All statuses</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ \Illuminate\Support\Str::headline($status) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="order-from">From</label>
                <input id="order-from" name="from" type="date" value="{{ $filters['from'] ?? '' }}">
            </div>
            <div>
                <label for="order-to">To</label>
                <input id="order-to" name="to" type="date" value="{{ $filters['to'] ?? '' }}">
            </div>
            <div class="admin-filter-actions">
                <button class="admin-primary-button" type="submit">Apply filters</button>
                <a href="{{ route('admin.dashboard') }}">Clear</a>
            </div>
        </form>

        @if ($errors->any())
            <div class="admin-form-error admin-filter-error" role="alert">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="admin-table-scroll">
            <table class="admin-orders-table">
                <thead>
                    <tr>
                        <th scope="col">Order</th>
                        <th scope="col">Customer</th>
                        <th scope="col">Products</th>
                        <th scope="col">Total</th>
                        <th scope="col">Status</th>
                        <th scope="col">Placed</th>
                        <th scope="col"><span class="sr-only">Details</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td><a class="admin-order-number" href="{{ route('admin.orders.show', $order->order_number) }}">{{ $order->order_number }}</a></td>
                            <td>
                                <strong>{{ $order->customer_name }}</strong>
                                <a class="admin-customer-email" href="mailto:{{ $order->customer_email }}">{{ $order->customer_email }}</a>
                                @if ($order->customer_phone)<span class="admin-customer-phone">{{ $order->customer_phone }}</span>@endif
                            </td>
                            <td>
                                <ul class="admin-item-summary">
                                    @foreach ($order->items as $item)
                                        <li>{{ $item->product_name }} <span>× {{ $item->quantity }}</span></li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="admin-amount">{{ config('store.currency_symbol') }}{{ number_format((float) $order->total, 2) }}</td>
                            <td><span class="admin-status admin-status--{{ \Illuminate\Support\Str::slug($order->status) }}">{{ \Illuminate\Support\Str::headline($order->status) }}</span></td>
                            <td>{{ $order->created_at->format('d M Y, h:i A') }}</td>
                            <td><a class="admin-view-link" href="{{ route('admin.orders.show', $order->order_number) }}">View</a></td>
                        </tr>
                    @empty
                        <tr><td class="admin-empty-cell" colspan="7">No orders match these filters.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="admin-pagination">
            <span>Showing {{ $orders->firstItem() ?? 0 }}–{{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }}</span>
            <div>
                @if ($orders->onFirstPage())
                    <span class="is-disabled">Previous</span>
                @else
                    <a href="{{ $orders->previousPageUrl() }}">Previous</a>
                @endif
                @if ($orders->hasMorePages())
                    <a href="{{ $orders->nextPageUrl() }}">Next</a>
                @else
                    <span class="is-disabled">Next</span>
                @endif
            </div>
        </div>
    </section>
@endsection

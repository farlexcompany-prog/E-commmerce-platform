@extends('admin.layout')

@section('title', 'Orders')
@section('section_title', 'Orders')

@section('content')
    <div class="admin-page-heading">
        <div>
            <p class="eyebrow">Sales</p>
            <h1>Orders</h1>
            <p>View customer orders, payment details, delivery locations, and ordered products.</p>
        </div>
        <a class="admin-secondary-button admin-order-export" href="{{ route('admin.orders.export', request()->query()) }}">Export</a>
    </div>

    <section class="admin-panel">
        <form class="admin-orders-toolbar" method="GET" action="{{ request()->routeIs('admin.sales.orders') ? route('admin.sales.orders') : route('admin.dashboard') }}">
            <div class="admin-order-search">
                <label class="sr-only" for="order-search">Search orders</label>
                <input id="order-search" name="q" type="search" value="{{ $filters['q'] ?? '' }}" placeholder="Search orders">
                <button type="submit" aria-label="Search orders">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.8"/><path d="m16 16 4.5 4.5"/></svg>
                </button>
            </div>
            <span class="admin-order-results">{{ number_format($orders->total()) }} Results</span>
            <div class="admin-orders-toolbar-controls">
                <details class="admin-order-filter">
                    <summary>
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 5h16l-6.5 7.5v5L10.5 19v-6.5L4 5Z"/></svg>
                        Filter
                    </summary>
                    <div class="admin-order-filter-panel">
                        <label for="order-status">Status</label>
                        <select id="order-status" name="status">
                            <option value="">All statuses</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ \Illuminate\Support\Str::headline($status) }}</option>
                            @endforeach
                        </select>
                        <label for="order-from">From</label>
                        <input id="order-from" name="from" type="date" value="{{ $filters['from'] ?? '' }}">
                        <label for="order-to">To</label>
                        <input id="order-to" name="to" type="date" value="{{ $filters['to'] ?? '' }}">
                        <button class="admin-primary-button" type="submit">Apply filters</button>
                    </div>
                </details>
                <label class="admin-order-per-page" for="orders-per-page">
                    <select id="orders-per-page" name="per_page">
                        @foreach ([10, 25, 50] as $pageSize)
                            <option value="{{ $pageSize }}" @selected((int) ($filters['per_page'] ?? 10) === $pageSize)>{{ $pageSize }}</option>
                        @endforeach
                    </select>
                    <span>Per Page</span>
                </label>
                <button class="admin-order-apply" type="submit">Go</button>
                <span class="admin-order-page-count">{{ $orders->currentPage() }} of {{ $orders->lastPage() }}</span>
                @if ($orders->onFirstPage())
                    <span class="admin-order-page-arrow is-disabled" aria-label="Previous page">‹</span>
                @else
                    <a class="admin-order-page-arrow" href="{{ $orders->previousPageUrl() }}" aria-label="Previous page">‹</a>
                @endif
                @if ($orders->hasMorePages())
                    <a class="admin-order-page-arrow" href="{{ $orders->nextPageUrl() }}" aria-label="Next page">›</a>
                @else
                    <span class="admin-order-page-arrow is-disabled" aria-label="Next page">›</span>
                @endif
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
            <table class="admin-orders-table admin-sales-orders-table">
                <thead>
                    <tr>
                        <th scope="col">Order ID / Date / Status</th>
                        <th scope="col">Grand Total / Pay Via / Channel</th>
                        <th scope="col">Customer / Email / Location</th>
                        <th scope="col">Items</th>
                        <th scope="col"><span class="sr-only">Details</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td>
                                <a class="admin-order-number" href="{{ route('admin.orders.show', $order->order_number) }}">#{{ $order->order_number }}</a>
                                <span class="admin-order-date">{{ $order->created_at->format('Y-m-d H:i:s') }}</span>
                                <span class="admin-status admin-status--{{ \Illuminate\Support\Str::slug($order->status) }}">{{ \Illuminate\Support\Str::headline($order->status) }}</span>
                            </td>
                            <td class="admin-order-payment">
                                <strong>{{ config('store.currency_symbol') }}{{ number_format((float) $order->total, 2) }}</strong>
                                <span>Pay by · {{ \Illuminate\Support\Str::headline($order->payment_method ?? 'Not recorded') }}</span>
                                <span>Farlex Store</span>
                            </td>
                            <td class="admin-order-customer">
                                <strong>{{ $order->customer_name }}</strong>
                                <a href="mailto:{{ $order->customer_email }}">{{ $order->customer_email }}</a>
                                <span>{{ \Illuminate\Support\Str::limit(str_replace(["\r", "\n"], ' ', $order->shipping_address), 36) }}@if ($order->pincode), {{ $order->pincode }}@endif</span>
                            </td>
                            <td>
                                <div class="admin-order-item-thumbnails">
                                    @foreach ($order->items->take(3) as $item)
                                        <div class="admin-order-item-thumb" title="{{ $item->product_name }} × {{ $item->quantity }}">
                                            @if ($item->product)
                                                <x-product-art :product="$item->product" size="small" />
                                            @else
                                                <span class="admin-order-item-fallback">{{ \Illuminate\Support\Str::limit($item->product_name, 12) }}</span>
                                            @endif
                                            <span class="admin-order-item-quantity">{{ $item->quantity }}</span>
                                        </div>
                                    @endforeach
                                    @if ($order->items->count() > 3)
                                        <span class="admin-order-item-more">+{{ $order->items->count() - 3 }}</span>
                                    @endif
                                </div>
                                <span class="admin-order-item-count">{{ $order->items->count() }} product(s)</span>
                            </td>
                            <td><a class="admin-order-open" href="{{ route('admin.orders.show', $order->order_number) }}" aria-label="Open order {{ $order->order_number }}">›</a></td>
                        </tr>
                    @empty
                        <tr><td class="admin-empty-cell" colspan="5">No orders match these filters.</td></tr>
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

@extends('admin.layout')

@section('title', 'Products')
@section('section_title', 'Products')

@section('content')
    <div class="admin-breadcrumbs">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span aria-hidden="true">/</span>
        <span>Catalog</span>
        <span aria-hidden="true">/</span>
        <span>Products</span>
    </div>

    <div class="admin-page-heading">
        <div>
            <p class="eyebrow">Catalog management</p>
            <h1>Products</h1>
            <p>Browse, search, and review the products in your storefront catalog.</p>
        </div>
        <div class="admin-product-heading-actions">
            <span class="admin-product-count">{{ number_format($products->total()) }} products</span>
            <a class="admin-primary-button" href="{{ route('admin.catalog.products.create') }}">Add product</a>
        </div>
    </div>

    <section class="admin-stat-grid" aria-label="Product summary">
        <article class="admin-stat-card"><span>Total products</span><strong>{{ number_format($stats['total']) }}</strong></article>
        <article class="admin-stat-card"><span>Active</span><strong>{{ number_format($stats['active']) }}</strong></article>
        <article class="admin-stat-card"><span>Rx required</span><strong>{{ number_format($stats['rx']) }}</strong></article>
        <article class="admin-stat-card"><span>Low stock (1–5)</span><strong>{{ number_format($stats['low_stock']) }}</strong></article>
    </section>

    <section class="admin-panel">
        <form class="admin-filters admin-product-filters" method="GET" action="{{ route('admin.catalog.products') }}">
            <div class="admin-filter-search">
                <label for="product-search">Search products</label>
                <input id="product-search" name="q" type="search" value="{{ $filters['q'] ?? '' }}" placeholder="Name, brand, manufacturer, or category">
            </div>
            <div>
                <label for="product-category">Category</label>
                <select id="product-category" name="category">
                    <option value="">All categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category }}" @selected(($filters['category'] ?? '') === $category)>{{ $category }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="product-status">Status</label>
                <select id="product-status" name="status">
                    <option value="">All statuses</option>
                    <option value="active" @selected(($filters['status'] ?? '') === 'active')>Active</option>
                    <option value="inactive" @selected(($filters['status'] ?? '') === 'inactive')>Inactive</option>
                </select>
            </div>
            <div>
                <label for="product-rx">Prescription</label>
                <select id="product-rx" name="rx">
                    <option value="">All products</option>
                    <option value="required" @selected(($filters['rx'] ?? '') === 'required')>Rx required</option>
                    <option value="otc" @selected(($filters['rx'] ?? '') === 'otc')>OTC</option>
                </select>
            </div>
            <div>
                <label for="product-stock">Inventory</label>
                <select id="product-stock" name="stock">
                    <option value="">All inventory</option>
                    <option value="available" @selected(($filters['stock'] ?? '') === 'available')>In stock</option>
                    <option value="low" @selected(($filters['stock'] ?? '') === 'low')>Low stock</option>
                    <option value="out" @selected(($filters['stock'] ?? '') === 'out')>Out of stock</option>
                </select>
            </div>
            <div>
                <label for="product-sort">Sort by</label>
                <select id="product-sort" name="sort">
                    <option value="newest" @selected(($filters['sort'] ?? 'newest') === 'newest')>Recently updated</option>
                    <option value="name" @selected(($filters['sort'] ?? '') === 'name')>Name</option>
                    <option value="price-low" @selected(($filters['sort'] ?? '') === 'price-low')>Price: low to high</option>
                    <option value="price-high" @selected(($filters['sort'] ?? '') === 'price-high')>Price: high to low</option>
                    <option value="stock" @selected(($filters['sort'] ?? '') === 'stock')>Stock: low to high</option>
                </select>
            </div>
            <div class="admin-filter-actions">
                <button class="admin-primary-button" type="submit">Apply filters</button>
                <a href="{{ route('admin.catalog.products') }}">Clear</a>
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
            <table class="admin-orders-table admin-product-table">
                <thead>
                    <tr>
                        <th scope="col">Product</th>
                        <th scope="col">Category</th>
                        <th scope="col">Form</th>
                        <th scope="col">Price</th>
                        <th scope="col">Stock</th>
                        <th scope="col">Prescription</th>
                        <th scope="col">Status</th>
                        <th scope="col">Updated</th>
                        <th scope="col"><span class="sr-only">Action</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td>
                                <div class="admin-product-summary">
                                    <div class="admin-product-thumb"><x-product-art :product="$product" size="small" /></div>
                                    <div class="admin-product-info">
                                        <strong>{{ $product->name }}</strong>
                                        <span>{{ $product->brand }} · ID #{{ $product->id }}</span>
                                        @if ($product->pack_size)<small>{{ $product->pack_size }}</small>@endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $product->category }}</td>
                            <td>{{ $product->dosage_form }}</td>
                            <td class="admin-amount">
                                {{ config('store.currency_symbol') }}{{ number_format((float) $product->price, 2) }}
                                @if ($product->mrp && (float) $product->mrp > (float) $product->price)
                                    <del class="admin-product-mrp">{{ config('store.currency_symbol') }}{{ number_format((float) $product->mrp, 2) }}</del>
                                @endif
                            </td>
                            <td><span class="admin-stock-badge {{ $product->stock === 0 ? 'is-out' : ($product->stock <= 5 ? 'is-low' : 'is-available') }}">{{ number_format($product->stock) }} units</span></td>
                            <td><span class="admin-rx-badge {{ $product->rx_required ? 'is-required' : '' }}">{{ $product->rx_required ? 'Rx required' : 'OTC' }}</span></td>
                            <td><span class="admin-status {{ $product->active ? 'admin-status--delivered' : 'admin-status--cancelled' }}">{{ $product->active ? 'Active' : 'Inactive' }}</span></td>
                            <td>{{ $product->updated_at?->format('d M Y') }}</td>
                            <td>
                                <a class="admin-view-link" href="{{ route('admin.catalog.products.edit', $product) }}">Edit</a>
                                @if ($product->active)
                                    <span aria-hidden="true"> · </span>
                                    <a class="admin-view-link" href="{{ route('products.show', $product) }}" target="_blank" rel="noopener">View</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td class="admin-empty-cell" colspan="9">No products match these filters.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="admin-pagination">
            <span>Showing {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products</span>
            <div>
                @if ($products->onFirstPage())
                    <span class="is-disabled">Previous</span>
                @else
                    <a href="{{ $products->previousPageUrl() }}">Previous</a>
                @endif
                @if ($products->hasMorePages())
                    <a href="{{ $products->nextPageUrl() }}">Next</a>
                @else
                    <span class="is-disabled">Next</span>
                @endif
            </div>
        </div>
    </section>
@endsection

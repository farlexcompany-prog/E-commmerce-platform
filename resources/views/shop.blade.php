@extends('layout')

@section('title', 'Shop health essentials | '.config('store.name'))
@section('meta_description', 'Browse demo pharmacy products by category, dosage form and prescription status.')

@section('content')
    <div class="container page-shell">
        <nav class="breadcrumbs" aria-label="Breadcrumb"><a href="{{ route('store') }}">Home</a><span aria-hidden="true">/</span><span>Shop</span></nav>
        <header class="page-heading">
            <p class="eyebrow">Demo catalogue · India</p>
            <h1>{{ $filters['category'] ?? 'Shop health essentials' }}</h1>
            <p>These are sample product records with placeholder prices and packaging. Replace all catalogue details with approved manufacturer and pharmacy information.</p>
        </header>

        <button class="button button--outline filter-toggle" type="button" data-filter-toggle aria-controls="shop-filters" aria-expanded="false">Filters</button>
        <div class="shop-layout">
            <aside class="filter-panel" id="shop-filters" aria-label="Product filters">
                <h2>Filter products</h2>
                <form method="GET" action="{{ route('shop') }}">
                    @if (! empty($filters['q'])) <input type="hidden" name="q" value="{{ $filters['q'] }}"> @endif
                    <div class="filter-group">
                        <label for="filter-category">Category</label>
                        <select id="filter-category" name="category">
                            <option value="">All categories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category }}" @selected(($filters['category'] ?? '') === $category)>{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="filter-brand">Brand</label>
                        <select id="filter-brand" name="brand">
                            <option value="">All demo brands</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand }}" @selected(($filters['brand'] ?? '') === $brand)>{{ $brand }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="filter-price">Maximum price (₹)</label>
                        <input id="filter-price" type="number" name="max_price" min="0" max="100000" step="50" value="{{ $filters['max_price'] ?? '' }}" placeholder="No limit">
                    </div>
                    <div class="filter-group">
                        <label for="filter-discount">Minimum discount</label>
                        <select id="filter-discount" name="min_discount">
                            <option value="">Any discount</option>
                            @foreach ([5, 10, 15, 20] as $discount)
                                <option value="{{ $discount }}" @selected((string) ($filters['min_discount'] ?? '') === (string) $discount)>{{ $discount }}% or more</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="filter-rx">Prescription status</label>
                        <select id="filter-rx" name="rx">
                            <option value="">All products</option>
                            <option value="otc" @selected(($filters['rx'] ?? '') === 'otc')>OTC</option>
                            <option value="rx" @selected(($filters['rx'] ?? '') === 'rx')>Rx required</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="filter-form">Dosage form</label>
                        <select id="filter-form" name="form">
                            <option value="">All forms</option>
                            @foreach ($forms as $form)
                                <option value="{{ $form }}" @selected(($filters['form'] ?? '') === $form)>{{ $form }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="filter-rating">Minimum rating</label>
                        <select id="filter-rating" name="min_rating">
                            <option value="">Any rating</option>
                            <option value="4" @selected((string) ($filters['min_rating'] ?? '') === '4')>4 stars & up</option>
                        </select>
                        <small class="muted">Ratings appear only after verified reviews.</small>
                    </div>
                    <div class="filter-actions">
                        <button class="button button--small button--full" type="submit">Apply filters</button>
                        <a class="button button--small button--outline" href="{{ route('shop') }}">Clear filters</a>
                    </div>
                </form>
            </aside>

            <section aria-label="Product results">
                <div class="shop-toolbar">
                    <p><strong>{{ $products->total() }}</strong> demo products</p>
                    <form class="shop-sort" method="GET" action="{{ route('shop') }}">
                        @foreach (['q', 'category', 'brand', 'form', 'rx', 'max_price', 'min_discount', 'min_rating'] as $key)
                            @if (! empty($filters[$key])) <input type="hidden" name="{{ $key }}" value="{{ $filters[$key] }}"> @endif
                        @endforeach
                        <label for="sort-products">Sort</label>
                        <select id="sort-products" name="sort" data-sort-select>
                            <option value="relevance" @selected(($filters['sort'] ?? 'relevance') === 'relevance')>Featured</option>
                            <option value="price-low" @selected(($filters['sort'] ?? '') === 'price-low')>Price: low to high</option>
                            <option value="price-high" @selected(($filters['sort'] ?? '') === 'price-high')>Price: high to low</option>
                            <option value="discount" @selected(($filters['sort'] ?? '') === 'discount')>Discount</option>
                            <option value="rating" @selected(($filters['sort'] ?? '') === 'rating')>Rating</option>
                        </select>
                    </form>
                </div>

                @if ($products->isEmpty())
                    <div class="empty-state">
                        <h2>No matching products</h2>
                        <p>Try clearing one or more filters.</p>
                        <a class="button button--outline" href="{{ route('shop') }}">Clear filters</a>
                    </div>
                @else
                    <div class="product-grid">
                        @foreach ($products as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>
                    @if ($products->hasPages())
                        <nav class="pagination" aria-label="Product pages">
                            @if ($products->onFirstPage())
                                <span aria-disabled="true">Previous</span>
                            @else
                                <a href="{{ $products->previousPageUrl() }}" rel="prev">Previous</a>
                            @endif
                            @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                                @if ($page === $products->currentPage())
                                    <span class="active" aria-current="page">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}">{{ $page }}</a>
                                @endif
                            @endforeach
                            @if ($products->hasMorePages())
                                <a href="{{ $products->nextPageUrl() }}" rel="next">Next</a>
                            @else
                                <span aria-disabled="true">Next</span>
                            @endif
                        </nav>
                    @endif
                @endif
            </section>
        </div>
    </div>
@endsection

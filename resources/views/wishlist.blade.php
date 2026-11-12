@extends('layout')

@section('title', $pageContent->value('wishlist', 'heading', 'title', 'Your wishlist').' | '.config('store.name'))

@section('content')
    <div class="container page-shell">
        <nav class="breadcrumbs" aria-label="Breadcrumb"><a href="{{ route('store') }}">Home</a><span aria-hidden="true">/</span><span>Wishlist</span></nav>
        <header class="page-heading"><p class="eyebrow">{{ $pageContent->value('wishlist', 'heading', 'eyebrow', 'Saved in this browser session') }}</p><h1>{{ $pageContent->value('wishlist', 'heading', 'title', 'Your wishlist') }}</h1><p>{{ $pageContent->value('wishlist', 'heading', 'description', 'Wishlist items are stored temporarily in this browser session; sign-in and cross-device sync are not configured.') }}</p></header>
        @if ($products->isEmpty())
            <section class="empty-state"><h2>{{ $pageContent->value('wishlist', 'empty', 'title', 'No saved products yet.') }}</h2><p>{{ $pageContent->value('wishlist', 'empty', 'description', 'Use the heart on a product card to save a demo item.') }}</p><a class="button" href="{{ route('shop') }}">{{ $pageContent->value('wishlist', 'empty', 'button_text', 'Browse catalogue') }}</a></section>
        @else
            <div class="product-grid">
                @foreach ($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        @endif
    </div>
@endsection

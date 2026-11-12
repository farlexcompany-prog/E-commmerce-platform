@extends('layout')

@section('title', 'Wishlist | '.config('store.name'))

@section('content')
    <div class="container page-shell">
        <nav class="breadcrumbs" aria-label="Breadcrumb"><a href="{{ route('store') }}">Home</a><span aria-hidden="true">/</span><span>Wishlist</span></nav>
        <header class="page-heading"><p class="eyebrow">Saved in this browser session</p><h1>Your wishlist</h1><p>Wishlist items are stored temporarily in this browser session; sign-in and cross-device sync are not configured.</p></header>
        @if ($products->isEmpty())
            <section class="empty-state"><h2>No saved products yet.</h2><p>Use the heart on a product card to save a demo item.</p><a class="button" href="{{ route('shop') }}">Browse catalogue</a></section>
        @else
            <div class="product-grid">
                @foreach ($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        @endif
    </div>
@endsection

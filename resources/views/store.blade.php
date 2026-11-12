@extends('layout')

@section('title', config('app.name').' | Shop')

@section('content')
    <section class="intro">
        <p class="muted">Thoughtful things for everyday life</p>
        <h1>Good finds. Made for every day.</h1>
        <p class="muted">Browse the collection, add your favorites to the cart, and place a demo order.</p>
    </section>

    <section class="grid" aria-label="Products">
        @forelse ($products as $product)
            <article class="card">
                <div class="product-mark" aria-hidden="true"><span>{{ mb_substr($product->name, 0, 1) }}</span></div>
                <h2 class="product-name">{{ $product->name }}</h2>
                <p class="description">{{ $product->description }}</p>
                <div class="row">
                    <span class="price">${{ number_format((float) $product->price, 2) }}</span>
                    <form method="POST" action="{{ route('cart.store', $product) }}">
                        @csrf
                        <button type="submit">Add to cart</button>
                    </form>
                </div>
            </article>
        @empty
            <p class="muted">No products yet. Seed the database to add the starter products.</p>
        @endforelse
    </section>
@endsection

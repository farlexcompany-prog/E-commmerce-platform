@props(['product', 'size' => 'card'])

@php
    $artStyles = ['tablet', 'capsule', 'bottle', 'tube', 'vial', 'supplement', 'jar', 'device'];
    $artStyle = in_array($product->art_style, $artStyles, true) ? $product->art_style : 'tablet';
@endphp

<div class="product-art product-art--{{ $artStyle }} product-art--{{ $size }}"
     role="img"
     aria-label="Illustrative demo packaging for {{ $product->name }}">
    @if ($product->image_url)
        <img src="{{ $product->image_url }}" alt="{{ $product->name }} packaging" loading="lazy" decoding="async">
    @else
        <div class="pack-illustration">
            <span class="pack-demo">DEMO · PLACEHOLDER</span>
            <strong>{{ \Illuminate\Support\Str::limit($product->name, 30) }}</strong>
            <span class="pack-size">{{ $product->pack_size }}</span>
            <span class="pack-rule" aria-hidden="true"></span>
        </div>
        <span class="pack-shadow" aria-hidden="true"></span>
    @endif
</div>

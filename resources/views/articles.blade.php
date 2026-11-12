@extends('layout')

@section('title', 'Health & product guides | '.config('store.name'))

@section('content')
    <div class="container page-shell">
        <nav class="breadcrumbs" aria-label="Breadcrumb"><a href="{{ route('store') }}">Home</a><span aria-hidden="true">/</span><span>Health articles</span></nav>
        <header class="page-heading"><p class="eyebrow">General information</p><h1>Health & product guides</h1><p>These general reading notes do not diagnose, treat, or replace advice from a registered medical practitioner or pharmacist.</p></header>
        <div class="article-body">
            <section id="product-labels"><p class="eyebrow">Product information</p><h2>How to read a product label</h2><p>Check the product name, pack size, manufacturer details, batch information, expiry date, and storage instructions on the original packaging. Ask a pharmacist if any information is unclear.</p></section>
            <section id="storage"><p class="eyebrow">Everyday care</p><h2>Keep products in original packaging</h2><p>Follow the storage information supplied with the specific product and keep items away from children. Do not use products past their printed expiry date or if packaging appears damaged.</p></section>
            <section id="pharmacist"><p class="eyebrow">Pharmacy support</p><h2>Questions to ask your pharmacist</h2><p>Ask a qualified pharmacist about the instructions on your product label, possible interactions, and how to store a product. For diagnosis or treatment advice, consult a registered medical practitioner.</p></section>
            <div class="medical-disclaimer">This demo content is general and not medical advice. Product-specific information must be reviewed by qualified professionals before publication.</div>
        </div>
    </div>
@endsection

@extends('layout')

@section('title', $title.' | '.config('store.name'))

@section('content')
    <div class="container page-shell">
        <nav class="breadcrumbs" aria-label="Breadcrumb"><a href="{{ route('store') }}">Home</a><span aria-hidden="true">/</span><span>{{ $title }}</span></nav>
        <article class="article-body">
            <p class="eyebrow">Review draft</p>
            <h1>{{ $title }}</h1>
            <div class="policy-notice">{{ $intro }}</div>
            @foreach ($sections as $section)
                <section><p>{{ $section }}</p></section>
            @endforeach
            <p class="fine-print">This is placeholder content, not legal advice. Obtain review from qualified counsel and pharmacy compliance professionals before publishing.</p>
        </article>
    </div>
@endsection

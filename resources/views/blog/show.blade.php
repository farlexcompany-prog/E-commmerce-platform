@extends('layout')

@section('title', ($blog->meta_title ?: $blog->title).' | '.config('store.name'))
@section('meta_description', $blog->meta_description ?: \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($blog->content))), 155))

@section('content')
    <style>
        .blog-article { max-width: 820px; margin: 0 auto; }
        .blog-article h1 { margin: 0 0 10px; line-height: 1.25; }
        .blog-article__date { color: #6b7585; font-size: .9rem; margin: 0 0 22px; }
        .blog-article__image { width: 100%; height: auto; border-radius: 12px; margin: 0 0 26px; display: block; }
        .blog-article__content { line-height: 1.8; font-size: 1.05rem; }
        .blog-article .medical-disclaimer { margin-top: 32px; }
        .blog-article__back { display: inline-block; margin-top: 22px; }
    </style>

    <div class="container page-shell">
        <nav class="breadcrumbs" aria-label="Breadcrumb"><a href="{{ route('store') }}">Home</a><span aria-hidden="true">/</span><a href="{{ route('blog.index') }}">Blog</a><span aria-hidden="true">/</span><span>{{ \Illuminate\Support\Str::limit($blog->title, 50) }}</span></nav>

        <article class="blog-article">
            <h1>{{ $blog->title }}</h1>
            <p class="blog-article__date">{{ $blog->shown_date->format('d M Y') }}</p>

            @if ($blog->image_url)
                <img class="blog-article__image" src="{{ asset($blog->image_url) }}" alt="{{ $blog->title }}">
            @endif

            <div class="blog-article__content">{!! nl2br(e($blog->content)) !!}</div>

            <div class="medical-disclaimer">This article is general information and not medical advice. Please speak to a registered medical practitioner or pharmacist about your own health.</div>

            <a class="text-link blog-article__back" href="{{ route('blog.index') }}">← All articles</a>
        </article>
    </div>
@endsection

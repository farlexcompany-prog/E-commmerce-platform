@extends('layout')

@section('title', 'Blog | '.config('store.name'))
@section('meta_description', 'Health and pharmacy articles from '.config('store.name').'.')

@section('content')
    <style>
        .blog-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(290px, 1fr)); gap: 24px; margin-top: 28px; }
        .blog-card { background: #fff; border: 1px solid #e3e7ee; border-radius: 12px; overflow: hidden; display: flex; flex-direction: column; }
        .blog-card__media { display: block; aspect-ratio: 1200 / 630; background: #e8edf5; }
        .blog-card__media img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .blog-card__body { padding: 18px 20px 20px; display: flex; flex-direction: column; flex: 1; }
        .blog-card__date { font-size: .8rem; color: #6b7585; margin: 0 0 8px; }
        .blog-card h2 { font-size: 1.1rem; line-height: 1.35; margin: 0 0 10px; }
        .blog-card h2 a { color: inherit; text-decoration: none; }
        .blog-card h2 a:hover { text-decoration: underline; }
        .blog-card p { margin: 0 0 14px; color: #4a5565; line-height: 1.55; flex: 1; }
        .blog-pager { display: flex; justify-content: center; gap: 12px; margin: 36px 0 10px; }
        .blog-empty { text-align: center; padding: 60px 0; color: #5a6575; }
    </style>

    <div class="container page-shell">
        <nav class="breadcrumbs" aria-label="Breadcrumb"><a href="{{ route('store') }}">Home</a><span aria-hidden="true">/</span><span>Blog</span></nav>

        <header class="page-heading">
            <p class="eyebrow">Farlex blog</p>
            <h1>Health articles &amp; guides</h1>
            <p>General reading notes. They do not diagnose, treat, or replace advice from a registered medical practitioner or pharmacist.</p>
        </header>

        @if ($blogs->isEmpty())
            <p class="blog-empty">No articles published yet. Please check back soon.</p>
        @else
            <div class="blog-grid">
                @foreach ($blogs as $blog)
                    <article class="blog-card">
                        <a class="blog-card__media" href="{{ route('blog.show', $blog->slug) }}" tabindex="-1" aria-hidden="true">
                            @if ($blog->image_url)
                                <img src="{{ asset($blog->image_url) }}" alt="" loading="lazy">
                            @endif
                        </a>
                        <div class="blog-card__body">
                            <p class="blog-card__date">{{ $blog->shown_date->format('d M Y') }}</p>
                            <h2><a href="{{ route('blog.show', $blog->slug) }}">{{ $blog->title }}</a></h2>
                            <p>{{ \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($blog->content))), 150) }}</p>
                            <a class="text-link" href="{{ route('blog.show', $blog->slug) }}">Read article →</a>
                        </div>
                    </article>
                @endforeach
            </div>

            @if ($blogs->previousPageUrl() || $blogs->nextPageUrl())
                <div class="blog-pager">
                    @if ($blogs->previousPageUrl())
                        <a class="button button--outline" href="{{ $blogs->previousPageUrl() }}">← Newer</a>
                    @endif
                    @if ($blogs->nextPageUrl())
                        <a class="button button--outline" href="{{ $blogs->nextPageUrl() }}">Older →</a>
                    @endif
                </div>
            @endif
        @endif
    </div>
@endsection

@extends('layout')

@section('title', $pageContent->value('articles', 'heading', 'title', 'Health & product guides').' | '.config('store.name'))

@section('content')
    @php
        $articleSections = collect([
            'heading' => ['order' => 10],
            'article_one' => ['order' => 20],
            'article_two' => ['order' => 30],
            'article_three' => ['order' => 40],
            'disclaimer' => ['order' => 50],
        ])->sortBy(fn ($section, $key) => $pageContent->order('articles', $key, $section['order']));
    @endphp
    <div class="container page-shell cms-sortable-page">
        <nav class="breadcrumbs" aria-label="Breadcrumb" style="order: -1"><a href="{{ route('store') }}">Home</a><span aria-hidden="true">/</span><span>Health articles</span></nav>
        @foreach ($articleSections as $sectionKey => $section)
            @if ($sectionKey === 'heading')
                <header class="page-heading" style="order: {{ $pageContent->order('articles', 'heading', 10) }}">
                    <p class="eyebrow">{{ $pageContent->value('articles', 'heading', 'eyebrow', 'General information') }}</p>
                    <h1>{{ $pageContent->value('articles', 'heading', 'title', 'Health & product guides') }}</h1>
                    <p>{{ $pageContent->value('articles', 'heading', 'description', 'These general reading notes do not diagnose, treat, or replace advice from a registered medical practitioner or pharmacist.') }}</p>
                </header>
            @elseif ($sectionKey === 'disclaimer')
                <div class="medical-disclaimer cms-article-content" style="order: {{ $pageContent->order('articles', 'disclaimer', 50) }}">{{ $pageContent->value('articles', 'disclaimer', 'copy', 'This demo content is general and not medical advice. Product-specific information must be reviewed by qualified professionals before publication.') }}</div>
            @elseif ($pageContent->enabled('articles', $sectionKey))
                @php
                    $anchors = ['article_one' => 'product-labels', 'article_two' => 'storage', 'article_three' => 'pharmacist'];
                @endphp
                <section class="cms-article-content" id="{{ $anchors[$sectionKey] }}" style="order: {{ $pageContent->order('articles', $sectionKey, $section['order']) }}">
                    <p class="eyebrow">{{ $pageContent->value('articles', $sectionKey, 'eyebrow', '') }}</p>
                    <h2>{{ $pageContent->value('articles', $sectionKey, 'title', '') }}</h2>
                    @if ($pageContent->imageUrl('articles', $sectionKey, 'image'))
                        <img class="cms-article-image" src="{{ $pageContent->imageUrl('articles', $sectionKey, 'image') }}" alt="{{ $pageContent->value('articles', $sectionKey, 'title', '') }}" loading="lazy">
                    @endif
                    <p>{{ $pageContent->value('articles', $sectionKey, 'copy', '') }}</p>
                </section>
            @endif
        @endforeach
    </div>
@endsection

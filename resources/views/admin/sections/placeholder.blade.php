@extends('admin.layout')

@section('title', $section['title'])
@section('section_title', $section['title'])

@section('content')
    <div class="admin-breadcrumbs">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span aria-hidden="true">/</span>
        <span>{{ $section['title'] }}</span>
    </div>

    <section class="admin-placeholder" aria-labelledby="admin-section-title">
        <div class="admin-placeholder-banner">
            <p class="eyebrow">{{ $section['group'] }} workspace</p>
            <h1 id="admin-section-title">{{ $section['title'] }}</h1>
            <p>{{ $section['description'] }}</p>
        </div>

        <div class="admin-placeholder-content">
            <span class="admin-placeholder-badge">Demo page</span>

            <div class="admin-placeholder-grid" aria-label="Preview summary">
                <article class="admin-placeholder-card">
                    <span>Total records</span>
                    <strong>Not configured</strong>
                </article>
                <article class="admin-placeholder-card">
                    <span>Workspace status</span>
                    <strong>Preview only</strong>
                </article>
                <article class="admin-placeholder-card">
                    <span>Last updated</span>
                    <strong>Not available</strong>
                </article>
            </div>

            <div class="admin-placeholder-note">
                <strong>This is a sample workspace</strong>
                <span>Navigation and page layout are ready. Management features and live data will be added when this section is implemented.</span>
            </div>

            <a class="admin-primary-button" href="{{ route('admin.dashboard') }}">Back to dashboard</a>
        </div>
    </section>
@endsection

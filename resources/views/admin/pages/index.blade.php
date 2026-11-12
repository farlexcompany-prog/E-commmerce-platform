@extends('admin.layout')

@section('title', 'Pages')
@section('section_title', 'Pages')

@section('content')
    <div class="admin-breadcrumbs">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span aria-hidden="true">/</span>
        <span>Pages</span>
    </div>

    <div class="admin-page-heading">
        <div>
            <p class="eyebrow">Storefront content</p>
            <h1>Pages</h1>
            <p>Edit page copy, images, buttons, and section order without changing product or checkout data.</p>
        </div>
    </div>

    <div class="admin-page-editor-notice">
        <strong>Safe structured editing</strong>
        <span>Text is displayed as plain text. Product, pricing, cart, order, and checkout behavior stays connected to the live store data.</span>
    </div>

    @foreach ($groups as $group => $groupPages)
        <section class="admin-page-group">
            <div class="admin-page-group-heading">
                <h2>{{ $group }}</h2>
                <span>{{ $groupPages->count() }} page{{ $groupPages->count() === 1 ? '' : 's' }}</span>
            </div>
            <div class="admin-page-card-grid">
                @foreach ($groupPages as $pageKey => $definition)
                    @php($record = $pages->get($pageKey))
                    <article class="admin-page-card">
                        <div>
                            <p class="eyebrow">{{ $definition['url'] }}</p>
                            <h3>{{ $definition['title'] }}</h3>
                            <p>{{ $definition['description'] }}</p>
                        </div>
                        <div class="admin-page-card-footer">
                            <span>{{ count($definition['sections']) }} editable sections @if ($record) · Updated {{ $record->updated_at->format('d M Y') }} @else · Using defaults @endif</span>
                            <a class="admin-primary-button" href="{{ route('admin.pages.edit', $pageKey) }}">Edit page</a>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endforeach
@endsection

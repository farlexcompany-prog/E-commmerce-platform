@extends('admin.layout')

@section('title', 'Edit '.$definition['title'])
@section('section_title', 'Pages')

@section('content')
    <div class="admin-breadcrumbs">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span aria-hidden="true">/</span>
        <a href="{{ route('admin.pages.index') }}">Pages</a>
        <span aria-hidden="true">/</span>
        <span>{{ $definition['title'] }}</span>
    </div>

    <div class="admin-page-heading">
        <div>
            <p class="eyebrow">{{ $definition['url'] }}</p>
            <h1>{{ $definition['title'] }}</h1>
            <p>{{ $definition['description'] }}</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="admin-form-error" role="alert">
            <strong>Please correct the highlighted fields.</strong>
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    @if (in_array($pageKey, ['privacy', 'terms', 'shipping', 'refund'], true))
        <div class="admin-page-editor-notice admin-page-editor-notice--review">
            <strong>Review before publishing</strong>
            <span>Policy changes should be checked by qualified legal and pharmacy compliance professionals.</span>
        </div>
    @elseif (in_array($pageKey, ['articles', 'product'], true))
        <div class="admin-page-editor-notice admin-page-editor-notice--review">
            <strong>Medical content review</strong>
            <span>Have product and health information checked by a registered pharmacist before publishing.</span>
        </div>
    @endif

    <form class="admin-page-editor-form" method="POST" action="{{ route('admin.pages.update', $pageKey) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @foreach ($definition['sections'] as $sectionKey => $section)
            @php
                $sectionContent = data_get($page?->content, "sections.{$sectionKey}", []);
                $sectionEnabled = old("content.sections.{$sectionKey}.enabled", $sectionContent['enabled'] ?? true);
                $sectionOrder = old("content.sections.{$sectionKey}.order", $sectionContent['order'] ?? $section['order']);
            @endphp
            <section class="admin-product-form-section admin-page-editor-section">
                <div class="admin-product-form-heading">
                    <h2>{{ $section['title'] }}</h2>
                    <p>These fields control this section on the {{ $definition['title'] }} page.</p>
                </div>
                <div class="admin-page-section-controls">
                    @if ($definition['reorderable'] ?? false)
                        <div class="admin-field">
                            <label for="section-order-{{ $sectionKey }}">Section order</label>
                            <input id="section-order-{{ $sectionKey }}" type="number" name="content[sections][{{ $sectionKey }}][order]" min="0" max="999" value="{{ $sectionOrder }}" required>
                            @error('content.sections.'.$sectionKey.'.order')<span class="admin-field-error">{{ $message }}</span>@enderror
                        </div>
                    @else
                        <span class="admin-field-hint">Section order is fixed to preserve this page's interactive flow.</span>
                    @endif
                    @if ($section['optional'] ?? false)
                        <label class="admin-checkbox">
                            <input type="hidden" name="content[sections][{{ $sectionKey }}][enabled]" value="0">
                            <input type="checkbox" name="content[sections][{{ $sectionKey }}][enabled]" value="1" @checked((bool) $sectionEnabled)>
                            <span>Show this section</span>
                        </label>
                    @endif
                </div>
                <div class="admin-page-field-grid">
                    @foreach ($section['fields'] as $fieldKey => $field)
                        @php
                            $storedValue = data_get($page?->content, "fields.{$sectionKey}.{$fieldKey}", $field['default'] ?? '');
                            $fieldValue = old("content.fields.{$sectionKey}.{$fieldKey}", $storedValue);
                        @endphp
                        <div class="admin-field {{ $field['type'] === 'textarea' ? 'is-full' : '' }}">
                            @if ($field['type'] === 'image')
                                <label for="page-image-{{ $sectionKey }}-{{ $fieldKey }}">{{ $field['label'] }}</label>
                                <input id="page-image-{{ $sectionKey }}-{{ $fieldKey }}" type="file" name="media[{{ $sectionKey }}][{{ $fieldKey }}]" accept=".jpg,.jpeg,.png,.webp,.avif,image/jpeg,image/png,image/webp,image/avif">
                                <span class="admin-field-hint">JPEG, PNG, WebP, or AVIF; maximum 5 MB.</span>
                                @if (is_string($storedValue) && $storedValue !== '')
                                    <img class="admin-cms-image-preview" src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($storedValue) }}" alt="Current {{ strtolower($field['label']) }}">
                                    <label class="admin-checkbox">
                                        <input type="hidden" name="remove_media[{{ $sectionKey }}][{{ $fieldKey }}]" value="0">
                                        <input type="checkbox" name="remove_media[{{ $sectionKey }}][{{ $fieldKey }}]" value="1">
                                        <span>Remove current image</span>
                                    </label>
                                @endif
                                @error('media.'.$sectionKey.'.'.$fieldKey)<span class="admin-field-error">{{ $message }}</span>@enderror
                            @elseif ($field['type'] === 'textarea')
                                <label for="page-field-{{ $sectionKey }}-{{ $fieldKey }}">{{ $field['label'] }}</label>
                                <textarea id="page-field-{{ $sectionKey }}-{{ $fieldKey }}" name="content[fields][{{ $sectionKey }}][{{ $fieldKey }}]" rows="4" maxlength="5000" @required($field['required'] ?? true)>{{ $fieldValue }}</textarea>
                                @error('content.fields.'.$sectionKey.'.'.$fieldKey)<span class="admin-field-error">{{ $message }}</span>@enderror
                            @else
                                <label for="page-field-{{ $sectionKey }}-{{ $fieldKey }}">{{ $field['label'] }}</label>
                                <input id="page-field-{{ $sectionKey }}-{{ $fieldKey }}" type="text" name="content[fields][{{ $sectionKey }}][{{ $fieldKey }}]" value="{{ $fieldValue }}" maxlength="{{ $field['type'] === 'url' ? 2048 : 255 }}" @required($field['required'] ?? true)>
                                @if ($field['type'] === 'url')
                                    <span class="admin-field-hint">Use a site path such as /shop or a full https:// URL.</span>
                                @endif
                                @error('content.fields.'.$sectionKey.'.'.$fieldKey)<span class="admin-field-error">{{ $message }}</span>@enderror
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>
        @endforeach

        <div class="admin-product-form-actions">
            <a class="admin-secondary-button" href="{{ route('admin.pages.index') }}">Back to pages</a>
            <button class="admin-primary-button" type="submit">Save page content</button>
        </div>
    </form>
@endsection

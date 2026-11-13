@extends('admin.layout')

@php($isEditing = $review->exists)

@section('title', $isEditing ? 'Edit review' : 'Add review')
@section('section_title', 'Homepage reviews')

@section('content')
    <div class="admin-breadcrumbs">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span aria-hidden="true">/</span>
        <a href="{{ route('admin.pages.index') }}">Pages</a>
        <span aria-hidden="true">/</span>
        <a href="{{ route('admin.reviews.index') }}">Homepage reviews</a>
        <span aria-hidden="true">/</span>
        <span>{{ $isEditing ? 'Edit' : 'Add' }}</span>
    </div>

    <div class="admin-page-heading">
        <div>
            <p class="eyebrow">Customer stories</p>
            <h1>{{ $isEditing ? 'Edit review' : 'Add a review' }}</h1>
            <p>Add a genuine written review, a YouTube video, or both. Drafts remain private until published.</p>
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

    <form class="admin-product-form" method="POST" enctype="multipart/form-data" action="{{ $isEditing ? route('admin.reviews.update', $review) : route('admin.reviews.store') }}">
        @csrf
        @if ($isEditing)
            @method('PUT')
        @endif

        <section class="admin-product-form-section">
            <div class="admin-product-form-heading">
                <h2>Review content</h2>
                <p>Provide a YouTube video URL, written review, or both. At least one is required.</p>
            </div>
            <div class="admin-product-form-grid">
                <div class="admin-field">
                    <label for="reviewer-name">Reviewer display name *</label>
                    <input id="reviewer-name" name="reviewer_name" value="{{ old('reviewer_name', $review->reviewer_name) }}" maxlength="120" required>
                    @error('reviewer_name')<span class="admin-field-error">{{ $message }}</span>@enderror
                </div>
                <div class="admin-field">
                    <label for="review-position">Carousel position</label>
                    <input id="review-position" name="position" type="number" min="0" max="9999" value="{{ old('position', $review->position ?? 0) }}" required>
                    <span class="admin-field-hint">Smaller numbers appear first.</span>
                    @error('position')<span class="admin-field-error">{{ $message }}</span>@enderror
                </div>
                <div class="admin-field is-full">
                    <label for="video-title">Video title</label>
                    <input id="video-title" name="video_title" value="{{ old('video_title', $review->video_title) }}" maxlength="255">
                    <span class="admin-field-hint">Optional title displayed above the video.</span>
                    @error('video_title')<span class="admin-field-error">{{ $message }}</span>@enderror
                </div>
                <div class="admin-field is-full">
                    <label for="review-text">Written review</label>
                    <textarea id="review-text" name="review_text" rows="5" maxlength="3000">{{ old('review_text', $review->review_text) }}</textarea>
                    @error('review_text')<span class="admin-field-error">{{ $message }}</span>@enderror
                </div>
                <div class="admin-field is-full">
                    <label for="youtube-url">YouTube video URL</label>
                    <input id="youtube-url" name="youtube_url" type="url" value="{{ old('youtube_url', $review->youtube_url) }}" maxlength="2048" placeholder="https://www.youtube.com/watch?v=...">
                    <span class="admin-field-hint">Paste a public YouTube watch, youtu.be, Shorts, or live video URL. The video is embedded from YouTube; it is not copied to this website.</span>
                    @error('youtube_url')<span class="admin-field-error">{{ $message }}</span>@enderror
                </div>
                <div class="admin-field is-full">
                    <label for="review-image">Review image</label>
                    <input id="review-image" name="image" type="file" accept=".jpg,.jpeg,.png,.webp,.avif,image/jpeg,image/png,image/webp,image/avif">
                    <span class="admin-field-hint">Optional JPEG, PNG, WebP, or AVIF image; maximum 5 MB.</span>
                    @if ($review->image_path)
                        <img class="admin-cms-image-preview admin-review-image-preview" src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($review->image_path) }}" alt="Current review image">
                        <label class="admin-checkbox">
                            <input type="hidden" name="remove_image" value="0">
                            <input type="checkbox" name="remove_image" value="1">
                            <span>Remove current image</span>
                        </label>
                    @endif
                    @error('image')<span class="admin-field-error">{{ $message }}</span>@enderror
                </div>
            </div>
        </section>

        <section class="admin-product-form-section">
            <div class="admin-product-form-heading">
                <h2>Permission and publishing</h2>
                <p>Unpublished drafts never appear in the public carousel.</p>
            </div>
            <label class="admin-checkbox admin-review-permission">
                <input type="hidden" name="permission_confirmed" value="0">
                <input type="checkbox" name="permission_confirmed" value="1" @checked(old('permission_confirmed')) required>
                <span>I confirm this is genuine customer content, and I have permission to publish the testimonial, display name, video, and uploaded image provided here.</span>
            </label>
            @error('permission_confirmed')<span class="admin-field-error">{{ $message }}</span>@enderror
            <label class="admin-checkbox admin-review-publish">
                <input type="hidden" name="published" value="0">
                <input type="checkbox" name="published" value="1" @checked((bool) old('published', $review->published))>
                <span>Publish on the homepage carousel</span>
            </label>
        </section>

        <div class="admin-product-form-actions">
            <a class="admin-secondary-button" href="{{ route('admin.reviews.index') }}">Cancel</a>
            <button class="admin-primary-button" type="submit">{{ $isEditing ? 'Save review' : 'Add review' }}</button>
        </div>
    </form>
@endsection

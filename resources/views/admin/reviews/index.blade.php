@extends('admin.layout')

@section('title', 'Homepage reviews')
@section('section_title', 'Homepage reviews')

@section('content')
    <div class="admin-breadcrumbs">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span aria-hidden="true">/</span>
        <a href="{{ route('admin.pages.index') }}">Pages</a>
        <span aria-hidden="true">/</span>
        <span>Homepage reviews</span>
    </div>

    <div class="admin-page-heading admin-reviews-heading">
        <div>
            <p class="eyebrow">Customer stories</p>
            <h1>Homepage reviews</h1>
            <p>Manage written reviews, YouTube video embeds, and optional images shown in the homepage carousel.</p>
        </div>
        <a class="admin-primary-button" href="{{ route('admin.reviews.create') }}">Add review</a>
    </div>

    <div class="admin-page-editor-notice admin-page-editor-notice--review">
        <strong>Publish responsibly</strong>
        <span>Only publish genuine customer feedback and images you have permission to share. Do not edit reviews in a way that changes their meaning; testimonials are not medical advice.</span>
    </div>

    @if ($reviews->isEmpty())
        <div class="admin-placeholder-note">
            <strong>No review cards yet</strong>
            Add your first written review or YouTube video. Drafts are not shown on the storefront until published.
        </div>
    @else
        <div class="admin-review-list">
            @foreach ($reviews as $review)
                <article class="admin-review-row">
                    @if ($review->image_path)
                        <img class="admin-review-thumb" src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($review->image_path) }}" alt="">
                    @else
                        <div class="admin-review-thumb admin-review-thumb--empty" aria-hidden="true">Review</div>
                    @endif
                    <div class="admin-review-row-copy">
                        <div class="admin-review-row-heading">
                            <h2>{{ $review->reviewer_name }}</h2>
                            <span class="admin-status-badge {{ $review->published ? 'is-active' : 'is-inactive' }}">{{ $review->published ? 'Published' : 'Draft' }}</span>
                        </div>
                        @if ($review->video_title)
                            <p>{{ $review->video_title }}</p>
                        @endif
                        @if ($review->review_text)
                            <p>{{ \Illuminate\Support\Str::limit($review->review_text, 200) }}</p>
                        @endif
                        @if ($review->youtube_url)
                            <a class="admin-review-video-link" href="{{ $review->youtube_url }}" target="_blank" rel="noopener noreferrer">Open YouTube video ↗</a>
                        @endif
                    </div>
                    <div class="admin-review-row-actions">
                        <a class="admin-secondary-button" href="{{ route('admin.reviews.edit', $review) }}">Edit</a>
                        <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" onsubmit="return confirm('Delete this review? This cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button class="admin-secondary-button admin-review-delete" type="submit">Delete</button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>
        @if ($reviews->hasPages())
            <nav class="admin-pagination" aria-label="Review pages">
                {{ $reviews->links() }}
            </nav>
        @endif
    @endif
@endsection

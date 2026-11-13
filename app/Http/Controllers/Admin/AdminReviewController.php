<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use RuntimeException;

class AdminReviewController extends Controller
{
    public function index(): View
    {
        $reviews = CmsReview::query()
            ->orderBy('position')
            ->orderByDesc('updated_at')
            ->paginate(15);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function create(): View
    {
        return view('admin.reviews.form', ['review' => new CmsReview]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedReviewData($request);
        $data['image_path'] = $this->storeImage($request);

        CmsReview::query()->create($data);

        return redirect()
            ->route('admin.reviews.index')
            ->with('status', 'Review added.');
    }

    public function edit(CmsReview $review): View
    {
        return view('admin.reviews.form', compact('review'));
    }

    public function update(Request $request, CmsReview $review): RedirectResponse
    {
        $data = $this->validatedReviewData($request);
        $oldImage = $review->image_path;
        $newImage = $this->storeImage($request);

        if ($newImage !== null) {
            $data['image_path'] = $newImage;
        } elseif ($request->boolean('remove_image')) {
            $data['image_path'] = null;
        }

        $review->update($data);

        if ($oldImage && ($newImage !== null || $request->boolean('remove_image'))) {
            $this->deleteImage($oldImage);
        }

        return redirect()
            ->route('admin.reviews.index')
            ->with('status', 'Review updated.');
    }

    public function destroy(CmsReview $review): RedirectResponse
    {
        $imagePath = $review->image_path;
        $review->delete();

        if ($imagePath) {
            $this->deleteImage($imagePath);
        }

        return redirect()
            ->route('admin.reviews.index')
            ->with('status', 'Review deleted.');
    }

    private function validatedReviewData(Request $request): array
    {
        $data = $request->validate([
            'reviewer_name' => ['required', 'string', 'max:120'],
            'video_title' => ['nullable', 'string', 'max:255'],
            'review_text' => ['nullable', 'string', 'max:3000', 'required_without:youtube_url'],
            'youtube_url' => [
                'nullable',
                'url',
                'max:2048',
                'required_without:review_text',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if ($value !== null && $value !== '' && (! is_string($value) || CmsReview::youtubeVideoIdFromUrl($value) === null)) {
                        $fail('Enter a valid HTTPS YouTube video URL.');
                    }
                },
            ],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,avif', 'max:5120'],
            'remove_image' => ['sometimes', 'boolean'],
            'published' => ['sometimes', 'boolean'],
            'permission_confirmed' => ['required', 'accepted'],
            'position' => ['required', 'integer', 'min:0', 'max:9999'],
        ]);

        $data['published'] = $request->boolean('published');
        $data['permission_confirmed'] = true;
        unset($data['image'], $data['remove_image']);

        return $data;
    }

    private function storeImage(Request $request): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        $path = $request->file('image')->store('reviews/images', 'public');
        if (! is_string($path) || $path === '') {
            throw new RuntimeException('Unable to store the review image.');
        }

        return $path;
    }

    private function deleteImage(string $path): void
    {
        if (! Storage::disk('public')->delete($path)) {
            throw new RuntimeException('Unable to delete the replaced review image.');
        }
    }
}

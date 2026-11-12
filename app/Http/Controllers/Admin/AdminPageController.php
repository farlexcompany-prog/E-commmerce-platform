<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use App\Services\PageContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use RuntimeException;

class AdminPageController extends Controller
{
    public function index(): View
    {
        $definitions = config('cms.pages');
        $groups = collect($definitions)->groupBy('group', true);
        $pages = CmsPage::query()
            ->whereIn('page_key', array_keys($definitions))
            ->get()
            ->keyBy('page_key');

        return view('admin.pages.index', compact('groups', 'pages'));
    }

    public function edit(string $pageKey): View
    {
        $definition = $this->definition($pageKey);
        $page = CmsPage::query()->where('page_key', $pageKey)->first();

        return view('admin.pages.edit', compact('pageKey', 'definition', 'page'));
    }

    public function update(Request $request, string $pageKey, PageContent $pageContent): RedirectResponse
    {
        $definition = $this->definition($pageKey);
        $rules = [
            'content' => ['required', 'array'],
            'content.fields' => ['nullable', 'array'],
            'content.sections' => ['nullable', 'array'],
            'media' => ['nullable', 'array'],
            'remove_media' => ['nullable', 'array'],
        ];

        foreach ($definition['sections'] as $sectionKey => $section) {
            if ($definition['reorderable'] ?? false) {
                $rules["content.sections.{$sectionKey}.order"] = ['required', 'integer', 'min:0', 'max:999'];
            }
            if ($section['optional'] ?? false) {
                $rules["content.sections.{$sectionKey}.enabled"] = ['required', 'boolean'];
            }

            foreach ($section['fields'] as $fieldKey => $field) {
                if ($field['type'] === 'image') {
                    $rules["media.{$sectionKey}.{$fieldKey}"] = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,avif', 'max:5120'];
                    $rules["remove_media.{$sectionKey}.{$fieldKey}"] = ['sometimes', 'boolean'];
                    continue;
                }

                $fieldRules = [
                    ($field['required'] ?? true) ? 'required' : 'nullable',
                    'string',
                    $field['type'] === 'textarea' ? 'max:5000' : ($field['type'] === 'url' ? 'max:2048' : 'max:255'),
                ];
                if ($field['type'] === 'url') {
                    $fieldRules[] = function (string $attribute, mixed $value, \Closure $fail): void {
                        if ($value === '') {
                            return;
                        }
                        if (! is_string($value)) {
                            $fail('Use a relative site URL or an http/https link.');

                            return;
                        }

                        $isLocalPath = str_starts_with($value, '/') && ! str_starts_with($value, '//') && ! str_contains($value, '\\');
                        $parts = parse_url($value);
                        $isWebUrl = is_array($parts)
                            && in_array(strtolower($parts['scheme'] ?? ''), ['http', 'https'], true)
                            && ! empty($parts['host']);

                        if (! $isLocalPath && ! $isWebUrl) {
                            $fail('Use a relative site URL or an http/https link.');
                        }
                    };
                }

                $rules["content.fields.{$sectionKey}.{$fieldKey}"] = $fieldRules;
            }
        }

        $validated = $request->validate($rules);
        $existing = CmsPage::query()->where('page_key', $pageKey)->first();
        $oldMedia = [];
        $content = ['fields' => [], 'sections' => []];

        foreach ($definition['sections'] as $sectionKey => $section) {
            $sectionInput = data_get($validated, "content.sections.{$sectionKey}", []);
            $content['sections'][$sectionKey] = [
                'order' => ($definition['reorderable'] ?? false)
                    ? (int) $sectionInput['order']
                    : $section['order'],
            ];
            if ($section['optional'] ?? false) {
                $content['sections'][$sectionKey]['enabled'] = $request->boolean("content.sections.{$sectionKey}.enabled");
            }

            foreach ($section['fields'] as $fieldKey => $field) {
                if ($field['type'] !== 'image') {
                    $content['fields'][$sectionKey][$fieldKey] = $validated['content']['fields'][$sectionKey][$fieldKey];
                    continue;
                }

                $oldPath = data_get($existing?->content, "fields.{$sectionKey}.{$fieldKey}");
                $upload = $request->file("media.{$sectionKey}.{$fieldKey}");
                if ($upload) {
                    $newPath = $upload->store("cms/pages/{$pageKey}", 'public');
                    if (! is_string($newPath) || $newPath === '') {
                        throw new RuntimeException('Unable to store the uploaded page image.');
                    }

                    $content['fields'][$sectionKey][$fieldKey] = $newPath;
                    if (is_string($oldPath) && $oldPath !== '') {
                        $oldMedia[] = $oldPath;
                    }
                } elseif ($request->boolean("remove_media.{$sectionKey}.{$fieldKey}")) {
                    $content['fields'][$sectionKey][$fieldKey] = null;
                    if (is_string($oldPath) && $oldPath !== '') {
                        $oldMedia[] = $oldPath;
                    }
                } else {
                    $content['fields'][$sectionKey][$fieldKey] = $oldPath ?: ($field['default'] ?? null);
                }
            }
        }

        CmsPage::query()->updateOrCreate(['page_key' => $pageKey], ['content' => $content]);
        $pageContent->clear($pageKey);

        foreach ($oldMedia as $path) {
            if (! Storage::disk('public')->delete($path)) {
                throw new RuntimeException('Unable to remove a replaced page image.');
            }
        }

        return redirect()
            ->route('admin.pages.edit', $pageKey)
            ->with('status', 'Page content updated.');
    }

    private function definition(string $pageKey): array
    {
        $definitions = config('cms.pages');
        abort_unless(isset($definitions[$pageKey]), 404);

        return $definitions[$pageKey];
    }
}

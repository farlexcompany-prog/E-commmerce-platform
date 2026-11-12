<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RuntimeException;

class AdminProductController extends Controller
{
    public function create(): View
    {
        return view('admin.catalog.products.form', [
            'product' => new Product,
            'categories' => $this->categories(),
            'dosageForms' => $this->dosageForms(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedProductData($request);
        foreach (['image' => 'products/images', 'video' => 'products/videos'] as $field => $directory) {
            if ($request->hasFile($field)) {
                $data[$field.'_path'] = $this->storeProductMedia($request, $field, $directory);
            }
        }

        Product::query()->create($data);

        return redirect()
            ->route('admin.catalog.products')
            ->with('status', 'Product added to the catalog.');
    }

    public function edit(Product $product): View
    {
        return view('admin.catalog.products.form', [
            'product' => $product,
            'categories' => $this->categories(),
            'dosageForms' => $this->dosageForms(),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $product->update($this->validatedProductData($request, $product));

        return redirect()
            ->route('admin.catalog.products')
            ->with('status', 'Product details updated.');
    }

    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:active,inactive'],
            'rx' => ['nullable', 'in:required,otc'],
            'stock' => ['nullable', 'in:available,low,out'],
            'sort' => ['nullable', 'in:newest,name,price-low,price-high,stock'],
        ]);

        $query = Product::query();

        if (! empty($filters['q'])) {
            $term = trim($filters['q']);
            $query->where(function ($builder) use ($term) {
                $builder->where('name', 'like', '%'.$term.'%')
                    ->orWhere('brand', 'like', '%'.$term.'%')
                    ->orWhere('manufacturer', 'like', '%'.$term.'%')
                    ->orWhere('category', 'like', '%'.$term.'%');
            });
        }

        if (! empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        if (($filters['status'] ?? '') === 'active') {
            $query->where('active', true);
        } elseif (($filters['status'] ?? '') === 'inactive') {
            $query->where('active', false);
        }

        if (($filters['rx'] ?? '') === 'required') {
            $query->where('rx_required', true);
        } elseif (($filters['rx'] ?? '') === 'otc') {
            $query->where('rx_required', false);
        }

        if (($filters['stock'] ?? '') === 'available') {
            $query->where('stock', '>', 0);
        } elseif (($filters['stock'] ?? '') === 'low') {
            $query->where('stock', '>', 0)->where('stock', '<=', 5);
        } elseif (($filters['stock'] ?? '') === 'out') {
            $query->where('stock', 0);
        }

        switch ($filters['sort'] ?? 'newest') {
            case 'name':
                $query->orderBy('name');
                break;
            case 'price-low':
                $query->orderBy('price');
                break;
            case 'price-high':
                $query->orderByDesc('price');
                break;
            case 'stock':
                $query->orderBy('stock')->orderBy('name');
                break;
            default:
                $query->orderByDesc('updated_at');
                break;
        }

        $products = $query->paginate(15)->withQueryString();
        $categories = Product::query()
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');
        $stats = [
            'total' => Product::query()->count(),
            'active' => Product::query()->where('active', true)->count(),
            'rx' => Product::query()->where('active', true)->where('rx_required', true)->count(),
            'low_stock' => Product::query()->where('active', true)->whereBetween('stock', [1, 5])->count(),
        ];

        return view('admin.catalog.products.index', compact('products', 'categories', 'filters', 'stats'));
    }

    private function validatedProductData(Request $request, ?Product $product = null): array
    {
        $slug = $request->input('slug');
        if ($slug === null || $slug === '') {
            $name = $request->input('name');
            $slug = is_string($name) ? Str::slug($name) : '';
        } elseif (is_string($slug)) {
            $slug = Str::slug($slug);
        }

        $request->merge(['slug' => $slug]);

        $uniqueSlug = Rule::unique('products', 'slug');
        if ($product) {
            $uniqueSlug->ignore($product->id);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', $uniqueSlug],
            'description' => ['required', 'string', 'max:5000'],
            'category' => ['required', 'string', 'max:80'],
            'brand' => ['required', 'string', 'max:100'],
            'manufacturer' => ['nullable', 'string', 'max:160'],
            'pack_size' => ['nullable', 'string', 'max:120'],
            'dosage_form' => ['required', 'string', 'max:60'],
            'rx_required' => ['required', 'boolean'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'mrp' => ['nullable', 'numeric', 'min:0', 'gte:price', 'max:99999999.99'],
            'stock' => ['required', 'integer', 'min:0', 'max:2147483647'],
            'image_url' => ['nullable', 'url', 'max:255'],
            'image' => $product
                ? ['prohibited']
                : ['required', 'image', 'mimes:jpg,jpeg,png,webp,avif', 'max:5120'],
            'video' => $product
                ? ['prohibited']
                : ['required', 'file', 'mimetypes:video/mp4,application/mp4,video/webm,video/quicktime', 'max:20480'],
            'art_style' => ['required', 'in:tablet,capsule,bottle,tube,vial,supplement,jar,device'],
            'active' => ['sometimes', 'boolean'],
            'featured' => ['sometimes', 'boolean'],
            'details' => ['nullable', 'array'],
            'details.composition' => ['nullable', 'string', 'max:3000'],
            'details.uses' => ['nullable', 'string', 'max:3000'],
            'details.dosage' => ['nullable', 'string', 'max:3000'],
            'details.side_effects' => ['nullable', 'string', 'max:3000'],
            'details.warnings' => ['nullable', 'string', 'max:3000'],
            'details.storage' => ['nullable', 'string', 'max:3000'],
            'details.faqs' => ['nullable', 'string', 'max:3000'],
        ]);

        $validated['active'] = $request->boolean('active');
        $validated['featured'] = $request->boolean('featured');
        $validated['rx_required'] = $request->boolean('rx_required');
        $validated['details'] = array_filter(
            $validated['details'] ?? [],
            fn ($value) => filled($value),
        );

        return $validated;
    }

    private function storeProductMedia(Request $request, string $field, string $directory): string
    {
        $path = $request->file($field)->store($directory, 'public');
        if (! is_string($path) || $path === '') {
            throw new RuntimeException('Unable to store the uploaded product '.$field.'.');
        }

        return $path;
    }

    private function categories(): \Illuminate\Support\Collection
    {
        return Product::query()
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');
    }

    private function dosageForms(): \Illuminate\Support\Collection
    {
        return Product::query()
            ->whereNotNull('dosage_form')
            ->distinct()
            ->orderBy('dosage_form')
            ->pluck('dosage_form');
    }
}

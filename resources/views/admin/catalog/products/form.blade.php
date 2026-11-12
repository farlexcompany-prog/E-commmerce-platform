@extends('admin.layout')

@php
    $isEditing = $product->exists;
    $selectedRx = (string) old('rx_required', $product->exists ? (int) $product->rx_required : 1);
@endphp

@section('title', $isEditing ? 'Edit product' : 'Add product')
@section('section_title', $isEditing ? 'Edit product' : 'Add product')

@section('content')
    <div class="admin-breadcrumbs">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span aria-hidden="true">/</span>
        <a href="{{ route('admin.catalog.products') }}">Catalog</a>
        <span aria-hidden="true">/</span>
        <span>{{ $isEditing ? 'Edit product' : 'Add product' }}</span>
    </div>

    <div class="admin-page-heading">
        <div>
            <p class="eyebrow">Catalog management</p>
            <h1>{{ $isEditing ? 'Edit product' : 'Add a product' }}</h1>
            <p>{{ $isEditing ? 'Update the product information shown in your catalog.' : 'Enter approved product information for your catalog.' }}</p>
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

    <form class="admin-product-form" method="POST" enctype="multipart/form-data" action="{{ $isEditing ? route('admin.catalog.products.update', $product) : route('admin.catalog.products.store') }}">
        @csrf
        @if ($isEditing)
            @method('PUT')
        @endif

        <section class="admin-product-form-section">
            <div class="admin-product-form-heading">
                <h2>Product information</h2>
                <p>Product name, category, brand, and packaging.</p>
            </div>
            <div class="admin-product-form-grid">
                <div class="admin-field">
                    <label for="product-name">Product name *</label>
                    <input id="product-name" name="name" value="{{ old('name', $product->name) }}" maxlength="255" required>
                    @error('name')<span class="admin-field-error">{{ $message }}</span>@enderror
                </div>
                <div class="admin-field">
                    <label for="product-slug">Product URL slug</label>
                    <input id="product-slug" name="slug" value="{{ old('slug', $product->slug) }}" maxlength="255" placeholder="Auto-generated from product name">
                    <span class="admin-field-hint">Leave blank to generate a clean URL from the product name.</span>
                    @error('slug')<span class="admin-field-error">{{ $message }}</span>@enderror
                </div>
                <div class="admin-field">
                    <label for="product-category">Category *</label>
                    <input id="product-category" name="category" list="product-categories" value="{{ old('category', $product->category) }}" maxlength="80" required>
                    <datalist id="product-categories">
                        @foreach ($categories as $category)
                            <option value="{{ $category }}">
                        @endforeach
                    </datalist>
                    @error('category')<span class="admin-field-error">{{ $message }}</span>@enderror
                </div>
                <div class="admin-field">
                    <label for="product-brand">Brand *</label>
                    <input id="product-brand" name="brand" value="{{ old('brand', $product->brand) }}" maxlength="100" required>
                    @error('brand')<span class="admin-field-error">{{ $message }}</span>@enderror
                </div>
                <div class="admin-field">
                    <label for="product-manufacturer">Manufacturer</label>
                    <input id="product-manufacturer" name="manufacturer" value="{{ old('manufacturer', $product->manufacturer) }}" maxlength="160">
                    @error('manufacturer')<span class="admin-field-error">{{ $message }}</span>@enderror
                </div>
                <div class="admin-field">
                    <label for="product-form">Dosage form *</label>
                    <input id="product-form" name="dosage_form" list="product-dosage-forms" value="{{ old('dosage_form', $product->dosage_form) }}" maxlength="60" required>
                    <datalist id="product-dosage-forms">
                        @foreach ($dosageForms as $dosageForm)
                            <option value="{{ $dosageForm }}">
                        @endforeach
                    </datalist>
                    @error('dosage_form')<span class="admin-field-error">{{ $message }}</span>@enderror
                </div>
                <div class="admin-field">
                    <label for="product-pack-size">Pack size</label>
                    <input id="product-pack-size" name="pack_size" value="{{ old('pack_size', $product->pack_size) }}" maxlength="120" placeholder="e.g. 10 tablets">
                    @error('pack_size')<span class="admin-field-error">{{ $message }}</span>@enderror
                </div>
            </div>
        </section>

        @unless ($isEditing)
            <section class="admin-product-form-section">
                <div class="admin-product-form-heading">
                    <h2>Required product photos and video</h2>
                    <p>Upload both product files to create the listing. They will appear on the storefront product page.</p>
                </div>
                <div class="admin-product-form-grid">
                    <div class="admin-field">
                        <label for="product-image">Product photo *</label>
                        <input id="product-image" name="image" type="file" accept=".jpg,.jpeg,.png,.webp,.avif,image/jpeg,image/png,image/webp,image/avif" required>
                        <span class="admin-field-hint">JPEG, PNG, WebP, or AVIF; maximum 5 MB.</span>
                        @error('image')<span class="admin-field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="admin-field">
                        <label for="product-video">Product video *</label>
                        <input id="product-video" name="video" type="file" accept=".mp4,.webm,.mov,video/mp4,video/webm,video/quicktime" required>
                        <span class="admin-field-hint">MP4, WebM, or MOV; maximum 20 MB.</span>
                        @error('video')<span class="admin-field-error">{{ $message }}</span>@enderror
                    </div>
                </div>
            </section>
        @endunless

        <section class="admin-product-form-section">
            <div class="admin-product-form-heading">
                <h2>Price and inventory</h2>
                <p>Set the selling price, MRP, available stock, and package illustration.</p>
            </div>
            <div class="admin-product-form-grid">
                <div class="admin-field">
                    <label for="product-price">Selling price (₹) *</label>
                    <input id="product-price" name="price" type="number" min="0" max="99999999.99" step="0.01" value="{{ old('price', $product->price) }}" required>
                    @error('price')<span class="admin-field-error">{{ $message }}</span>@enderror
                </div>
                <div class="admin-field">
                    <label for="product-mrp">MRP (₹)</label>
                    <input id="product-mrp" name="mrp" type="number" min="0" max="99999999.99" step="0.01" value="{{ old('mrp', $product->mrp) }}">
                    <span class="admin-field-hint">MRP must be equal to or greater than the selling price.</span>
                    @error('mrp')<span class="admin-field-error">{{ $message }}</span>@enderror
                </div>
                <div class="admin-field">
                    <label for="product-stock">Stock quantity *</label>
                    <input id="product-stock" name="stock" type="number" min="0" step="1" value="{{ old('stock', $product->exists ? $product->stock : 0) }}" required>
                    @error('stock')<span class="admin-field-error">{{ $message }}</span>@enderror
                </div>
                <div class="admin-field">
                    <label for="product-art-style">Demo package illustration *</label>
                    <select id="product-art-style" name="art_style" required>
                        @foreach (['tablet' => 'Tablet box', 'capsule' => 'Capsule box', 'bottle' => 'Bottle', 'tube' => 'Tube', 'vial' => 'Vial', 'supplement' => 'Supplement pack', 'jar' => 'Jar', 'device' => 'Device'] as $artStyle => $label)
                            <option value="{{ $artStyle }}" @selected(old('art_style', $product->art_style ?: 'tablet') === $artStyle)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('art_style')<span class="admin-field-error">{{ $message }}</span>@enderror
                </div>
            </div>
        </section>

        <section class="admin-product-form-section">
            <div class="admin-product-form-heading">
                <h2>Description and medicine details</h2>
                <p>These details appear on the product page. Enter only verified, product-specific information.</p>
            </div>
            <div class="admin-product-form-grid">
                <div class="admin-field is-full">
                    <label for="product-description">Product description *</label>
                    <textarea id="product-description" name="description" rows="4" maxlength="5000" required>{{ old('description', $product->description) }}</textarea>
                    @error('description')<span class="admin-field-error">{{ $message }}</span>@enderror
                </div>
                <div class="admin-field">
                    <label for="product-composition">Composition / salt</label>
                    <textarea id="product-composition" name="details[composition]" rows="3" maxlength="3000">{{ old('details.composition', $product->details['composition'] ?? '') }}</textarea>
                    @error('details.composition')<span class="admin-field-error">{{ $message }}</span>@enderror
                </div>
                <div class="admin-field">
                    <label for="product-uses">Uses</label>
                    <textarea id="product-uses" name="details[uses]" rows="3" maxlength="3000">{{ old('details.uses', $product->details['uses'] ?? '') }}</textarea>
                    @error('details.uses')<span class="admin-field-error">{{ $message }}</span>@enderror
                </div>
                <div class="admin-field">
                    <label for="product-dosage">Dosage and how to use</label>
                    <textarea id="product-dosage" name="details[dosage]" rows="3" maxlength="3000">{{ old('details.dosage', $product->details['dosage'] ?? '') }}</textarea>
                    @error('details.dosage')<span class="admin-field-error">{{ $message }}</span>@enderror
                </div>
                <div class="admin-field">
                    <label for="product-side-effects">Side effects</label>
                    <textarea id="product-side-effects" name="details[side_effects]" rows="3" maxlength="3000">{{ old('details.side_effects', $product->details['side_effects'] ?? '') }}</textarea>
                    @error('details.side_effects')<span class="admin-field-error">{{ $message }}</span>@enderror
                </div>
                <div class="admin-field">
                    <label for="product-warnings">Warnings and precautions</label>
                    <textarea id="product-warnings" name="details[warnings]" rows="3" maxlength="3000">{{ old('details.warnings', $product->details['warnings'] ?? '') }}</textarea>
                    @error('details.warnings')<span class="admin-field-error">{{ $message }}</span>@enderror
                </div>
                <div class="admin-field">
                    <label for="product-storage">Storage</label>
                    <textarea id="product-storage" name="details[storage]" rows="3" maxlength="3000">{{ old('details.storage', $product->details['storage'] ?? '') }}</textarea>
                    @error('details.storage')<span class="admin-field-error">{{ $message }}</span>@enderror
                </div>
                <div class="admin-field is-full">
                    <label for="product-faqs">Frequently asked questions</label>
                    <textarea id="product-faqs" name="details[faqs]" rows="3" maxlength="3000">{{ old('details.faqs', $product->details['faqs'] ?? '') }}</textarea>
                    @error('details.faqs')<span class="admin-field-error">{{ $message }}</span>@enderror
                </div>
            </div>
        </section>

        <section class="admin-product-form-section">
            <div class="admin-product-form-heading">
                <h2>Publishing and compliance</h2>
                <p>New products are saved as inactive until their information has been reviewed.</p>
            </div>
            <div class="admin-checkbox-grid">
                <label class="admin-checkbox">
                    <input type="hidden" name="rx_required" value="0">
                    <input type="checkbox" name="rx_required" value="1" @checked($selectedRx === '1')>
                    <span>Prescription required (Rx)</span>
                </label>
                <label class="admin-checkbox">
                    <input type="checkbox" name="active" value="1" @checked((bool) old('active', $product->exists ? $product->active : false))>
                    <span>Active on storefront</span>
                </label>
                <label class="admin-checkbox">
                    <input type="checkbox" name="featured" value="1" @checked((bool) old('featured', $product->featured ?? false))>
                    <span>Featured product</span>
                </label>
            </div>
            <div class="admin-product-review-note">
                Verify the description, composition, Rx status, dosage, warnings, and manufacturer details against approved product information with a registered pharmacist before activating a medicine.
            </div>
        </section>

        <div class="admin-product-form-actions">
            <a class="admin-secondary-button" href="{{ route('admin.catalog.products') }}">Cancel</a>
            <button class="admin-primary-button" type="submit">{{ $isEditing ? 'Save changes' : 'Create product' }}</button>
        </div>
    </form>
@endsection

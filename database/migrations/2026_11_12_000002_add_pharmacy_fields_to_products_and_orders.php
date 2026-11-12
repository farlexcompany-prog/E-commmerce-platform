<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('category')->default('Medicines');
            $table->string('brand')->default('Demo brand');
            $table->string('manufacturer')->nullable();
            $table->string('pack_size')->nullable();
            $table->string('dosage_form')->default('Other');
            $table->boolean('rx_required')->default(false);
            $table->decimal('mrp', 10, 2)->nullable();
            $table->decimal('rating', 2, 1)->default(0);
            $table->unsignedInteger('review_count')->default(0);
            $table->string('art_style')->default('box');
            $table->json('details')->nullable();
            $table->boolean('featured')->default(false);
            $table->unsignedInteger('stock')->default(0);
            $table->index(['active', 'category']);
            $table->index(['active', 'rx_required']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('customer_phone', 20)->nullable();
            $table->string('pincode', 6)->nullable();
            $table->string('payment_method')->nullable();
            $table->decimal('mrp_total', 10, 2)->default(0);
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->string('coupon_code')->nullable();
            $table->string('prescription_path')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'customer_phone',
                'pincode',
                'payment_method',
                'mrp_total',
                'delivery_fee',
                'discount',
                'coupon_code',
                'prescription_path',
            ]);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['active', 'category']);
            $table->dropIndex(['active', 'rx_required']);
            $table->dropColumn([
                'category',
                'brand',
                'manufacturer',
                'pack_size',
                'dosage_form',
                'rx_required',
                'mrp',
                'rating',
                'review_count',
                'art_style',
                'details',
                'featured',
                'stock',
            ]);
        });
    }
};

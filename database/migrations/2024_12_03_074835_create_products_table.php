<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('small_desc');
            $table->longText('description')->nullable();
            $table->boolean('status')->default(1);
            $table->string('sku');
            $table->string('available_for')->nullable();
            $table->string('views')->default(0);
            $table->foreignId('category_id')->references('id')->on('categories')->cascadeOnDelete();
            $table->foreignId('brand_id')->nullable()->references('id')->on('brands')->cascadeOnDelete();
            ###########################
            $table->boolean('has_variant')->default(0);
            $table->decimal('price', 10, 2)->nullable();
            $table->boolean('has_discount')->default(0);
            $table->decimal('discount', 10, 2)->nullable();
            $table->date('start_discount')->nullable();
            $table->date('end_discount')->nullable();
            $table->boolean('manage_stock')->default(0);
            $table->integer('qty')->nullable();
            $table->integer('available_in_stock')->default(1);
            $table->string('meta_title')->nullable();
            $table->string('meta_desc')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

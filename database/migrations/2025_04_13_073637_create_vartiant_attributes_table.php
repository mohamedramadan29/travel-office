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
        Schema::create('vartiant_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_vartiant_id')->references('id')->on('product_vartiants')->cascadeOnDelete();
            $table->foreignId('attribute_value_id')->references('id')->on('attribute_values')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vartiant_attributes');
    }
};

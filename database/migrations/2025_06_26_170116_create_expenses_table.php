<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->references('id')->on('expence_categories')->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->double('price',8,2)->default(0);
            $table->longText('description')->nullable();
            $table->foreignId('safe_id')->nullable()->references('id')->on('safes')->nullOnDelete();
            $table->foreignId('admin_id')->nullable()->references('id')->on('admins')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};

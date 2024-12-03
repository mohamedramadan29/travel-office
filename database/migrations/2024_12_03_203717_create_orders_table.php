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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->string('user_name');
            $table->string('user_email');
            $table->string('user_phone');
            $table->decimal('price',10,2);
            $table->decimal('shipping_price',10,2)->default(0);
            $table->decimal('total_price',10,2)->default(0);
            $table->text('not')->nullable();
            $table->enum('status',['pending','completed','cancelled','delivered'])->default('pending');
            $table->string('country');
            $table->string('government');
            $table->string('city');
            $table->string('street');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

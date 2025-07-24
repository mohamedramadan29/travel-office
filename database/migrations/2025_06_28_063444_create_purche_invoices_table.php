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
        Schema::create('purche_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('bayan_txt');
            $table->string('referance_number')->nullable();
            $table->string('qyt')->nullable();
            $table->double('purches_price',8,2)->nullable();
            $table->double('total_price',8,2)->nullable();
            $table->foreignId('supplier_id')->references('id')->on('suppliers')->cascadeOnDelete();
            $table->string('payment_method')->nullable();
            $table->integer('safe_id')->nullable();
            $table->double('paid',8,2)->default(0);
            $table->double('remaining',8,2)->default(0);
            $table->foreignId('admin_id')->references('id')->on('admins')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->references('id')->on('categories')->cascadeOnDelete();
            ##################################################### Not Used Untill Now ##############
            $table->double('selling_price',8,2)->nullable();
            $table->foreignId('client_id')->nullable()->references('id')->on('clients')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purche_invoices');
    }
};

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
        Schema::create('sale_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purche_id')->nullable()->references('id')->on('purche_invoices')->nullOnDelete();
            $table->string('type')->nullable();
            $table->string('bayan_txt');
            $table->string('referance_number')->nullable();
            $table->string('qyt')->nullable();
            $table->double('selling_price',8,2)->nullable();
            $table->double('total_price',8,2)->nullable();
            $table->foreignId('supplier_id')->nullable()->references('id')->on('suppliers')->nullOnDelete();
            $table->string('payment_method')->nullable();
            $table->integer('safe_id')->nullable();
            $table->double('paid',8,2)->default(0);
            $table->double('remaining',8,2)->default(0);
            $table->foreignId('admin_id')->nullable()->references('id')->on('admins')->nullOnDelete();
            $table->foreignId('category_id')->nullable()->references('id')->on('categories')->nullOnDelete();
            $table->foreignId('client_id')->nullable()->references('id')->on('clients')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_invoices');
    }
};

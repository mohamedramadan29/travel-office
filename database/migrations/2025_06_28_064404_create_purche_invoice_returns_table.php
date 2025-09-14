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
        Schema::create('purche_invoice_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purche_invoice_id')->references('id')->on('purche_invoices')->cascadeOnDelete();
            $table->string('type');
            $table->string('bayan_txt');
            $table->string('referance_number')->nullable();
            $table->string('qyt')->nullable();
            $table->double('purches_price',8,2)->nullable();
            $table->double('total_price',8,2)->nullable();
            $table->foreignId('supplier_id')->nullable()->references('id')->on('suppliers')->nullOnDelete();
            $table->string('payment_method')->nullable();
            $table->integer('safe_id')->nullable();
            $table->double('paid',8,2)->default(0);
            $table->double('remaining',8,2)->default(0);
            $table->foreignId('admin_id')->nullable()->references('id')->on('admins')->nullOnDelete();
            $table->foreignId('category_id')->nullable()->references('id')->on('categories')->nullOnDelete();
            $table->double('return_price',8,2);
            $table->double('additional_profit',8,2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purche_invoice_returns');
    }
};

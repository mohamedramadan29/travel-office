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
        Schema::create('safe_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('safe_id')->references('id')->on('safes')->cascadeOnDelete();
            $table->integer('client_id')->nullable();
            $table->integer('supplier_id')->nullable();
            $table->integer('sale_invoice_id')->nullable();
            $table->integer('purchase_invoice_id')->nullable();
            $table->integer('expense_category_id')->nullable();
            $table->integer('income_service_category_id')->nullable();
            $table->double('amount',8,2);
            $table->enum('type',['withdraw', 'deposit']);
            $table->string('payment_method')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('safe_transactions');
    }
};

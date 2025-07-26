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
        Schema::create('supplier_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->references('id')->on('suppliers')->cascadeOnDelete();
            $table->foreignId('purchase_invoice_id')->references('id')->on('purche_invoices')->cascadeOnDelete();
            $table->double('amount',8,2);
            $table->enum('type',['credit', 'debit']); // credit (مستحق للمورد), debit (مدفوع للمورد)
            $table->string('payment_method')->nullable();
            $table->foreignId('safe_id')->nullable()->references('id')->on('safes')->nullOnDelete();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_transactions');
    }
};

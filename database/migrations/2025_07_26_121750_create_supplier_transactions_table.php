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
            $table->integer('purchase_invoice_id')->nullable();
            $table->double('amount',8,2);
            $table->enum('type',['credit', 'debit']); // credit (مستحق للمورد), debit (مدفوع للمورد)
            $table->string('payment_method')->nullable();
            $table->integer('safe_id')->nullable();
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

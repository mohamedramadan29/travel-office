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
        Schema::create('client_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->references('id')->on('clients')->cascadeOnDelete();
            $table->integer('sale_invoice_id')->nullable();
            $table->double('amount',8,2);
            $table->enum('type',['credit', 'debit']); // credit (مدفوع من العميل), debit (مستحق من العميل)
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
        Schema::dropIfExists('client_transactions');
    }
};

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
            $table->string('qyt')->nullable();
            $table->double('selling_price')->nullable();
            $table->double('purches_price')->nullable();
            $table->double('total_price')->nullable();
            $table->foreignId('client_id')->nullable()->references('id')->on('clients')->cascadeOnDelete();
            $table->foreignId('supplier_id')->references('id')->on('suppliers')->cascadeOnDelete();
            $table->string('referance_number')->nullable();
            $table->string('payment_method');
            $table->string('paid');
            $table->string('remaining');
            $table->foreignId('admin_id')->references('id')->on('admins')->cascadeOnDelete();
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

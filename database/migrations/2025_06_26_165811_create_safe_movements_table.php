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
        Schema::create('safe_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('safe_id')->references('id')->on('safes')->cascadeOnDelete();
            $table->double('amount', 8, 2)->default(0);
            $table->tinyInteger('admin_id');
            $table->enum('movment_type',['deposit','withdraw']);
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('safe_movements');
    }
};

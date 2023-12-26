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
        Schema::create('warehouse_product', function (Blueprint $table) {
            $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete(); 
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity'); 
            $table->boolean('is_active')->default(true);
            $table->primary('warehouse_id', 'product_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_product');
    }
};

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
        Schema::create('quote_materials', function (Blueprint $table) {
            $table->foreignId('quote_id')->references('id')->on('quotes');
            $table->foreignId('product_id')->references('id')->on('products');
            $table->primary(['quote_id', 'product_id']);
            $table->double('quantity');
            $table->double('unit_price');
            $table->double('total_price');
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quote_materials');
    }
};

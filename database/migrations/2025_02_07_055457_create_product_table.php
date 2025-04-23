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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('prod_name');
            $table->string('prod_reference')->nullable();
            $table->string('prod_des')->nullable();
            $table->foreignId('provider_id')->references('id')->on('providers');
            $table->integer('prod_status')->default(1);
            $table->double('prod_price_purchase');
            $table->double('prod_price_sales');
            $table->string('prod_image')->nullable();
            $table->integer('status')->default(1);
            $table->string('money_exchange');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

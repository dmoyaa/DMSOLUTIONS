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
        Schema::create('extra_costs', function (Blueprint $table) {
            $table->id(); // equivale a id INT AUTO_INCREMENT PRIMARY KEY
            $table->foreignId('quote_id')->references('id')->on('quotes');
            $table->string('name', 100); // VARCHAR(100) NOT NULL
            $table->decimal('unit_price', 10, 2); // DECIMAL(10, 2) NOT NULL
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extra_costs');
    }
};

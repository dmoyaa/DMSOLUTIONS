<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('tax_rates', function (Blueprint $table) {
            $table->id(); // equivale a bigIncrements('id')
            $table->string('tax_name');
            $table->decimal('tax_rate', 5, 2); // Ej: 19.00 = 19%
            $table->date('valid_from'); // desde qué fecha es válido este impuesto
            $table->timestamps(); // incluye created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_rates');
    }
};

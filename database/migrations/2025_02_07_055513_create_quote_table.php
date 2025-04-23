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
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_client_id')->references('id')->on('clients');
            $table->double('quote_material_total');
            $table->double('quote_estimated_time');
            $table->integer('quote_helpers');
            $table->double('quote_helper_payday');
            $table->double('quote_supervisor_payday');
            $table->double('quote_work_total');
            $table->double('quote_other_costs_total');
            $table->double('quote_subtotal');
            $table->double('quote_iva');
            $table->double('quote_total');
            $table->date('quote_expiration_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};

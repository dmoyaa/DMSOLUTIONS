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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_id')->references('id')->on('quotes');
            $table->string('proj_name');
            $table->date('proj_start_date');
            $table->date('proj_end_date');
            $table->dateTime('proj_visit')->nullable();
            $table->double('proj_deposit');
            $table->date('proj_warranty')->nullable();
            $table->foreignId('status_id')->references('id')->on('statuses');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};

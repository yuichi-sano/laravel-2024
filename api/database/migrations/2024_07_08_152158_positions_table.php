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
        Schema::create('positions', function(Blueprint $table){
            $table->id('position_id');
            $table->foreignId('project_id');
            $table->foreignId('product_id');
            $table->string('direction');
            $table->string('record');
            $table->string('place');
            $table->string('column');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};

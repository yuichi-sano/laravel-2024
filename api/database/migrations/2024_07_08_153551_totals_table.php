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
        Schema::create('totals', function(Blueprint $table){
            $table->id('total_id');
            $table->foreignId('project_id');
            $table->foreignId('scaffold_id');
            $table->string('total_name');
            $table->integer('total_value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExist('totals');
    }
};

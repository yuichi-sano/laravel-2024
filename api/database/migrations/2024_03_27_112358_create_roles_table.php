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
        Schema::create('roles', function (Blueprint $table) {
            $table->comment('権限');
            $table->increments('id');
            $table->string('name');
            $table->string('description')->nullable()->comment('説明');
            $table->timestamps();
        });


        Schema::create('users', function (Blueprint $table) {
            $table->comment('ユーザー');
            $table->increments('id');
            $table->string('cognito_sub')->unique()->comment('認証ID');
            $table->string('name_family');
            $table->string('name_first');
            $table->string('name_family_kana')->nullable();
            $table->string('name_first_kana')->nullable();
            $table->string('tel')->nullable();
            $table->boolean('is_active')->default(false);
            $table->enum('state', ['not_invited', 'invited', 'confirmed']);
            $table->timestamps();
        });

        Schema::create('user_roles', function (Blueprint $table) {
            $table->comment('ユーザー権限');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id', 'FK_user_roles_1')->references('id')->on('users');
            $table->unsignedInteger('role_id');
            $table->foreign('role_id', 'FK_user_roles_2')->references('id')->on('roles');
            $table->primary('user_id','role_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
    }
};

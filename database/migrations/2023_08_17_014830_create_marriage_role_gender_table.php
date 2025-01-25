<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marriage_role_gender', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('role_id');
            $table->integer('gender_id');
            $table->foreign('role_id')
                ->references('id')
                ->on('marriage_roles')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('gender_id')
                ->references('id')
                ->on('genders')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unique(['role_id', 'gender_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marriage_role_gender');
    }
};

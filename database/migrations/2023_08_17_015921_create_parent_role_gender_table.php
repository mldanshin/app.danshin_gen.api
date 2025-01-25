<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parent_role_gender', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('gender_id');
            $table->foreign('gender_id')
                ->references('id')
                ->on('genders')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer('parent_id');
            $table->foreign('parent_id')
                ->references('id')
                ->on('parent_roles')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unique(['gender_id', 'parent_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parent_role_gender');
    }
};

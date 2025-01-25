<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marriages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('person1_id');
            $table->foreign('person1_id')
                ->references('id')
                ->on('people')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unsignedBigInteger('person2_id');
            $table->foreign('person2_id')
                ->references('id')
                ->on('people')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer('role_scope_id');
            $table->foreign('role_scope_id')
                ->references('id')
                ->on('marriage_role_scope')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestamps();
            $table->unique(['person1_id', 'person2_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marriages');
    }
};

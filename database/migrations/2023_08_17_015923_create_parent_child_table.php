<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parent_child', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id');
            $table->foreign('parent_id')
                ->references('id')
                ->on('people')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unsignedBigInteger('child_id');
            $table->foreign('child_id')
                ->references('id')
                ->on('people')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer('parent_role_id');
            $table->foreign('parent_role_id')
                ->references('id')
                ->on('parent_roles')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestamps();
            $table->unique(['parent_id', 'child_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parent_child');
    }
};

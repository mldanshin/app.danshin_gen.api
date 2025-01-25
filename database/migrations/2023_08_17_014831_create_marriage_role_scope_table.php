<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marriage_role_scope', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('role1_id')->unique();
            $table->integer('role2_id')->unique();
            $table->foreign('role1_id')
                ->references('id')
                ->on('marriage_roles')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('role2_id')
                ->references('id')
                ->on('marriage_roles')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marriage_role_scope');
    }
};

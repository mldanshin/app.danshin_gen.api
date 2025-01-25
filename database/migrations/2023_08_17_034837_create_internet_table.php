<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internet', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('url');
            $table->string('name');
            $table->timestamps();
            $table->unique(['person_id', 'url']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internet');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('phones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->char('name', 20);
            $table->timestamps();
            $table->unique('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phones');
    }
};

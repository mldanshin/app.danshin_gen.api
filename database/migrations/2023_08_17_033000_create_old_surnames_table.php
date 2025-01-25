<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('old_surnames', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('surname');
            $table->integer('order');
            $table->timestamps();
            $table->unique(['person_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('old_surnames');
    }
};

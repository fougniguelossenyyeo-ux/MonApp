<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
      public function up(): void
    {
         Schema::create('demandes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('denomination');
            $table->string('entite');
            $table->string('Montantdemande');
            $table->string('date');
            $table->string('contatc');
            $table->string('email');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
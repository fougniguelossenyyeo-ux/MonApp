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
        Schema::create('entites', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('libelle_entite');
            $table->string('logo')->nullable(); // nouveau champ pour le logo
            // Flag pour "suppression" visible à l'utilisateur

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entites');
    }
};

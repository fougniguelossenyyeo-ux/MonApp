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
        Schema::create('permissions', function (Blueprint $table) {
            $table->uuid('id')->primary();             // UUID pour chaque permission
            $table->foreignUuid('entite_id')           // ← AJOUT UNIQUEMENT CE CHAMP
                  ->constrained('entites')
                  ->onDelete('cascade')
                  ->comment('Entité à laquelle cette permission appartient');
            $table->string('nom')->unique();           // Nom de la permission (ex: creer_demande)
            $table->string('description')->nullable(); // Description détaillée
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
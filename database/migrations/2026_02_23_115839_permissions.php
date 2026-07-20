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
            $table->uuid('id')->primary();                        // UUID unique
            $table->foreignUuid('entite_id')                      // Lien vers entites
                ->nullable()                                   // Permet de garder la permission si l'entité est "supprimée"
                ->constrained('entites')
                ->nullOnDelete();                              // On ne supprime pas, on met null
            $table->string('nom');                                // Nom de la permission
            $table->string('description')->nullable();           // Description
            $table->boolean('is_deleted')->default(false);       // Flag pour suppression logique
            $table->timestamps();

            // Empêche doublons pour la même entité
            $table->unique(['nom', 'entite_id']);
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

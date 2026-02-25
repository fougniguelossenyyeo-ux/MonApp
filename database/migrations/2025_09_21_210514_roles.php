<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('libelle'); // Exemple: caissiere, controleur, daf, dg, admin
            $table->foreignUuid('entite_id')->nullable()->constrained('entites')->nullOnDelete();
            $table->boolean('super_admin')->default(false);
            $table->timestamps();
            

            // Optionnel: empêcher les doublons par entité
            $table->unique(['libelle', 'entite_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};

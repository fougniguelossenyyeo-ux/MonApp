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
        Schema::create('paiement_versements', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID pour l'identifiant
            $table->uuid('paiement_id'); // Référence vers le paiement parent
            $table->integer('nombre_versements')->default(0);
            $table->decimal('montant', 15, 2); // Montant versé
            $table->timestamp('date_versement')->useCurrent(); // Date du versement
            $table->string('commentaire')->nullable(); // Commentaire optionnel
            $table->string('statut_versement')->default('en_attente'); // valeurs possibles : valide, refuse et en attente

            $table->timestamps();

            // Clé étrangère vers paiements
             $table->foreignUuid('paiement_id')
          ->constrained('paiements')  // pointe vers paiements.id
          ->cascadeOnDelete();        // supprime les versements si paiement supprimé
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiement_versements');
    }
};

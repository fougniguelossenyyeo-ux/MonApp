<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiement_versements', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID pour l'identifiant
            $table->foreignUuid('paiement_id') // Référence vers le paiement parent
                ->constrained('paiements')  // pointe vers paiements.id
                ->cascadeOnDelete();        // supprime les versements si paiement supprimé

            $table->integer('nombre_versements')->default(0);
            $table->decimal('montant', 15, 2); // Montant versé
            $table->timestamp('date_versement')->useCurrent(); // Date du versement
            $table->string('commentaire')->nullable(); // Commentaire optionnel
            $table->string('statut_versement')->default('en_attente'); // valeurs possibles : valide, refuse et en attente
            $table->string('mode_paiement')->nullable(); // Mode de paiement (ex: carte, virement, etc.)
            $table->string('motif_refus_versement')->nullable(); // Motif du refus du versement
            $table->boolean('is_deleted')->default(false);
            $table->text('refuse_par')->nullable(); // Nouveau champ pour stocker qui a refusé le versement
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiement_versements');
    }
};

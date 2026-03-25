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
   Schema::create('users', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->string('nom');
    $table->string('prenom');
    $table->string('email', 191)->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('poste')->nullable();
    $table->string('password');
    $table->string('signature', 255)->nullable();

    // Entité principale
    $table->uuid('entite_id')->nullable();
    $table->foreign('entite_id')->references('id')->on('entites')->onDelete('set null');

    // Rôle unique
    $table->foreignUuid('role_id')->nullable()->constrained('roles')->nullOnDelete();

    $table->rememberToken();
    $table->timestamps();

});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       
        Schema::dropIfExists('users');
    }
};

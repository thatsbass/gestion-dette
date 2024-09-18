<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('demandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->integer('montant');
            $table->enum('etat', ['Encours', 'Annuler', 'Valider'])->default('Encours');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demandes');
    }
};

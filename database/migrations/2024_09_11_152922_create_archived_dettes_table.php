<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('archived_dettes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dette_id');
            $table->unsignedBigInteger('client_id');
            $table->decimal('montant', 10, 2);
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('dette_id')->references('id')->on('dettes')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archived_dettes');
    }
};

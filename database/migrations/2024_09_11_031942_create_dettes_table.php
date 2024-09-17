<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("dettes", function (Blueprint $table) {
            $table->id();
            $table->integer("montant");
            $table->foreignId("client_id")->constrained("clients")->onDelete('cascade'); 
            $table->enum("statut", ["pending", "paid"])->default("pending");
            $table->timestamp("limit_at")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("dettes");
    }
};

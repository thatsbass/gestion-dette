<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create("clients", function (Blueprint $table) {
            $table->id();
            $table->string("surnom")->unique();
            $table->string("adresse");
            $table->string("telephone")->unique();
            $table
                ->foreignId("user_id")
                ->nullable()
                ->constrained("users")
                ->nullOnDelete();
            $table
                ->foreignId("category_id")
                ->constrained("categories")
                ->default(3);
            $table->decimal("max_montant", 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("clients");
    }
};

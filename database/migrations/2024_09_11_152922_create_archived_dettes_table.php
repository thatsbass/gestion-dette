<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create("archived_dettes", function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("dette_id");
            $table
                ->foreign("dette_id")
                ->references("id")
                ->on("dettes")
                ->onDelete("cascade");
            $table->timestamp("archived_at")->nullable();
            $table->timestamp("restored_at")->nullable();
            $table->string("cloud_from")->nullable()->default("unknown");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("archived_dettes");
    }
};

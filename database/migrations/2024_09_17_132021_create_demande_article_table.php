<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create("article_demande", function (Blueprint $table) {
            $table->id();
            $table->foreignId("demande_id")->constrained()->onDelete("cascade");
            $table->foreignId("article_id")->constrained()->onDelete("cascade");
            $table->integer("quantity");
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists("article_demande");
    }
};

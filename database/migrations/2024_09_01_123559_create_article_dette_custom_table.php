<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleDetteCustomTable extends Migration
{
    public function up()
    {
        Schema::create('article_dette', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->onDelete('cascade'); // Un article peut se trouver dans plusieurs dettes
            $table->foreignId('dette_id')->constrained()->onDelete('cascade'); // Une dette peut avoir plusieurs articles
            $table->integer('qteVente');
            $table->decimal('prixVente', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('article_dette');
    }
}


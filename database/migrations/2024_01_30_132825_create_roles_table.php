<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('libelle')->unique(); // Le nom du rôle, par exemple 'admin', 'user', etc.
            $table->timestamps();
        });

      
    }

    public function down()
    {


        Schema::dropIfExists('roles');
    }
}


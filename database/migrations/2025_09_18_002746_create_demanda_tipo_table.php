<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDemandaTipoTable extends Migration
{
    public function up()
    {
        Schema::create('demanda_tipo', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['Melhoria', 'Sugestão', 'Correção', 'Novidade', 'Problema'])->unique();
            $table->string('descricao');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('demanda_tipo');
    }
}


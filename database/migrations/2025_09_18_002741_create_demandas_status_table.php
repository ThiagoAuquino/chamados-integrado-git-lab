<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDemandasStatusTable extends Migration
{
    public function up()
    {
        Schema::create('demandas_status', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['em_branco', 'analise', 'em_execucao', 'em_testes', 'validacao', 'entregue'])->unique();
            $table->string('descricao');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('demandas_status');
    }
}


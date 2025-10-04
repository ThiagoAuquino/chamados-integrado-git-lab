<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDemandaObservacaoTable extends Migration
{
    public function up()
    {
        Schema::create('demanda_observacao', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demanda_id')->constrained('demandas')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('observacao');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('demanda_observacao');
    }
}

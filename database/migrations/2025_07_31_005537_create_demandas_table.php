<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('demandas', function (Blueprint $table) {
            $table->id();
            $table->string('produto');
            $table->string('chamado')->nullable();
            $table->text('descricao');
            $table->enum('tipo', ['bug', 'melhoria', 'funcionalidade']);
            $table->date('data_previsao');
            $table->string('cliente');
            $table->unsignedBigInteger('responsavel_id')->nullable();
            $table->enum('status', ['em_branco', 'analise', 'em_execucao', 'em_testes', 'validacao', 'entregue'])->default('em_branco');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demandas');
    }
};

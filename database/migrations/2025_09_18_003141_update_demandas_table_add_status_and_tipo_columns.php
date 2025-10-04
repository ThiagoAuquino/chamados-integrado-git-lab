<?php

// database/migrations/xxxx_xx_xx_update_demandas_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDemandasTableAddStatusAndTipoColumns extends Migration
{
    public function up()
    {
        Schema::table('demandas', function (Blueprint $table) {
            $table->foreignId('status_id')->constrained('demandas_status')->onDelete('cascade');
            $table->foreignId('tipo_id')->constrained('demanda_tipo')->onDelete('cascade');
            $table->dropColumn(['status', 'tipo']);
        });
    }

    public function down()
    {
        Schema::table('demandas', function (Blueprint $table) {
            $table->dropForeign(['status_id']);
            $table->dropForeign(['tipo_id']);
            $table->enum('status', ['em_branco', 'analise', 'em_execucao', 'em_testes', 'validacao', 'entregue']);
            $table->enum('tipo', ['Melhoria', 'Sugestão', 'Correção', 'Novidade', 'Problema']);
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriorityToDemandasTable extends Migration
{
    public function up(): void
    {
        Schema::table('demandas', function (Blueprint $table) {
            $table->enum('priority', ['verde', 'amarelo', 'laranja', 'vermelho'])->default('verde')->after('status');
            $table->integer('order')->default(0)->after('priority');
            
            $table->index(['status', 'priority', 'order']);
        });
    }

    public function down(): void
    {
        Schema::table('demandas', function (Blueprint $table) {
            $table->dropColumn(['priority', 'order']);
        });
    }
}
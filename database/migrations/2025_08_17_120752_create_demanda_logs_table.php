<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDemandaLogsTable extends Migration
{
    public function up(): void
    {
        Schema::create('demanda_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demanda_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('action'); // 'created', 'updated', 'status_changed', 'assigned', etc.
            $table->string('field_changed')->nullable();
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
            $table->text('description')->nullable();
            $table->timestamp('created_at');
            
            $table->index(['demanda_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demanda_logs');
    }
}
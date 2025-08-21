<?php

namespace App\Models\Demanda;

use Illuminate\Database\Eloquent\Model;

class Demanda extends Model
{
    protected $table = 'demandas';

    protected $fillable = [
        'produto',
        'chamado',
        'descricao',
        'tipo',
        'data_previsao',
        'cliente',
        'responsavel_id',
        'status',
        'prioridade',
    ];

    protected $casts = [
        'data_previsao' => 'date',
    ];
}

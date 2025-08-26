<?php

namespace App\Models\Demanda;

use App\Models\DemandaLog;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demanda extends Model
{
    use HasFactory;

    protected $fillable = [
        'produto',
        'chamado',
        'descricao',
        'tipo',
        'data_previsao',
        'cliente',
        'responsavel_id',
        'status',
        'priority',
        'order',
        'prioridade',
    ];

    protected $casts = [
        'data_previsao' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function logs()
    {
        return $this->hasMany(DemandaLog::class);
    }

    public function responsavel()
    {
        return $this->belongsTo(User::class, 'responsavel_id');
    }
}

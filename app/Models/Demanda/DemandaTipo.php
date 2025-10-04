<?php

namespace App\Models\Demanda;

use App\Models\Demanda\Demanda;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DemandaTipo extends Model
{
    protected $table = 'demanda_tipo';

    protected $fillable = [
        'tipo',
        'descricao',
    ];

    public $timestamps = true;

    /**
     * Relacionamento com demandas.
     */
    public function demandas(): HasMany
    {
        return $this->hasMany(Demanda::class, 'tipo_id');
    }
}

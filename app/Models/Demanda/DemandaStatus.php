<?php

namespace App\Models\Demanda;

use App\Models\Demanda\Demanda;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DemandaStatus extends Model
{
    protected $table = 'demandas_status';

    protected $fillable = [
        'status',
        'descricao',
    ];

    public $timestamps = true;

    /**
     * Relacionamento com demandas.
     */
    public function demandas(): HasMany
    {
        return $this->hasMany(Demanda::class, 'status_id');
    }
}

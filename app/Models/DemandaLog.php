<?php

namespace App\Models;

use App\Models\Demanda\Demanda;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandaLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'demanda_id',
        'user_id',
        'action',
        'field_changed',
        'old_value',
        'new_value',
        'description',
        'created_at',
    ];

    public $timestamps = false;

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function demanda()
    {
        return $this->belongsTo(Demanda::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

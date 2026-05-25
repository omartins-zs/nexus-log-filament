<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Models\Concerns\HasActivity;
use Spatie\Activitylog\Support\LogOptions;

class Recebimento extends Model
{
    use HasActivity;

    protected $fillable = [
        'codigo_nfe',
        'fornecedor',
        'data_recebimento',
        'status',
        'observacoes',
    ];

    protected $casts = [
        'data_recebimento' => 'datetime',
    ];

    public function lotes(): HasMany
    {
        return $this->hasMany(Lote::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }
}

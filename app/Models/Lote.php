<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Models\Concerns\HasActivity;
use Spatie\Activitylog\Support\LogOptions;

class Lote extends Model
{
    use HasActivity;

    protected $fillable = [
        'produto_id',
        'recebimento_id',
        'endereco_id',
        'codigo_lote',
        'data_fabricacao',
        'data_validade',
        'quantidade_inicial',
        'quantidade_atual',
    ];

    protected $casts = [
        'data_fabricacao' => 'date',
        'data_validade' => 'date',
    ];

    public function endereco(): BelongsTo
    {
        return $this->belongsTo(Endereco::class);
    }

    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class);
    }

    public function recebimento(): BelongsTo
    {
        return $this->belongsTo(Recebimento::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }
}

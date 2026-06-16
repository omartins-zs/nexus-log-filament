<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Models\Concerns\HasActivity;
use Spatie\Activitylog\Support\LogOptions;

class Produto extends Model
{
    /** @use HasFactory<\Database\Factories\ProdutoFactory> */
    use HasFactory, HasActivity;

    public function lotes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Lote::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    protected $fillable = [
        'cliente_id',
        'nome',
        'sku',
        'peso',
        'altura',
        'largura',
        'comprimento',
        'quantidade_estoque',
        'embalagem_id',
        'cor',
        'linha',
        'tipo_tinta',
        'codigo_barras',
        'unidade_medida',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function embalagem()
    {
        return $this->belongsTo(Embalagem::class);
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }
}

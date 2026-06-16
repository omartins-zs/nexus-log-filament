<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Embalagem extends Model
{
    use HasFactory;

    protected $table = 'embalagens';

    protected $fillable = [
        'nome',
        'tipo',
        'capacidade_litros',
        'codigo_barras',
        'material',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'capacidade_litros' => 'decimal:2',
    ];

    public function produtos(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Produto::class);
    }
}

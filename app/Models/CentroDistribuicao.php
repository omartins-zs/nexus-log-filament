<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentroDistribuicao extends Model
{
    /** @use HasFactory<\Database\Factories\CentroDistribuicaoFactory> */
    use HasFactory;

    protected $table = 'centro_distribuicoes';

    protected $fillable = [
        'nome',
        'codigo_interno',
        'cidade',
        'estado',
        'endereco',
    ];

    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }
}

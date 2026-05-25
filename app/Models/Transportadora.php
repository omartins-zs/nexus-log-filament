<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transportadora extends Model
{
    /** @use HasFactory<\Database\Factories\TransportadoraFactory> */
    use HasFactory;

    protected $fillable = [
        'nome',
        'cnpj',
        'email',
        'telefone',
        'prazo_medio_entrega',
        'valor_base_frete',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }
}

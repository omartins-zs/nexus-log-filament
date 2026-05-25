<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    /** @use HasFactory<\Database\Factories\ClienteFactory> */
    use HasFactory;

    protected $fillable = [
        'nome',
        'cnpj',
        'email',
        'telefone',
        'endereco',
    ];

    public function produtos()
    {
        return $this->hasMany(Produto::class);
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }
}

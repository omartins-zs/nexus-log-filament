<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\PedidoStatus;
use Spatie\Activitylog\Models\Concerns\HasActivity;
use Spatie\Activitylog\Support\LogOptions;

class Pedido extends Model
{
    /** @use HasFactory<\Database\Factories\PedidoFactory> */
    use HasFactory, HasActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    protected $fillable = [
        'cliente_id',
        'centro_distribuicao_id',
        'produto_id',
        'transportadora_id',
        'quantidade',
        'valor_total',
        'status',
        'codigo_rastreio',
        'data_pedido',
        'data_envio',
    ];

    protected $casts = [
        'status' => PedidoStatus::class,
        'data_pedido' => 'datetime',
        'data_envio' => 'datetime',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function centroDistribuicao()
    {
        return $this->belongsTo(CentroDistribuicao::class);
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    public function transportadora()
    {
        return $this->belongsTo(Transportadora::class);
    }
}

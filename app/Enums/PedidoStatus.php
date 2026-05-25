<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PedidoStatus: string implements HasLabel, HasColor
{
    case PENDENTE = 'pendente';
    case EM_SEPARACAO = 'em_separacao';
    case CONFERIDO = 'conferido';
    case AGUARDANDO_EXPEDICAO = 'aguardando_expedicao';
    case EXPEDIDO = 'expedido';
    case ENTREGUE = 'entregue';
    case CANCELADO = 'cancelado';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PENDENTE => 'Pendente',
            self::EM_SEPARACAO => 'Em Separação',
            self::CONFERIDO => 'Conferido',
            self::AGUARDANDO_EXPEDICAO => 'Aguardando Expedição',
            self::EXPEDIDO => 'Expedido',
            self::ENTREGUE => 'Entregue',
            self::CANCELADO => 'Cancelado',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::PENDENTE => 'gray',
            self::EM_SEPARACAO => 'warning',
            self::CONFERIDO => 'info',
            self::AGUARDANDO_EXPEDICAO => 'primary',
            self::EXPEDIDO => 'purple',
            self::ENTREGUE => 'success',
            self::CANCELADO => 'danger',
        };
    }
}

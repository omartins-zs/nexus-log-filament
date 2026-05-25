<?php

namespace App\Filament\Resources\Recebimentos\Pages;

use App\Filament\Resources\Recebimentos\RecebimentoResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditRecebimento extends EditRecord
{
    protected static string $resource = RecebimentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('finalizar')
                ->label('Finalizar Recebimento')
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->requiresConfirmation()
                ->modalHeading('Finalizar e Atualizar Estoque')
                ->modalDescription('Tem certeza? Isso irá atualizar o estoque de todos os produtos com base nos lotes inseridos e travará o recebimento.')
                ->visible(fn ($record) => $record->status !== 'concluido')
                ->action(function ($record) {
                    \Illuminate\Support\Facades\DB::transaction(function () use ($record) {
                        foreach ($record->lotes as $lote) {
                            $produto = $lote->produto;
                            $produto->quantidade_estoque += $lote->quantidade_inicial;
                            $produto->save();
                        }
                        $record->update(['status' => 'concluido']);
                    });
                    
                    \Filament\Notifications\Notification::make()
                        ->title('Recebimento Finalizado')
                        ->body('O estoque dos produtos foi atualizado com sucesso.')
                        ->success()
                        ->send();
                }),
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}

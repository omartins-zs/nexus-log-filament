<?php

namespace App\Filament\Resources\Produtos\Tables;

use App\Models\Produto;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;

class ProdutosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('cliente.nome')
                    ->label('Cliente Proprietário')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nome')
                    ->label('Nome do Produto')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('quantidade_estoque')
                    ->label('Estoque')
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => $state < 10 ? 'danger' : ($state < 50 ? 'warning' : 'success')),
                TextColumn::make('peso')
                    ->label('Peso (Kg)')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('altura')
                    ->label('Altura (cm)')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('largura')
                    ->label('Largura (cm)')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('comprimento')
                    ->label('Comprimento (cm)')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Cadastrado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                \pxlrbt\FilamentExcel\Actions\Tables\ExportAction::make()
                    ->label('Exportar Todos (Relatório)')
                    ->icon('heroicon-o-document-arrow-down')
                    ->exports([
                        \pxlrbt\FilamentExcel\Exports\ExcelExport::make('excel')->fromTable()->withFilename('produtos_'.date('Y-m-d'))->withWriterType(\Maatwebsite\Excel\Excel::XLSX)->label('Exportar Excel (.xlsx)'),
                        \pxlrbt\FilamentExcel\Exports\ExcelExport::make('csv')->fromTable()->withFilename('produtos_'.date('Y-m-d'))->withWriterType(\Maatwebsite\Excel\Excel::CSV)->label('Exportar CSV (.csv)'),
                        \pxlrbt\FilamentExcel\Exports\ExcelExport::make('pdf')->fromTable()->withFilename('produtos_'.date('Y-m-d'))->withWriterType(\Maatwebsite\Excel\Excel::DOMPDF)->label('Exportar PDF (.pdf)'),
                    ]),
            ])
            ->recordActions([
                Action::make('ajustar_estoque')
                    ->label('Ajustar Estoque')
                    ->icon('heroicon-o-arrows-up-down')
                    ->color('warning')
                    ->form([
                        Select::make('tipo')
                            ->label('Tipo de Movimentação')
                            ->options([
                                'entrada' => 'Entrada (+)',
                                'saida' => 'Saída (-)',
                            ])
                            ->required(),
                        TextInput::make('quantidade')
                            ->label('Quantidade')
                            ->numeric()
                            ->required()
                            ->minValue(1),
                    ])
                    ->action(function (Produto $record, array $data): void {
                        $quantidade = intval($data['quantidade']);
                        if ($data['tipo'] === 'entrada') {
                            $record->increment('quantidade_estoque', $quantidade);
                        } else {
                            if ($record->quantidade_estoque < $quantidade) {
                                Notification::make()
                                    ->title('Erro no Ajuste')
                                    ->body('A quantidade de saída é maior que o saldo em estoque!')
                                    ->danger()
                                    ->send();
                                return;
                            }
                            $record->decrement('quantidade_estoque', $quantidade);
                        }

                        Notification::make()
                            ->title('Estoque Ajustado')
                            ->body("Estoque do produto '{$record->nome}' ajustado com sucesso!")
                            ->success()
                            ->send();
                    }),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    \pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction::make()
                        ->label('Exportar Selecionados')
                        ->icon('heroicon-o-document-arrow-down')
                        ->exports([
                            \pxlrbt\FilamentExcel\Exports\ExcelExport::make('excel')->fromTable()->withFilename('produtos_lote_'.date('Y-m-d'))->withWriterType(\Maatwebsite\Excel\Excel::XLSX)->label('Exportar Excel (.xlsx)'),
                            \pxlrbt\FilamentExcel\Exports\ExcelExport::make('csv')->fromTable()->withFilename('produtos_lote_'.date('Y-m-d'))->withWriterType(\Maatwebsite\Excel\Excel::CSV)->label('Exportar CSV (.csv)'),
                            \pxlrbt\FilamentExcel\Exports\ExcelExport::make('pdf')->fromTable()->withFilename('produtos_lote_'.date('Y-m-d'))->withWriterType(\Maatwebsite\Excel\Excel::DOMPDF)->label('Exportar PDF (.pdf)'),
                        ]),
                ]),
            ]);
    }
}

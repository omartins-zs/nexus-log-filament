<?php

namespace App\Filament\Resources\Pedidos\Tables;

use App\Models\Pedido;
use App\Models\Transportadora;
use App\Enums\PedidoStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PedidosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('cliente.nome')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('centroDistribuicao.nome')
                    ->label('CD Origem')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('produto.nome')
                    ->label('Produto')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('quantidade')
                    ->label('Qtd')
                    ->sortable(),
                TextColumn::make('valor_total')
                    ->label('Total')
                    ->money('BRL')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable(),
                TextColumn::make('transportadora.nome')
                    ->label('Transportadora')
                    ->placeholder('Sem transportadora')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('codigo_rastreio')
                    ->label('Rastreamento')
                    ->placeholder('Aguardando')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('data_pedido')
                    ->label('Data Pedido')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('data_envio')
                    ->label('Data Envio')
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
                        \pxlrbt\FilamentExcel\Exports\ExcelExport::make('excel')->fromTable()->withFilename('pedidos_'.date('Y-m-d'))->withWriterType(\Maatwebsite\Excel\Excel::XLSX)->label('Exportar Excel (.xlsx)'),
                        \pxlrbt\FilamentExcel\Exports\ExcelExport::make('csv')->fromTable()->withFilename('pedidos_'.date('Y-m-d'))->withWriterType(\Maatwebsite\Excel\Excel::CSV)->label('Exportar CSV (.csv)'),
                        \pxlrbt\FilamentExcel\Exports\ExcelExport::make('pdf')->fromTable()->withFilename('pedidos_'.date('Y-m-d'))->withWriterType(\Maatwebsite\Excel\Excel::DOMPDF)->label('Exportar PDF (.pdf)'),
                    ]),
            ])
            ->recordActions([
                // Action "Imprimir Etiqueta"
                Action::make('imprimir_etiqueta')
                    ->label('Etiqueta')
                    ->icon('heroicon-o-qr-code')
                    ->color('success')
                    ->button()
                    ->action(function (Pedido $record) {
                        return response()->streamDownload(function () use ($record) {
                            // Gerar QR code em base64 SVG para não depender do Imagick no Dompdf
                            $qrCode = base64_encode(
                                QrCode::format('svg')
                                    ->size(100)
                                    ->margin(1)
                                    ->generate("NEXUS-PED-{$record->id}")
                            );

                            // Gerar Código de Barras 1D (Code 128) linear usando a biblioteca Picqer
                            $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
                            $barcodeVal = $record->codigo_rastreio ?? "NEXUS-PED-{$record->id}";
                            $barcode = base64_encode(
                                $generator->getBarcode($barcodeVal, $generator::TYPE_CODE_128, 2, 45)
                            );

                            // Buscar a Rota de Coleta (Endereços do produto) com FEFO
                            $lotes = \App\Models\Lote::with('endereco')
                                ->where('produto_id', $record->produto_id)
                                ->where('quantidade_atual', '>', 0)
                                ->where(function ($query) {
                                    $query->whereNull('data_validade')
                                          ->orWhere('data_validade', '>=', now()->startOfDay());
                                })
                                ->orderBy('data_validade', 'asc') // FEFO: Vence primeiro sai primeiro
                                ->get()
                                ->sortBy(fn($l) => $l->endereco ? $l->endereco->corredor . $l->endereco->estante : 'Z'); // Depois por corredor apenas para agrupamento, mas validade domina

                            // Para a impressão, vamos priorizar 100% o FEFO na Rota.
                            // Refazer sort para FEFO absoluto no PDF
                            $lotesFefo = $lotes->sortBy('data_validade');

                            $rotasArray = [];
                            foreach ($lotesFefo as $l) {
                                if ($l->endereco) {
                                    $rotasArray[] = "{$l->endereco->corredor}-{$l->endereco->estante}-{$l->endereco->nivel}";
                                }
                            }
                            $rotaColetaStr = count($rotasArray) > 0 ? implode(', ', array_unique($rotasArray)) : 'SEM ENDEREÇO';

                            $pdf = Pdf::loadView('pdf.etiqueta', [
                                'pedido' => $record,
                                'qrCode' => $qrCode,
                                'barcode' => $barcode,
                                'rotaColeta' => $rotaColetaStr,
                            ]);
                            echo $pdf->output();
                        }, "etiqueta-pedido-{$record->id}.pdf");
                    }),

                // Action "Expedir" (Modal)
                Action::make('expedir')
                    ->label('Expedir')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('info')
                    ->button()
                    ->visible(fn (Pedido $record) => in_array($record->status, [
                        PedidoStatus::PENDENTE,
                        PedidoStatus::EM_SEPARACAO,
                        PedidoStatus::CONFERIDO,
                        PedidoStatus::AGUARDANDO_EXPEDICAO
                    ]))
                    ->form([
                        Select::make('transportadora_id')
                            ->label('Transportadora')
                            ->options(Transportadora::where('ativo', true)->pluck('nome', 'id'))
                            ->required()
                            ->searchable()
                            ->preload(),
                        TextInput::make('codigo_rastreio')
                            ->label('Código de Rastreio')
                            ->required()
                            ->placeholder('Ex: ALFA-987654321-BR'),
                    ])
                    ->action(function (Pedido $record, array $data): void {
                        $record->update([
                            'transportadora_id' => $data['transportadora_id'],
                            'codigo_rastreio' => $data['codigo_rastreio'],
                            'status' => PedidoStatus::EXPEDIDO,
                            'data_envio' => now(),
                        ]);

                        Notification::make()
                            ->title('Pedido Expedido')
                            ->body("O pedido #{$record->id} foi expedido com sucesso via " . Transportadora::find($data['transportadora_id'])->nome)
                            ->success()
                            ->send();
                    }),

                Action::make('rota_coleta')
                    ->label('Rota de Coleta')
                    ->icon('heroicon-o-map')
                    ->color('warning')
                    ->button()
                    ->modalHeading(fn ($record) => 'Rota de Coleta: Pedido #' . $record->id)
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Fechar')
                    ->modalContent(function ($record) {
                        $lotes = \App\Models\Lote::with('endereco')
                            ->where('produto_id', $record->produto_id)
                            ->where('quantidade_atual', '>', 0)
                            ->where(function ($query) {
                                $query->whereNull('data_validade')
                                      ->orWhere('data_validade', '>=', now()->startOfDay());
                            })
                            ->orderBy('data_validade', 'asc') // FEFO Restrito
                            ->get()
                            ->sortBy(fn($l) => $l->endereco ? $l->endereco->corredor . $l->endereco->estante : 'Z');

                        return view('filament.pages.rota-coleta-modal', [
                            'pedido' => $record,
                            'lotes' => $lotes,
                        ]);
                    }),
                EditAction::make()
                    ->button()
                    ->color('gray'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    \pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction::make()
                        ->label('Exportar Selecionados')
                        ->icon('heroicon-o-document-arrow-down')
                        ->exports([
                            \pxlrbt\FilamentExcel\Exports\ExcelExport::make('excel')->fromTable()->withFilename('pedidos_lote_'.date('Y-m-d'))->withWriterType(\Maatwebsite\Excel\Excel::XLSX)->label('Exportar Excel (.xlsx)'),
                            \pxlrbt\FilamentExcel\Exports\ExcelExport::make('csv')->fromTable()->withFilename('pedidos_lote_'.date('Y-m-d'))->withWriterType(\Maatwebsite\Excel\Excel::CSV)->label('Exportar CSV (.csv)'),
                            \pxlrbt\FilamentExcel\Exports\ExcelExport::make('pdf')->fromTable()->withFilename('pedidos_lote_'.date('Y-m-d'))->withWriterType(\Maatwebsite\Excel\Excel::DOMPDF)->label('Exportar PDF (.pdf)'),
                        ]),
                    \Filament\Actions\BulkAction::make('roteirizar')
                        ->label('Processar Roteirização (Fila)')
                        ->icon('heroicon-o-truck')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            foreach ($records as $record) {
                                \App\Jobs\ProcessarRoteirizacaoJob::dispatch($record);
                            }

                            Notification::make()
                                ->title('Roteirização em Andamento')
                                ->body(count($records) . ' pedidos foram enviados para a fila de processamento assíncrono.')
                                ->success()
                                ->send();
                        }),
                ]),
            ]);
    }
}

<?php

namespace App\Filament\Resources\Recebimentos\RelationManagers;

use App\Models\Produto;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class LotesRelationManager extends RelationManager
{
    protected static string $relationship = 'lotes';

    protected static ?string $recordTitleAttribute = 'codigo_lote';

    protected static ?string $title = 'Lotes Recebidos';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('produto_id')
                    ->label('Produto')
                    ->options(Produto::all()->pluck('nome', 'id'))
                    ->searchable()
                    ->required()
                    ->reactive(),
                Forms\Components\Select::make('endereco_id')
                    ->label('Endereço (Prateleira)')
                    ->options(\App\Models\Endereco::all()->pluck('codigo_barras', 'id'))
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('codigo_lote')
                    ->label('Código do Lote (Opcional)')
                    ->maxLength(255)
                    ->helperText('Se não preenchido, será gerado automaticamente (LOTE-XXXXXXXX)'),
                Forms\Components\TextInput::make('quantidade_inicial')
                    ->label('Quantidade')
                    ->required()
                    ->numeric()
                    ->default(1)
                    ->minValue(1),
                Forms\Components\DatePicker::make('data_fabricacao')
                    ->label('Data de Fabricação'),
                Forms\Components\DatePicker::make('data_validade')
                    ->label('Data de Validade'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('produto.nome')
                    ->label('Produto')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('endereco.codigo_barras')
                    ->label('Endereço (Físico)')
                    ->badge()
                    ->color('warning')
                    ->sortable(),
                Tables\Columns\TextColumn::make('codigo_lote')
                    ->label('Lote')
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantidade_inicial')
                    ->label('Qtd. Inicial')
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantidade_atual')
                    ->label('Qtd. Atual')
                    ->sortable(),
                Tables\Columns\TextColumn::make('data_validade')
                    ->label('Validade')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Adicionar Lote')
                    ->icon('heroicon-o-plus')
                    ->mutateFormDataUsing(function (array $data): array {
                        if (empty($data['codigo_lote'])) {
                            $data['codigo_lote'] = 'LOTE-' . strtoupper(uniqid());
                        }
                        $data['quantidade_atual'] = $data['quantidade_inicial'];
                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}

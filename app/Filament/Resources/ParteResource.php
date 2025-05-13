<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ParteResource\Pages;
use App\Models\Parte;
use Filament\Tables\Columns\ViewColumn;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\{
    Grid, Select, TextInput, Textarea, DatePicker, FileUpload, RichEditor
};
use Filament\Tables\Columns\{
    TextColumn
};
use Filament\Tables\Actions\{
    EditAction, DeleteAction, Action
};

class ParteResource extends Resource
{
    protected static ?string $model = Parte::class;
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?string $navigationGroup = 'Reparaciones';
    protected static ?string $modelLabel = 'Parte de reparación';
    protected static ?string $pluralModelLabel = 'Partes de reparación';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(2)->schema([
                Select::make('cliente_id')
                    ->label('Cliente')
                    ->relationship('cliente', 'nombre')
                    ->searchable()
                    ->required(),

                TextInput::make('nombre_equipo')
                    ->label('Nombre del equipo')
                    ->required(),
            ]),

            Textarea::make('caracteristicas')->label('Características'),
            Textarea::make('problema')->label('Problema detectado'),

            Grid::make(2)->schema([
                DatePicker::make('fecha_entrada')->label('Fecha de entrada')->required(),
                DatePicker::make('fecha_salida')->label('Fecha de salida'),
            ]),

            Grid::make(2)->schema([
                TextInput::make('precio_estimado')->label('Precio estimado (€)')->numeric(),
                TextInput::make('precio_final')->label('Precio final (€)')->numeric(),
            ]),

            Textarea::make('solucion_aplicada')->label('Solución aplicada'),

            Select::make('estado')
                ->label('Estado')
                ->options([
                    'pendiente' => 'Pendiente',
                    'en_proceso' => 'En proceso',
                    'terminado' => 'Terminado',
                    'entregado' => 'Entregado',
                ])
                ->required(),

            RichEditor::make('observaciones')->label('Observaciones'),

            FileUpload::make('imagenes')
                ->label('Imágenes del equipo')
                ->multiple()
                ->image()
                ->directory('imagenes')
                ->visibility('public')
                ->preserveFilenames()
                ->downloadable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cliente.nombre')->label('Cliente')->searchable(),
                TextColumn::make('nombre_equipo')->label('Equipo')->searchable(),
                TextColumn::make('estado')->badge()->label('Estado')->colors([
                    'gray' => 'pendiente',
                    'warning' => 'en_proceso',
                    'success' => 'terminado',
                    'info' => 'entregado',
                ]),
                TextColumn::make('fecha_entrada')->label('Entrada')->date(),
                TextColumn::make('fecha_salida')->label('Salida')->date(),

                // ✅ Columna con botón Ver PDF
                TextColumn::make('ver_pdf')
                    ->label('PDF')
                    ->html()
                    ->formatStateUsing(fn ($record) => '
                        <a href="' . route('admin.partes.pdf', $record->id) . '" 
                           target="_blank"
                           style="display:inline-block;padding:6px 12px;background-color:#2563eb;color:white;border-radius:6px;font-weight:bold;text-decoration:none;font-size:13px;">
                           Ver PDF
                        </a>
                    ')
                    ->alignCenter(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                Action::make('descargar_pdf')
                    ->icon('heroicon-o-printer')
                    ->label('PDF')
                    ->color('info')
                    ->tooltip('Descargar PDF del parte')
                    ->url(fn ($record) => route('admin.partes.pdf', $record->id))
                    ->openUrlInNewTab(),
            ])
            ->defaultSort('fecha_entrada', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPartes::route('/'),
            'create' => Pages\CreateParte::route('/create'),
            'edit' => Pages\EditParte::route('/{record}/edit'),
        ];
    }
}

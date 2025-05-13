<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClienteResource\Pages;
use App\Models\Cliente;
use App\Models\Empresa;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\BulkAction;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Facades\Filament;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\File;

use Filament\Support\Enums\IconSize;

class ClienteResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Cliente::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)->schema([
                    Toggle::make('aprobado')
                        ->label('Aprobado')
                        ->inline(false)
                        ->default(true)
                        ->helperText('Si no está aprobado, el cliente no aparecerá al crear documentos'),
                    Toggle::make('persona_fisica')->label('Persona Física')->inline(false)->default(false)->reactive(),
                ]),

                Grid::make(3)->schema([
                    TextInput::make('dni_cif')->label('DNI / CIF')->required()->maxLength(50),
                    TextInput::make('nombre')->label('Nombre')->required()->maxLength(255),
                    TextInput::make('apellidos')
                        ->label('Apellidos')
                        ->maxLength(255)
                        ->hidden(fn ($get) => !$get('persona_fisica'))
                        ->required(fn ($get) => $get('persona_fisica')),
                ]),

                Grid::make(3)->schema([
                    TextInput::make('marca_comercial')->label('Marca Comercial')->maxLength(255),
                    TextInput::make('nombre_responsable')->label('Nombre Responsable')->maxLength(255),
                    TextInput::make('web')
                        ->label('Página Web')
                        ->url()
                        ->maxLength(255)
                        ->suffixAction(
                            fn ($state) => $state ? 
                                \Filament\Forms\Components\Actions\Action::make('openWebsite')
                                    ->icon('heroicon-o-eye')
                                    ->tooltip('Abrir página web')
                                    ->url(fn() => $state, shouldOpenInNewTab: true)
                                : null
                        ),
                ]),

                Grid::make(3)->schema([
                    TextInput::make('email')
                        ->label('Correo Electrónico')
                        ->email()
                        ->maxLength(255)
                        ->suffixAction(
                            fn ($state) => $state ? 
                                \Filament\Forms\Components\Actions\Action::make('sendEmail')
                                    ->icon('heroicon-o-envelope')
                                    ->tooltip('Enviar correo')
                                    ->url(fn() => "mailto:" . $state, shouldOpenInNewTab: true)
                                : null
                        ),
                    TextInput::make('tlf_1')->label('Teléfono 1')->maxLength(20),
                    TextInput::make('tlf_2')->label('Teléfono 2')->maxLength(20),
                ]),

                Grid::make(3)->schema([
                    TextInput::make('cp')->label('Código Postal')->required()->maxLength(10),
                    TextInput::make('direccion')->label('Dirección')->required()->maxLength(255)->columnSpan(2),
                ]),

                Grid::make(3)->schema([
                    TextInput::make('localidad')->label('Localidad')->maxLength(255),
                    TextInput::make('provincia')->label('Provincia')->required()->maxLength(255),
                    Select::make('pais')
                        ->label('País')
                        ->required()
                        ->options(self::getPaises())
                        ->searchable()
                        ->default('España'),
                ]),

                \Filament\Forms\Components\RichEditor::make('observaciones')
                ->label('Observaciones')
                ->columnSpan('full')
                ->disableToolbarButtons(['attachFiles', 'codeBlock', 'blockquote'])
                ->maxLength(1000)
                ->toolbarButtons([
                    'bold', 'italic', 'underline', 'strike',
                    'link', 'bulletList', 'orderedList',
                    'h2', 'h3', 'paragraph',
                ]),
            ]);
    }

    public static function getPaises(): array
    {
        return [
            'Afganistán' => 'Afganistán',
            'Alemania' => 'Alemania',
            'Argentina' => 'Argentina',
            'Australia' => 'Australia',
            'Bolivia' => 'Bolivia',
            'Brasil' => 'Brasil',
            'Canadá' => 'Canadá',
            'Chile' => 'Chile',
            'China' => 'China',
            'Colombia' => 'Colombia',
            'Costa Rica' => 'Costa Rica',
            'Cuba' => 'Cuba',
            'Ecuador' => 'Ecuador',
            'Egipto' => 'Egipto',
            'España' => 'España',
            'Estados Unidos' => 'Estados Unidos',
            'Francia' => 'Francia',
            'Guatemala' => 'Guatemala',
            'Honduras' => 'Honduras',
            'India' => 'India',
            'Italia' => 'Italia',
            'Japón' => 'Japón',
            'México' => 'México',
            'Panamá' => 'Panamá',
            'Paraguay' => 'Paraguay',
            'Perú' => 'Perú',
            'Portugal' => 'Portugal',
            'Reino Unido' => 'Reino Unido',
            'Uruguay' => 'Uruguay',
            'Venezuela' => 'Venezuela',
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->filters([
                TernaryFilter::make('aprobado')
                    ->label('Aprobado')
                    ->placeholder('Todos')
                    ->trueLabel('Aprobado')
                    ->falseLabel('No aprobado'),
                TernaryFilter::make('persona_fisica')
                    ->label('Persona Física')
                    ->placeholder('Todos')
                    ->trueLabel('Persona Física')
                    ->falseLabel('Persona Jurídica'),
                /*SelectFilter::make('proyecto')
                    ->label('Proyecto')
                    ->relationship('proyectos', 'nombre')
                    ->searchable()
                    ->preload(),*/
            ])
            ->columns([
                IconColumn::make('aprobado')->boolean(),
                IconColumn::make('persona_fisica')->boolean()->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('nombre_completo')
                    ->label('Nombre')
                    ->searchable(['nombre', 'apellidos'])
                    ->getStateUsing(fn ($record) => $record->persona_fisica ? "{$record->nombre} {$record->apellidos}" : $record->nombre),
                TextColumn::make('dni_cif')->searchable()->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('email')->searchable()->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('web')->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('tlf_1')->searchable()->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('tlf_2')->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('direccion')->searchable()->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('cp')->searchable()->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('localidad')->searchable()->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('provincia')->searchable()->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('pais')->searchable()->toggleable(isToggledHiddenByDefault: false),
            ])
            ->actions([
                EditAction::make(),
            
                Action::make('whatsapp')
                ->label('WhatsApp')
                ->icon('heroicon-o-chat-bubble-left-right')
                ->color('success')
                ->url(fn ($record) => $record->generarEnlaceWhatsapp())
                ->openUrlInNewTab()
                ->visible(fn ($record) => $record->boolMostrarBotonWhatsapp()),

                

                /*Action::make('ver_contratos')
                ->label('Contratos')
                ->icon('heroicon-o-document-text')
                ->iconSize(IconSize::Small)
                ->color('primary')
                ->tooltip('Ver contratos de este cliente')
                ->url(fn ($record) => route('filament.admin.resources.contrato-generados.index', [
                    'tableFilters[cliente_id][value]' => $record->id,
                ]))
                ->openUrlInNewTab(),*/

                Action::make('ver_politica_datos')
                ->label('Protección de Datos')
                ->icon('heroicon-o-shield-check')
                ->color('gray')
                ->url(fn ($record) => route('admin.clientes.politica-datos', $record->id))
                ->openUrlInNewTab(),

        
                
                Action::make('ver_politica')
                ->label('Ver Política')
                ->icon('heroicon-o-document-text')
                ->color('gray')
                ->url(fn ($record) => asset("politicas/politica_cliente_{$record->id}.pdf"))
                ->openUrlInNewTab()
                ->visible(fn ($record) => File::exists(public_path("politicas/politica_cliente_{$record->id}.pdf"))),


            
                //->visible(fn ($record) => !empty($record->telefono)),            
                Action::make('email')
                    ->label('')
                    ->icon('heroicon-o-envelope')
                    ->color('gray')
                    ->url(fn ($record) => "mailto:" . $record->email)
                    ->visible(fn ($record) => !empty($record->email)),
                    
                /*Action::make('ver_documentos')
                    ->label('Documentos')
                    ->icon('heroicon-o-document-duplicate')
                    ->iconSize(IconSize::Small)
                    ->color('warning')
                    ->tooltip('Ver documentos de este cliente')
                    ->url(fn ($record) => route('filament.admin.resources.documentos.index', [
                        'tableFilters[cliente_id][value]' => $record->id,
                    ]))
                    ->openUrlInNewTab(),*/
            ])          
            
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    BulkAction::make('exportToExcel')
                        ->label('Exportar a Excel')
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(function ($records) {
                            return self::generateExcel($records);
                        }),
                ]),
            ]);
    }

    public static function getPermissionPrefixes(): array
    {
        return ['view', 'view_any', 'create', 'update', 'delete', 'delete_any'];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();

        $user = auth()->user();

        if ($user && $user->empresa_id) {
            $query->where('empresa_id', $user->empresa_id);
        }

        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClientes::route('/'),
            'create' => Pages\CreateCliente::route('/create'),
            'edit' => Pages\EditCliente::route('/{record}/edit'),
        ];
    }
    
    /**
     * Genera un archivo Excel con los datos de los clientes seleccionados
     *
     * @param \Illuminate\Database\Eloquent\Collection $records
     * @return StreamedResponse
     */
    public static function generateExcel($records): StreamedResponse
    {
        // Crear una respuesta en streaming para descargar el archivo
        return response()->streamDownload(function () use ($records) {
            // Crear un archivo CSV temporal
            $csv = fopen('php://output', 'w');
            
            // Establecer el separador de columnas para Excel
            fprintf($csv, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM para UTF-8
            
            // Encabezados de columnas
            fputcsv($csv, [
                'ID',
                'Aprobado',
                'Persona Física',
                'Nombre',
                'Apellidos',
                'DNI/CIF',
                'Email',
                'Web',
                'Teléfono 1',
                'Teléfono 2',
                'Dirección',
                'CP',
                'Localidad',
                'Provincia',
                'País',
                'Marca Comercial',
                'Nombre Responsable',
            ], ';');
            
            // Datos de los clientes
            foreach ($records as $cliente) {
                fputcsv($csv, [
                    $cliente->id,
                    $cliente->aprobado ? 'Sí' : 'No',
                    $cliente->persona_fisica ? 'Sí' : 'No',
                    $cliente->nombre,
                    $cliente->apellidos,
                    $cliente->dni_cif,
                    $cliente->email,
                    $cliente->web,
                    $cliente->tlf_1,
                    $cliente->tlf_2,
                    $cliente->direccion,
                    $cliente->cp,
                    $cliente->localidad,
                    $cliente->provincia,
                    $cliente->pais,
                    $cliente->marca_comercial,
                    $cliente->nombre_responsable,
                ], ';');
            }
            
            fclose($csv);
        }, 'listado_clientes.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="listado_clientes.csv"',
        ]);
    }
}








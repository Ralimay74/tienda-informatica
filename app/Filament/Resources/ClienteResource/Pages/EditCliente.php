<?php

namespace App\Filament\Resources\ClienteResource\Pages;

use App\Filament\Resources\ClienteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Pages\Actions\Action;

class EditCliente extends EditRecord
{
    protected static string $resource = ClienteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('descargar_politica')
                ->label('Descargar Política de Datos')
                ->icon('heroicon-o-document-arrow-down')
                ->color('secondary')
                ->url(fn () => route('admin.clientes.politica-datos', $this->record->id))
                ->openUrlInNewTab(),
        ];
    }
}

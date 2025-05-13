<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cliente;
use App\Models\Parte;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class DemoClientesYPartesSeeder extends Seeder
{
    public function run(): void
    {
        // Asegurar carpetas
        $dirPoliticas = public_path('politicas');
        $dirImagenes = public_path('imagenes');

        File::ensureDirectoryExists($dirPoliticas);
        File::ensureDirectoryExists($dirImagenes);

        $imagenesDisponibles = File::files(storage_path('app/seed-imagenes'));

        Cliente::factory(3)->create()->each(function ($cliente) use ($dirPoliticas, $dirImagenes, $imagenesDisponibles) {

            // ✅ Generar PDF de política de datos
            $pdf = Pdf::loadView('pdf.politica-datos', compact('cliente'));
            $rutaPdf = $dirPoliticas . "/politica_cliente_{$cliente->id}.pdf";
            $pdf->save($rutaPdf);

            // ✅ Copiar 1 o 2 imágenes al azar
            $imagenesParte = collect($imagenesDisponibles)->random(2)->map(function ($file) use ($dirImagenes) {
                $nombreDestino = Str::uuid() . '.' . $file->getExtension();
                File::copy($file->getPathname(), $dirImagenes . '/' . $nombreDestino);
                return 'imagenes/' . $nombreDestino;
            })->toArray();

            // ✅ Crear parte con imágenes
            Parte::factory()->create([
                'cliente_id' => $cliente->id,
                'imagenes' => $imagenesParte,
            ]);
        });
    }
}

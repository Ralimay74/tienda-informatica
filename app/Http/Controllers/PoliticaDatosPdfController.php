<?php
namespace App\Http\Controllers;

use App\Models\Cliente;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;

class PoliticaDatosPdfController extends Controller
{
    public function generar($id)
    {
        $cliente = Cliente::findOrFail($id);

        $pdf = Pdf::loadView('pdf.politica-datos', compact('cliente'));

        $ruta = public_path("politicas/politica_cliente_{$cliente->id}.pdf");

        // Asegurar que exista la carpeta
        if (!File::exists(public_path('politicas'))) {
            File::makeDirectory(public_path('politicas'), 0755, true);
        }

        $pdf->save($ruta);

        return response()->download($ruta);
    }
}

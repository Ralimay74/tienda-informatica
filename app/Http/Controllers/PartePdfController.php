<?php

namespace App\Http\Controllers;

use App\Models\Parte;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PartePdfController extends Controller
{
    public function descargar($id)
    {
        $parte = Parte::with('cliente')->findOrFail($id);

        $pdf = Pdf::loadView('pdf.parte', compact('parte'));

        return $pdf->download('parte_reparacion_' . $parte->id . '.pdf');
    }
}

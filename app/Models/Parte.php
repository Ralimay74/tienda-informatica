<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Parte extends Model
{

    use HasFactory;
    protected $casts = [
        'imagenes' => 'array',
    ];

    protected $fillable = [
        'cliente_id',
        'nombre_equipo',
        'caracteristicas',
        'problema',
        'fecha_entrada',
        'precio_estimado',
        'precio_final',
        'solucion_aplicada',
        'estado',
        'fecha_salida',
        'observaciones',
        'imagenes',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}

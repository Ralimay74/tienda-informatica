<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Cliente extends Model
{

    use HasFactory;
    protected $fillable = [
        'aprobado',
        'persona_fisica',
        'dni_cif',
        'nombre',
        'apellidos',
        'marca_comercial',
        'nombre_responsable',
        'web',
        'email',
        'tlf_1',
        'tlf_2',
        'direccion',
        'cp',
        'localidad',
        'provincia',
        'pais',
        'observaciones',
        'empresa_id',
    ];

    public function boolMostrarBotonWhatsapp(): bool
    {
        return $this->tlf_1 && !str_starts_with($this->tlf_1, '9');
    }

    public function generarEnlaceWhatsapp(): string
    {
        return 'https://wa.me/34' . preg_replace('/\D/', '', $this->tlf_1);
    }
}

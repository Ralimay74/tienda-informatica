<?php

namespace Database\Factories;

use App\Models\Parte;
use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

class ParteFactory extends Factory
{
    protected $model = Parte::class;

    public function definition(): array
    {
        return [
            'cliente_id' => Cliente::inRandomOrder()->first()->id,
            'nombre_equipo' => $this->faker->word() . ' ' . $this->faker->word(),
            'caracteristicas' => $this->faker->sentence(),
            'problema' => $this->faker->paragraph(),
            'fecha_entrada' => $this->faker->dateTimeBetween('-7 days', 'now'),
            'fecha_salida' => $this->faker->optional()->dateTimeBetween('now', '+7 days'),
            'precio_estimado' => $this->faker->randomFloat(2, 30, 300),
            'precio_final' => $this->faker->randomFloat(2, 40, 350),
            'solucion_aplicada' => $this->faker->sentence(),
            'estado' => $this->faker->randomElement(['pendiente', 'en_proceso', 'terminado', 'entregado']),
            'observaciones' => $this->faker->paragraph(),
            'imagenes' => [],
        ];
    }
}

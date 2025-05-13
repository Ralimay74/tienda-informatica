<?php

namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClienteFactory extends Factory
{
    protected $model = Cliente::class;

    public function definition(): array
    {
        return [
            'aprobado' => true,
            'persona_fisica' => true,
            'dni_cif' => $this->faker->unique()->bothify('########A'),
            'nombre' => $this->faker->firstName(),
            'apellidos' => $this->faker->lastName(),
            'marca_comercial' => $this->faker->company(),
            'nombre_responsable' => $this->faker->name(),
            'web' => $this->faker->url(),
            'email' => $this->faker->safeEmail(),
            'tlf_1' => $this->faker->numerify('6########'),
            'tlf_2' => $this->faker->numerify('7########'),
            'direccion' => $this->faker->streetAddress(),
            'cp' => $this->faker->postcode(),
            'localidad' => $this->faker->city(),
            'provincia' => $this->faker->state(),
            'pais' => 'España',
            'observaciones' => $this->faker->sentence(),
        ];
    }
}

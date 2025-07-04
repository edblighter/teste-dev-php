<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use APP\Models\Supplier;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SupplierFactory extends Factory
{
    protected $model = Supplier::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['CPF', 'CNPJ']);

        return [
            'name' => fake()->company,
            'type' => $type,
            'email' => fake()->unique()->safeEmail,
            'document' => ($type === 'CPF') ? fake('pt_BR')->cpf(false) : fake('pt_BR')->cnpj(false) ,
            'phone' => fake()->numerify('###########'),
        ];
    }
}

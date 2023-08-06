<?php

namespace Database\Factories;

use App\Models\EmployeePermission;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;

class EmployeesPermissionsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = app(Faker::class);

        return [
            'name' => $faker->word,
            'description' => $faker->paragraph,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
<?php

namespace Database\Factories;

use App\Models\Users;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UsersFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Users::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => Str::uuid(),
            'title' => $this->faker->title,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'username' => $this->faker->unique()->userName,
            'password_hash' => bcrypt('password'),
            'avatar' => null,
            'bio' => null,
            'city' => $this->faker->city,
            'country' => $this->faker->country,
            'birth_date' => $this->faker->date(),
            'gender' => $this->faker->randomElement(['male', 'female', 'other']),
            'phone' => $this->faker->phoneNumber,
            'other_phone' => $this->faker->phoneNumber,
            'favorite_dog_breeds' => json_encode($this->faker->randomElements(['Labrador', 'Poodle', 'Bulldog'])),
            'account_status' => $this->faker->randomElement(['active', 'inactive']),
            'email_verified' => $this->faker->boolean,
            'phone_verified' => $this->faker->boolean,
            'created_at' => $this->faker->dateTimeThisYear,
            'updated_at' => $this->faker->optional()->dateTimeThisYear,
            'deleted_at' => $this->faker->optional()->dateTimeThisYear,
        ];
    }
}
<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\EmployeesPermissions;
use App\Models\Department; 
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,
            'middle_name' => $this->faker->optional()->lastName,
            'last_name' => $this->faker->lastName,
            
            'gender' => $this->faker->randomElement(['male', 'female', 'other']),
            
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            
            'emergency_contact_name' => $this->faker->name,
            'emergency_contact_number' => $this->faker->phoneNumber,
            
            'employee_status' => $this->faker->randomElement(['active', 'on_leave', 'resigned', 'terminated']), 
            'position' => $this->faker->jobTitle,
            
            'permission_id' => EmployeesPermissions::factory(),
            
            'date_of_birth' => $this->faker->date(),
            'hire_date' => $this->faker->date(),
            
            'address_1' => $this->faker->streetAddress,
            'address_2' => $this->faker->secondaryAddress,
            'city' => $this->faker->city,
            'postcode' => $this->faker->postcode,
            'country' => $this->faker->country,
            
            'department_id' => Department::factory(),
        ];
    }
}
<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;


class EmployeeTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
       // Generate 50 random employees
       Employee::factory()->count(50)->create();
  }
}
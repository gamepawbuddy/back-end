<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

use Database\Factories\DepartmentFactory;

class DeparmentTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
       // Generate 5 random departments
       Department::factory()->count(5)->create();
  }
}
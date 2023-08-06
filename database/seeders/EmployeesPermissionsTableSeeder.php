<?php

namespace Database\Seeders;

use App\Models\EmployeesPermissions;
use Illuminate\Database\Seeder;


class EmployeesPermissionsTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
       // Generate 2 random employee permissions
       EmployeesPermissions::factory()->count(2)->create();
  }
}
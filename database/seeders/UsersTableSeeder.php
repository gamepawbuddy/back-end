<?php

namespace Database\Seeders;

use App\Models\Users;
use Illuminate\Database\Seeder;

use Database\Factories\UsersFactory;

class UsersTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    // Generate 50 users using model factory
    Users::factory()->count(50)->create();
  }
}
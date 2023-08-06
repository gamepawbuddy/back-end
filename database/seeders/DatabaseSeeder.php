<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class); 
        $this->call(EmployeesPermissionsTableSeeder::class);  
        $this->call(DeparmentTableSeeder::class);   
        $this->call(EmployeeTableSeeder::class);   
        $this->call(DeparmentsTableSeeder::class);  
    }
}
<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;
use Faker\Factory;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        for ($i = 0; $i < 50; $i++) 
        { 
            $this->db->table('employees')->insert($this->generateClient());
        }
    }
    private function generateClient(): array
    {
        $faker = Factory::create();
        $currentDateTime = Time::now();
        return [
            'name' => $faker->name(),
            'email' => $faker->email,
            'phone' => $faker->phoneNumber,
            'address' => $faker->address,
            'created_at' => $currentDateTime,
            'updated_at' => $currentDateTime,
        ];
    }
}

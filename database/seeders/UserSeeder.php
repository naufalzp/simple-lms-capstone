<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');
        /* 
                'username',
                'first_name',
                'last_name',
                'email',
                'password',

                buat 20 user dengan nama indonesia
                 */
        $users = [
            [
                'username' => 'zhafif',
                'first_name' => 'Naufal',
                'last_name' => 'Zhafif',
                'email' => 'zhafif@gmail.com',
                'password' => bcrypt('password')
            ],
        ];

        foreach (range(1, 49) as $index) {
            $users[] = [
                'username' => $faker->unique()->userName,
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->unique()->safeEmail(),
                'password' => bcrypt('password')
            ];
        }

        foreach ($users as $user) {
            \App\Models\User::create($user);
        }
    }
}

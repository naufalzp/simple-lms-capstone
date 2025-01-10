<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseMember;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');

        $userId = User::whereBetween('id', [11, 50])->pluck('id')->toArray();
        
        $courses = Course::all();

        foreach ($courses as $course) {
            $memberCount = rand(10, 30);
            for ($i = 0; $i < $memberCount; $i++) {
                CourseMember::create([
                    'course_id' => $course->id,
                    'user_id' => $faker->randomElement($userId),
                    'roles' => $faker->randomElement(['std', 'ast'])
                ]);
            }
        }
    }
}

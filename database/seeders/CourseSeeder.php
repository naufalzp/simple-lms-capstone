<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');
        $categoryIds = CourseCategory::pluck('id')->toArray();

        for ($i = 0; $i < 20; $i++) {
            Course::create([
                'name' => $faker->sentence(3), 
                'description' => $faker->paragraph, 
                'price' => $faker->numberBetween(100000, 1000000),
                'image' => $faker->imageUrl(640, 480, 'education', true, 'Course'),
                'teacher_id' => $faker->numberBetween(1, 10), 
                'category_id' => $faker->randomElement($categoryIds),
            ]);
        }

    }
}

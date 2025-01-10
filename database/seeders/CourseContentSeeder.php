<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\CourseContent;

class CourseContentSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID');

        $courses = Course::all();

        foreach ($courses as $course) {
            $parentContent = CourseContent::create([
                'name' => $faker->sentence(3),
                'description' => $faker->paragraph,
                'video_url' => $faker->url,
                'file_attachment' => $faker->filePath(),
                'course_id' => $course->id,
                'parent_id' => null,
            ]);

            $childCount = rand(1, 5);
            for ($i = 0; $i < $childCount; $i++) {
                CourseContent::create([
                    'name' => $faker->sentence(3),
                    'description' => $faker->paragraph,
                    'video_url' => $faker->url,
                    'file_attachment' => $faker->filePath(),
                    'course_id' => $course->id,
                    'parent_id' => $parentContent->id,
                ]);
            }
        }
    }
}

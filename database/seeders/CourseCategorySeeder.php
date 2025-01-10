<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // buat category untuk LMS dengan nama indonesia    berjumlah 10 category
/* 
        'name',
        'slug',
        'created_by'
         */

         $categories = [
            [
                'name' => 'Programming',
                'slug' => 'programming',
                'created_by' => 1
            ],
            [
                'name' => 'Design',
                'slug' => 'design',
                'created_by' => 1
            ],
            [
                'name' => 'Business',
                'slug' => 'business',
                'created_by' => 1
            ],
            [
                'name' => 'Marketing',
                'slug' => 'marketing',
                'created_by' => 1
            ],
            [
                'name' => 'Music',
                'slug' => 'music',
                'created_by' => 1
            ],
            [
                'name' => 'Photography',
                'slug' => 'photography',
                'created_by' => 1
            ],
            [
                'name' => 'Health & Fitness',
                'slug' => 'health-fitness',
                'created_by' => 1
            ],
            [
                'name' => 'Personal Development',
                'slug' => 'personal-development',
                'created_by' => 1
            ],
            [
                'name' => 'Lifestyle',
                'slug' => 'lifestyle',
                'created_by' => 1
            ],
            [
                'name' => 'IT & Software',
                'slug' => 'it-software',
                'created_by' => 1
            ],
        ];

        foreach ($categories as $category) {
            \App\Models\CourseCategory::create($category);
        }
    }
}

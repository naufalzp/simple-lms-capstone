<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CourseCategory extends Model
{
    protected $fillable = [
        'name',
        'slug'
    ];

    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = Str::slug($value);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'instructor',
        'status',
        'category_id',
        'objectives',
        'course_content',
        'user_id',
        'image',
        'curriculum',
        'projects',
        'tools',
        'course_content_details',
    ];
    protected $casts = [
        'objectives'      => 'array',
        'course_content'  => 'array',
        'projects'        => 'array',
        'tools'           => 'array',
        'course_content_details' => 'array'
    ];

    // A course belongs to a user (instructor)
    public function instructor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // A course belongs to a category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // A course has many enrollments
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}



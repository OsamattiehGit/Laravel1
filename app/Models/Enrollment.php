<?php

namespace App\Models;
use App\Models\User;
use App\Models\Course;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Enrollment extends Model
{
     use HasFactory;
     protected $fillable = ['course_id', 'user_id'];

   // An enrollment belongs to a user
public function user()
{
    return $this->belongsTo(User::class);
}

// An enrollment belongs to a course
public function course()
{
    return $this->belongsTo(Course::class);
}

}

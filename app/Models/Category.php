<?php

namespace App\Models;
use App\Models\Course;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
        protected $fillable = ['name'];
    // A category has many courses
public function courses()
{
    return $this->hasMany(Course::class);
}

}

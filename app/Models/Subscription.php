<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subscription extends Model
{
    use HasFactory;

 protected $fillable = ['user_id', 'type', 'course_credits'];

public static function getCreditsByType($type) {
    return match($type) {
        'A' => 5,
        'B' => 3,
        'C' => 1,
        default => 0,
    };
}


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model

{
    protected $table = 'complaints';
    protected $fillable = ['name', 'email', 'phone', 'issue', 'message'];

    //
}


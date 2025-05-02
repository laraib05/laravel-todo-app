<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    // Allow mass-assignment for 'title' and 'completed'
    protected $fillable = ['title', 'completed'];
}

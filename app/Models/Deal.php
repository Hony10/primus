<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    use HasFactory;

    protected $attributes = [
        'enabled' => 1,
        'name' => '',
        'slug' => '',
        'colour' => '',
        'assignments' => '',
        'deduction' => 0,
        'deduction_ivan' => 0,
    ];

    protected $fillable = [
        'enabled',
        'name',
        'slug',
        'colour',
        'assignments',
        'deduction',
        'deduction_ivan',
    ];
}

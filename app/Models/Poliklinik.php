<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Poliklinik extends Model
{
    use HasFactory;

    // Define the table if it differs from the model name
    protected $table = 'polikliniks';

    // Define fillable attributes for mass assignment
    protected $fillable = [
        'name',
        'icon',
        'color',
    ];
}

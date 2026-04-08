<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrototypeModel extends Model
{
    protected $table = 'prototypes';

    protected $fillable = [
        'name',
        'requirements',
        'formatted_requirements',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class PrototypeModel extends Model
{
    use HasUlids;

    protected $table = 'prototypes';

    protected $fillable = [
        'name',
        'requirements',
        'formatted_requirements',
    ];
}

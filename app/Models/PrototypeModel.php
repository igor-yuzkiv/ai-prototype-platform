<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PrototypeModel extends Model
{
    use HasUlids;

    protected $table = 'prototypes';

    protected $fillable = [
        'name',
        'initial_requirements',
        'formatted_requirements',
        'project_overview',
        'global_rules',
    ];

    public function pages(): HasMany
    {
        return $this->hasMany(PrototypePageModel::class, 'prototype_id', 'id');
    }
}

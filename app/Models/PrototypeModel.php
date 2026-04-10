<?php

namespace App\Models;

use App\Enums\PrototypeStatus;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PrototypeModel extends Model
{
    use HasUlids;

    protected $table = 'prototypes';

    protected $fillable = [
        'name',
        'status',
        'is_published',
        'initial_requirements',
        'formatted_requirements',
        'project_overview',
        'global_rules',
    ];

    protected $casts = [
        'status'       => PrototypeStatus::class,
        'is_published' => 'boolean',
    ];

    public function pages(): HasMany
    {
        return $this->hasMany(PrototypePageModel::class, 'prototype_id', 'id');
    }
}

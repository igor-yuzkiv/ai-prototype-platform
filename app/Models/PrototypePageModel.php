<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrototypePageModel extends Model
{
    use HasUlids;

    protected $table = 'prototype_pages';

    protected $fillable = [
        'prototype_id',
        'file_name',
        'title',
        'description',
        'implementation',
    ];

    public function prototype(): BelongsTo
    {
        return $this->belongsTo(PrototypeModel::class, 'prototype_id', 'id');
    }
}

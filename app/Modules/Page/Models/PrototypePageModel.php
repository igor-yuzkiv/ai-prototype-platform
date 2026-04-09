<?php

namespace App\Modules\Page\Models;

use App\Modules\Prototype\Models\PrototypeModel;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrototypePageModel extends Model
{
    use HasUlids;

    protected $table = 'prototype_pages';

    protected $fillable = [
        'prototype_id',
        'parent_page_id',
        'file_name',
        'title',
        'description',
        'deep_index',
        'implementation',
        'icon',
    ];

    public function prototype(): BelongsTo
    {
        return $this->belongsTo(PrototypeModel::class, 'prototype_id', 'id');
    }

    public function parentPage(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_page_id', 'id');
    }

    public function childPages()
    {
        return $this->hasMany(self::class, 'parent_page_id', 'id');
    }
}

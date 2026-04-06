<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectArtifactModel extends Model
{
    protected $table = 'project_artifacts';

    protected $fillable = [
        'project_id',
        'name',
        'content',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(ProjectModel::class, 'project_id');
    }
}

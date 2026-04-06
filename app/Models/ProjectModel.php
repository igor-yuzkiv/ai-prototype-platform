<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectModel extends Model
{
    protected $table = 'projects';

    protected $fillable = [
        'name',
        'description',
    ];

    public function artifacts(): HasMany
    {
        return $this->hasMany(ProjectArtifactModel::class, 'project_id');
    }
}

<?php

namespace App\Domains\Project\Handlers;

use App\Models\ProjectModel;
use App\Domains\Project\Commands\UpdateProjectCommand;

class UpdateProjectHandler
{
    public function handle(ProjectModel $project, UpdateProjectCommand $command): ProjectModel
    {
        $attributes = [];

        if ($command->name !== null) {
            $attributes['name'] = $command->name;
        }

        if ($command->description !== null) {
            $attributes['description'] = $command->description;
        }

        if ($attributes !== []) {
            $project->update($attributes);
        }

        return $project->fresh();
    }
}

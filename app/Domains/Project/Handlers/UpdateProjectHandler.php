<?php

namespace App\Domains\Project\Handlers;

use App\Domains\Project\Commands\UpdateProjectCommand;
use App\Models\ProjectModel;

class UpdateProjectHandler
{
    public function __invoke(ProjectModel $project, UpdateProjectCommand $command): ProjectModel
    {
        $attributes = [];

        if ($command->name !== null) {
            $attributes['name'] = $command->name;
        }

        if ($command->requirements !== null) {
            $attributes['requirements'] = $command->requirements;
        }

        if ($attributes !== []) {
            $project->update($attributes);
        }

        return $project->fresh();
    }
}

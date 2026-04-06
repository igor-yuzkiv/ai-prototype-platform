<?php

namespace App\Domains\Project\Handlers;

use App\Models\ProjectModel;
use App\Domains\Project\Commands\UpdateProjectCommand;
use App\Domains\Project\Support\ProjectSlugGenerator;

class UpdateProjectHandler
{
    public function __construct(
        private readonly ProjectSlugGenerator $projectSlugGenerator,
    ) {}

    public function handle(ProjectModel $project, UpdateProjectCommand $command): ProjectModel
    {
        $attributes = [];

        if ($command->name !== null) {
            $attributes['name'] = $command->name;
            $attributes['slug'] = $this->projectSlugGenerator->generate($command->name, $project->id);
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

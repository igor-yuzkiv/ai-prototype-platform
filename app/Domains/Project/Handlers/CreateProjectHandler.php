<?php

namespace App\Domains\Project\Handlers;

use App\Models\ProjectModel;
use App\Domains\Project\Commands\CreateProjectCommand;
use App\Domains\Project\Support\ProjectSlugGenerator;

class CreateProjectHandler
{
    public function __construct(
        private readonly ProjectSlugGenerator $projectSlugGenerator,
    ) {}

    public function handle(CreateProjectCommand $command): ProjectModel
    {
        return ProjectModel::query()->create([
            'slug'        => $this->projectSlugGenerator->generate($command->name),
            'name'        => $command->name,
            'description' => $command->description,
        ]);
    }
}

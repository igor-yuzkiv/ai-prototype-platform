<?php

namespace App\Domains\Project\Handlers;

use App\Domains\Project\Commands\CreateProjectCommand;
use App\Domains\Project\Jobs\CreateProjectPrototypeJob;
use App\Models\ProjectModel;

class CreateProjectHandler
{
    public function __construct(
        private readonly GenerateProjectNameHandler $generateProjectNameHandler,
    ) {}

    public function handle(CreateProjectCommand $command): ProjectModel
    {
        $name = trim($command->name ?? '') ?: $this->generateProjectNameHandler->handle($command->requirements);

        $project = ProjectModel::query()->create([
            'name'         => $name,
            'requirements' => $command->requirements,
        ]);

        CreateProjectPrototypeJob::dispatch($project->id);

        return $project;
    }
}

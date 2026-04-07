<?php

namespace App\Domains\Project\Handlers;

use App\Domains\Project\Commands\CreateProjectCommand;
use App\Domains\Project\Jobs\CreateProjectPrototypeJob;
use App\Models\ProjectModel;

readonly class CreateProjectHandler
{
    public function __construct(
        private GenerateProjectNameHandler $generateProjectNameHandler,
        private NormalizeProjectRequirementsHandler $normalizeProjectRequirementsHandler,
    ) {}

    public function __invoke(CreateProjectCommand $command): ProjectModel
    {
        $name = trim($command->name ?? '') ?: ($this->generateProjectNameHandler)($command->requirements);
        $formattedRequirements = ($this->normalizeProjectRequirementsHandler)($command->requirements);

        $project = ProjectModel::query()->create([
            'name'                   => $name,
            'requirements'           => $command->requirements,
            'formatted_requirements' => $formattedRequirements,
        ]);

        CreateProjectPrototypeJob::dispatch($project->id);

        return $project;
    }
}

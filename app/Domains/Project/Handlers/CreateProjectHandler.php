<?php

namespace App\Domains\Project\Handlers;

use App\Domains\Project\Commands\CreateProjectCommand;
use App\Domains\Project\Jobs\CreateProjectPrototypeJob;
use App\Models\ProjectModel;

class CreateProjectHandler
{
    public function handle(CreateProjectCommand $command): ProjectModel
    {
        $project = ProjectModel::query()->create([
            'name'        => $command->name,
            'description' => $command->description,
        ]);

        CreateProjectPrototypeJob::dispatch($project->id);

        return $project;
    }
}

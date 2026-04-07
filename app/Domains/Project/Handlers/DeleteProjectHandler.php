<?php

namespace App\Domains\Project\Handlers;

use App\Domains\Project\Support\ProjectPrototypeLocator;
use App\Models\ProjectModel;
use Illuminate\Support\Facades\File;

class DeleteProjectHandler
{
    public function __construct(private ProjectPrototypeLocator $locator) {}

    public function __invoke(ProjectModel $project): void
    {
        $path = $this->locator->path($project);

        if ($path !== null && is_dir($path)) {
            File::deleteDirectory($path);
        }

        $project->delete();
    }
}

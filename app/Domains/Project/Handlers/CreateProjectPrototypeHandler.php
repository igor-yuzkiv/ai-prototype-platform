<?php

namespace App\Domains\Project\Handlers;

use App\Models\ProjectModel;
use App\Domains\Project\Support\ProjectPrototypeLocator;
use Illuminate\Support\Facades\File;

class CreateProjectPrototypeHandler
{
    public function __construct(
        private readonly ProjectPrototypeLocator $projectPrototypeLocator,
    ) {}

    public function handle(ProjectModel $project): void
    {
        $projectPath = $this->projectPrototypeLocator->path($project);

        if ($projectPath === null) {
            return;
        }

        File::ensureDirectoryExists($projectPath);
        File::put(
            $projectPath.DIRECTORY_SEPARATOR.'index.html',
            $this->buildInitialHtml($project),
        );
    }

    private function buildInitialHtml(ProjectModel $project): string
    {
        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$project->name}</title>
</head>
<body>
    <h1>{$project->name}</h1>
</body>
</html>
HTML;
    }
}

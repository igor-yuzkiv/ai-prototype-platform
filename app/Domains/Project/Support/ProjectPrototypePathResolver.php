<?php

namespace App\Domains\Project\Support;

use App\Models\ProjectModel;

class ProjectPrototypePathResolver
{
    public function folderName(ProjectModel $project): string
    {
        return (string) config('project.prototypes.folder_prefix', '').$project->id;
    }

    public function path(ProjectModel $project): ?string
    {
        $basePath = config('project.prototypes.base_path');

        if (!is_string($basePath) || trim($basePath) === '') {
            return null;
        }

        return rtrim($basePath, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$this->folderName($project);
    }

    public function url(ProjectModel $project): ?string
    {
        $baseUrl = config('project.prototypes.base_url');

        if (!is_string($baseUrl) || trim($baseUrl) === '') {
            return null;
        }

        return rtrim($baseUrl, '/').'/'.$this->folderName($project).'/index.html';
    }
}

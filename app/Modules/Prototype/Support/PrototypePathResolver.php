<?php

namespace App\Modules\Prototype\Support;

use App\Modules\Prototype\Models\PrototypeModel;

class PrototypePathResolver
{
    public function folderName(PrototypeModel $prototype): string
    {
        return (string) config('project.prototypes.folder_prefix', '').$prototype->id;
    }

    public function path(PrototypeModel $prototype): ?string
    {
        $basePath = config('project.prototypes.base_path');

        if (!is_string($basePath) || trim($basePath) === '') {
            return null;
        }

        return rtrim($basePath, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$this->folderName($prototype);
    }

    public function url(PrototypeModel $prototype): ?string
    {
        $baseUrl = config('project.prototypes.base_url');

        if (!is_string($baseUrl) || trim($baseUrl) === '') {
            return null;
        }

        return rtrim($baseUrl, '/').'/'.$this->folderName($prototype).'/index.html';
    }
}

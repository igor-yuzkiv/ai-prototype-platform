<?php

namespace App\Domains\Project\Support;

use App\Models\ProjectModel;
use Illuminate\Support\Str;

class ProjectSlugGenerator
{
    public function generate(string $name, ?int $ignoreProjectId = null): string
    {
        $baseSlug = Str::slug($name);
        $baseSlug = $baseSlug !== '' ? $baseSlug : 'project';
        $baseSlug = Str::limit($baseSlug, 255, '');

        $slug = $baseSlug;
        $suffix = 2;

        while ($this->slugExists($slug, $ignoreProjectId)) {
            $suffixValue = '-'.$suffix;
            $maxBaseLength = 255 - strlen($suffixValue);
            $slug = Str::limit($baseSlug, $maxBaseLength, '').$suffixValue;
            $suffix++;
        }

        return $slug;
    }

    private function slugExists(string $slug, ?int $ignoreProjectId = null): bool
    {
        return ProjectModel::query()
            ->when($ignoreProjectId !== null, fn ($query) => $query->whereKeyNot($ignoreProjectId))
            ->where('slug', $slug)
            ->exists();
    }
}

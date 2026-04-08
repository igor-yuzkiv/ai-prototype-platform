<?php

namespace App\Http\Controllers;

use App\Ai\Agents\HtmlPageGeneratorAgent;
use App\Models\ProjectModel;

class ProjectPrototypeController extends Controller
{
    public function generate(ProjectModel $project)
    {
        $model = 'gpt-5.3-codex'; // gpt-5.3-codex

        return (new HtmlPageGeneratorAgent)
            ->stream(
                prompt: $project->formatted_requirements,
                model: $model
            );
    }
}

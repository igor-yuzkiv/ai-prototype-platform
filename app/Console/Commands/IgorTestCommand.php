<?php

namespace App\Console\Commands;

use App\Ai\Agents\GeneratePrototypeAgent;
use App\Domains\Project\Commands\CreateProjectCommand;
use App\Domains\Project\Handlers\CreateProjectHandler;
use App\Models\ProjectModel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class IgorTestCommand extends Command
{
    protected $signature = 'igor:test {--action=}';

    protected $description = 'Command description';

    public function handle(): void
    {
        $action = $this->option('action');
        if ($action && method_exists($this, $action)) {
            $this->{$action}();
        }
    }

    private function test()
    {
        $project = ProjectModel::firstOrFail();

        $model = 'gpt-3.5-turbo';

        $protoHtml = (new GeneratePrototypeAgent)->prompt(
            prompt: $project->formatted_requirements,
            model: $model
        );

        Storage::disk('local')->put("prototypes/{$project->id}-{$model}.html", (string) $protoHtml);
    }

    private function createTestProject(): void
    {
        $timestamp = now()->format('Y-m-d H:i:s');
        $project = app(CreateProjectHandler::class)(new CreateProjectCommand(
            name: "Test Project {$timestamp}",
            requirements: "Created from igor:test --action=createTestProject at {$timestamp}",
        ));

        $this->info("Project {$project->id} created.");
    }
}

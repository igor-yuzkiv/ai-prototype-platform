<?php

namespace App\Console\Commands;

use App\Ai\Agents\NamingAgent;
use App\Domains\Project\Commands\CreateProjectCommand;
use App\Domains\Project\Handlers\CreateProjectHandler;
use App\Domains\Project\Support\ProjectPrototypeLocator;
use App\Models\ProjectModel;
use Illuminate\Console\Command;

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
        $response = (new NamingAgent())->prompt(
            prompt: 'Форма для зобру відвідуваності користувачів на навчальну сесію з збором підписів',
            model: 'gpt-4o-mini',
        );
        dd($response);

        $project = ProjectModel::findOrFail(4);
        $locator = app(ProjectPrototypeLocator::class);

        $url = $locator->url($project);

        dd($url);
    }

    private function createTestProject(): void
    {
        $timestamp = now()->format('Y-m-d H:i:s');
        $project = app(CreateProjectHandler::class)->handle(new CreateProjectCommand(
            name: "Test Project {$timestamp}",
            requirements: "Created from igor:test --action=createTestProject at {$timestamp}",
        ));

        $this->info("Project {$project->id} created.");
    }
}

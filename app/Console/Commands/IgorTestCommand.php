<?php

namespace App\Console\Commands;

use App\Domains\Prototype\Commands\CreatePrototypeCommand;
use App\Domains\Prototype\Handlers\CreatePrototypeHandler;
use App\Domains\Prototype\Handlers\PlanPrototypeHandler;
use App\Models\PrototypeModel;
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
        $prototype = PrototypeModel::findOrFail(4);

        $prototype->formatted_requirements = app(PlanPrototypeHandler::class)($prototype->requirements);

        //        $project->save();

        //        $model = 'gpt-3.5-turbo';
        //
        //        $protoHtml = (new GeneratePrototypeAgent)->prompt(
        //            prompt: $project->formatted_requirements,
        //            model: $model
        //        );
        //
        //        Storage::disk('local')->put("prototypes/{$project->id}-{$model}.html", (string) $protoHtml);
    }

    private function createTestProject(): void
    {
        $timestamp = now()->format('Y-m-d H:i:s');
        $prototype = app(CreatePrototypeHandler::class)(new CreatePrototypeCommand(
            name: "Test Prototype {$timestamp}",
            requirements: "Created from igor:test --action=createTestProject at {$timestamp}",
        ));

        $this->info("Prototype {$prototype->id} created.");
    }
}

<?php

namespace App\Console\Commands;

use App\Events\PrototypeStatusChangedEvent;
use App\Modules\Page\Handlers\ImplementPageHandler;
use App\Modules\Plan\Handlers\GeneratePrototypePlanHandler;
use App\Modules\Prototype\Enums\PrototypeStatus;
use App\Modules\Prototype\Models\PrototypeModel;
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

    private function test() {}

    private function implementPrototype(): void
    {
        $prototype = PrototypeModel::find('01knv9wh6daa63akv29wgndcj4');

        foreach ($prototype->pages as $page) {
            if ($page->implementation) {
                continue;
            }

            dump($page->file_name);
            app(ImplementPageHandler::class)($page);
            sleep(1);
        }

        $prototype->status = PrototypeStatus::Implemented;
        $prototype->save();
        PrototypeStatusChangedEvent::broadcast($prototype);
    }

    private function generatePrototypePlan(): void
    {
        $prototype = PrototypeModel::find('01knv9wh6daa63akv29wgndcj4');
        app(GeneratePrototypePlanHandler::class)($prototype);
    }
}

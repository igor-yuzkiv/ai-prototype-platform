<?php

namespace App\Console\Commands;

use App\Handlers\GeneratePlanHandler;
use App\Handlers\ImplementPageHandler;
use App\Handlers\ImplementPlanHandler;
use App\Models\PrototypeModel;
use App\Models\PrototypePageModel;
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
        $prototype = PrototypeModel::find('01knvqga0q8zg72hak0rab58dt');

        app(ImplementPlanHandler::class)($prototype);

    }

    private function implementPrototype(): void
    {
        $prototype = PrototypeModel::find('01knvqga0q8zg72hak0rab58dt');
        $pageId = '01knvqgvnznzejx0m5drx6kwk8';

        $page = PrototypePageModel::findOrFail($pageId);
        app(ImplementPageHandler::class)($page);

        //        foreach ($prototype->pages as $page) {
        //            if ($page->implementation) {
        //                continue;
        //            }
        //
        //            dump($page->file_name);
        //            app(ImplementPageHandler::class)($page);
        //            sleep(1);
        //        }
        //
        //        $prototype->status = PrototypeStatus::Implemented;
        //        $prototype->save();
        //        PrototypeStatusChangedEvent::broadcast($prototype);
    }

    private function generatePrototypePlan(): void
    {
        $prototype = PrototypeModel::find('01knv9wh6daa63akv29wgndcj4');
        app(GeneratePlanHandler::class)($prototype);
    }
}

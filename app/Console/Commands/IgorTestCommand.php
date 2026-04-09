<?php

namespace App\Console\Commands;

use App\Handlers\ImplementPrototypePageHandler;
use App\Handlers\GeneratePrototypePlanHandler;
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
        $prototype = PrototypeModel::find('01knryk4h4hswxtc1n6gt6wts0')->load('pages');

        //        app(PublishPrototypeHandler::class)($prototype);
        //        dd();

//                app(GeneratePrototypePlanHandler::class)($prototype);
//                dd();

        foreach ($prototype->pages as $page) {
            app(ImplementPrototypePageHandler::class)($page);
            dump($page->file_name);
            sleep(1);
        }
    }
}

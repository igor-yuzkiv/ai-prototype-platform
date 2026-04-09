<?php

namespace App\Console\Commands;

use App\Modules\Plan\Handlers\GeneratePrototypePlanHandler;
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

    private function test()
    {
        $pro = PrototypeModel::find('01knscxzs5zr5tkh3sm3p72005');
        //
        //        //        app(PublishPrototypeHandler::class)($prototype);
        //        //        dd();
        //
        app(GeneratePrototypePlanHandler::class)($pro);
        dd();
        //
        //        foreach ($prototype->pages as $page) {
        //            app(ImplementPrototypePageHandler::class)($page);
        //            dump($page->file_name);
        //            sleep(1);
        //        }
    }
}

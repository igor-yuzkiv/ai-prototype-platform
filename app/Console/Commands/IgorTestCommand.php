<?php

namespace App\Console\Commands;

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
        $prototype = PrototypeModel::find('01knph3xf63d2xwp5xcz62p2ys')->load('pages');
        app(GeneratePrototypePlanHandler::class)($prototype);
    }
}

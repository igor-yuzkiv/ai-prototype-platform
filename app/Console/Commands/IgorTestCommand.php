<?php

namespace App\Console\Commands;

use App\Handlers\GeneratePrototypePlan;
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

    private function test() {
        $prototype = PrototypeModel::find('01knph98metrnr3csgd2fne1tv');
        app(GeneratePrototypePlan::class)($prototype);
    }
}

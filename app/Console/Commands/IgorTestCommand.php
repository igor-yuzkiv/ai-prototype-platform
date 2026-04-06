<?php

namespace App\Console\Commands;

use App\Ai\Agents\TestAgent;
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
        $response = (new TestAgent)->prompt('What is the capital of France?');
        dd($response);
    }
}

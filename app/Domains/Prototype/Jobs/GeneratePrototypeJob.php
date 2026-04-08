<?php

namespace App\Domains\Prototype\Jobs;

use App\Domains\Prototype\Handlers\CreatePrototypeHtmlHandler;
use App\Models\PrototypeModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GeneratePrototypeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public readonly int $prototypeId,
    ) {}

    public function handle(CreatePrototypeHtmlHandler $handler): void
    {
        $prototype = PrototypeModel::query()->find($this->prototypeId);

        if (!$prototype) {
            return;
        }

        $handler($prototype);
    }
}

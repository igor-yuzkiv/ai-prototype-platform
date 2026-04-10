<?php

namespace App\Jobs;

use App\Handlers\GeneratePlanHandler;
use App\Models\PrototypeModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Queue\ShouldBeUniqueUntilProcessing;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class GeneratePlanJob implements ShouldBeUniqueUntilProcessing, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected PrototypeModel $prototype
    ) {}

    public function uniqueId(): string
    {
        return self::class.'::'.$this->prototype->id;
    }

    public function uniqueVia(): Repository
    {
        return Cache::driver('redis');
    }

    public function handle(GeneratePlanHandler $handler): void
    {
        $handler($this->prototype);
    }
}

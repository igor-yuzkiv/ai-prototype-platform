<?php

namespace App\Jobs;

use App\Handlers\ImplementPageHandler;
use App\Models\PrototypePageModel;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUniqueUntilProcessing;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImplementPageJob implements ShouldBeUniqueUntilProcessing, ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected readonly PrototypePageModel $page) {}

    public function handle(ImplementPageHandler $handler): void
    {
        if ($this->batch() && $this->batch()->cancelled()) {
            return;
        }

        $handler($this->page);

        logger()->debug('[Page] Implemented page with ID: '.$this->page->id, [
            'page_id'      => $this->page->id,
            'prototype_id' => $this->page->prototype_id,
        ]);
    }
}

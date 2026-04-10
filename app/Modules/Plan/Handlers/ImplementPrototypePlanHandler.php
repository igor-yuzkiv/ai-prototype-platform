<?php

namespace App\Modules\Plan\Handlers;

use App\Events\PrototypeStatusChangedEvent;
use App\Modules\Page\Jobs\ImplementPageJob;
use App\Modules\Page\Models\PrototypePageModel;
use App\Modules\Prototype\Enums\PrototypeStatus;
use App\Modules\Prototype\Models\PrototypeModel;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;

class ImplementPrototypePlanHandler
{
    public function __invoke(PrototypeModel $prototype, bool $force = false): void
    {
        throw_if(
            !$prototype->pages()->exists(),
            new \InvalidArgumentException('Prototype must have at least one page and be in Planned status to be implemented.')
        );

        $jobs = $this->makeJobs($prototype, $force);
        if (empty($jobs)) {
            logger()->warning('No pages to implement for prototype ID: '.$prototype->id, [
                'prototype_id' => $prototype->id,
                'force'        => $force,
            ]);

            return;
        }

        Bus::batch($jobs)
            ->name('Implement Prototype Plan: prototype ID '.$prototype->id)
            ->withOption('prototype_id', $prototype->id)
            ->then(function (Batch $batch) {
                $prototype = PrototypeModel::find($batch->options['prototype_id']);
                if ($prototype) {
                    $prototype->update(['status' => PrototypeStatus::Implemented]);
                    PrototypeStatusChangedEvent::broadcast($prototype);
                }
            })
            ->dispatch();

        $prototype->status = PrototypeStatus::Implementing;
        $prototype->save();
    }

    private function makeJobs(PrototypeModel $prototype, bool $force): array
    {
        if ($force) {
            return $prototype->pages->map(fn (PrototypePageModel $page) => new ImplementPageJob($page))->toArray();
        }

        return $prototype->pages()
            ->whereNull('implementation')
            ->get()
            ->map(fn (PrototypePageModel $page) => new ImplementPageJob($page))
            ->toArray();
    }
}

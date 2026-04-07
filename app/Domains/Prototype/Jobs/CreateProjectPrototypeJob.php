<?php

namespace App\Domains\Prototype\Jobs;

use App\Domains\Prototype\Handlers\CreateProjectPrototypeHandler;
use App\Models\ProjectModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateProjectPrototypeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public readonly int $projectId,
    ) {}

    public function handle(CreateProjectPrototypeHandler $handler): void
    {
        $project = ProjectModel::query()->find($this->projectId);

        if (!$project) {
            return;
        }

        $handler($project);
    }
}

<?php

namespace App\Domains\Prototype\Handlers;

use App\Domains\Prototype\Support\PrototypePathResolver;
use App\Models\PrototypeModel;
use Illuminate\Support\Facades\File;

class DeletePrototypeHandler
{
    public function __construct(private PrototypePathResolver $locator) {}

    public function __invoke(PrototypeModel $prototype): void
    {
        $path = $this->locator->path($prototype);

        if ($path !== null && is_dir($path)) {
            File::deleteDirectory($path);
        }

        $prototype->delete();
    }
}

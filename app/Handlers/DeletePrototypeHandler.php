<?php

namespace App\Handlers;

use App\Models\PrototypeModel;
use App\Support\PrototypePathResolver;
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

<?php

namespace App\Modules\Prototype\Handlers;

use App\Modules\Prototype\Models\PrototypeModel;
use App\Modules\Prototype\Support\PrototypePathResolver;
use Illuminate\Support\Facades\File;

readonly class DeletePrototypeHandler
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

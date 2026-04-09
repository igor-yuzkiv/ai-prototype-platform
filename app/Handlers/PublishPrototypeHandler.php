<?php

namespace App\Handlers;

use App\Enums\PrototypeStatus;
use App\Models\PrototypeModel;
use App\Models\PrototypePageModel;
use App\Support\PrototypePathResolver;
use Illuminate\Support\Facades\File;

readonly class PublishPrototypeHandler
{
    public function __construct(private PrototypePathResolver $locator) {}

    public function __invoke(PrototypeModel $prototype): void
    {
        $path = $this->locator->path($prototype);

        if ($path === null) {
            return;
        }

        if (is_dir($path)) {
            File::cleanDirectory($path);
        } else {
            File::makeDirectory($path, recursive: true);
        }

        $prototype->pages->each(function (PrototypePageModel $page) use ($path): void {
            File::put($path.DIRECTORY_SEPARATOR.$page->file_name, (string) $page->implementation);
        });

        $prototype->status = PrototypeStatus::Published;
        $prototype->save();
    }
}

<?php

namespace App\Domains\Prototype\Handlers;

use App\Domains\Prototype\Support\PrototypePathResolver;
use App\Models\PrototypeModel;
use Illuminate\Support\Facades\File;

class CreatePrototypeHtmlHandler
{
    public function __construct(
        private readonly PrototypePathResolver $prototypeLocator,
    ) {}

    public function __invoke(PrototypeModel $prototype): void
    {
        $prototypePath = $this->prototypeLocator->path($prototype);

        if ($prototypePath === null) {
            return;
        }

        File::ensureDirectoryExists($prototypePath);
        File::put(
            $prototypePath.DIRECTORY_SEPARATOR.'index.html',
            $this->buildInitialHtml($prototype),
        );
    }

    private function buildInitialHtml(PrototypeModel $prototype): string
    {
        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$prototype->name}</title>
</head>
<body>
    <h1>{$prototype->name}</h1>
</body>
</html>
HTML;
    }
}

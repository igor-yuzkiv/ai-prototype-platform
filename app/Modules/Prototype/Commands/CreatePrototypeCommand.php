<?php

namespace App\Modules\Prototype\Commands;

readonly class CreatePrototypeCommand
{
    public function __construct(
        public string $initialRequirements,
        public string $formattedRequirements,
        public ?string $name = null,
    ) {}
}

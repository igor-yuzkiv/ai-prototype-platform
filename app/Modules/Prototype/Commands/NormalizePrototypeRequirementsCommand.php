<?php

namespace App\Modules\Prototype\Commands;

readonly class NormalizePrototypeRequirementsCommand
{
    public function __construct(
        public string $rawRequirements,
    ) {}
}

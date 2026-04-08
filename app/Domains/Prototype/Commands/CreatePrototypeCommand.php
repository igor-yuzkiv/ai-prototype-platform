<?php

namespace App\Domains\Prototype\Commands;

class CreatePrototypeCommand
{
    public function __construct(
        public readonly string  $initialRequirements,
        public readonly ?string $name = null,
    ) {}
}

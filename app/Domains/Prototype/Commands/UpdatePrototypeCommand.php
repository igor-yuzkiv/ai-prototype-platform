<?php

namespace App\Domains\Prototype\Commands;

class UpdatePrototypeCommand
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?string $requirements = null,
    ) {}
}

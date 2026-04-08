<?php

namespace App\Domains\Prototype\Commands;

class CreatePrototypeCommand
{
    public function __construct(
        public readonly string $requirements,
        public readonly ?string $name = null,
    ) {}
}

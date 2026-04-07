<?php

namespace App\Domains\Project\Commands;

class CreateProjectCommand
{
    public function __construct(
        public readonly string $requirements,
        public readonly ?string $name = null,
    ) {}
}

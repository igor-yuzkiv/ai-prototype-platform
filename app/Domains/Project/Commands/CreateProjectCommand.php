<?php

namespace App\Domains\Project\Commands;

class CreateProjectCommand
{
    public function __construct(
        public readonly string $name,
        public readonly string $description,
    ) {}
}

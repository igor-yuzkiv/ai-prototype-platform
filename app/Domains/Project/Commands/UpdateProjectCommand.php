<?php

namespace App\Domains\Project\Commands;

class UpdateProjectCommand
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?string $description = null,
    ) {}
}

<?php

namespace App\DTO;

readonly class CreatePrototypeDto
{
    public function __construct(
        public string $initialRequirements,
        public string $formattedRequirements,
        public ?string $name = null,
    ) {}
}

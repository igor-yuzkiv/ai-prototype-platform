<?php

namespace App\DTO;

use Illuminate\Contracts\Support\Arrayable;

class PrototypePlanPageDto implements Arrayable
{
    public function __construct(
        public string $fileName,
        public string $title,
        public string $description
    ) {}

    public static function makeFromArray(array $data): self
    {
        return new self(
            fileName: $data['file_name'] ?? '',
            title: $data['title'] ?? '',
            description: $data['description'] ?? '',
        );
    }

    public function toArray(): array
    {
        return [
            'file_name'   => $this->fileName,
            'title'       => $this->title,
            'description' => $this->description,
        ];
    }
}

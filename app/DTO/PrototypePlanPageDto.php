<?php

namespace App\DTO;

use Illuminate\Contracts\Support\Arrayable;

class PrototypePlanPageDto implements Arrayable
{
    public function __construct(
        public string $aiId,
        public string $fileName,
        public string $title,
        public string $description,
        public ?string $parentAiId = null,
        public int $deepIndex = 0,
        public ?string $icon = null
    ) {}

    public static function makeFromArray(array $data): self
    {
        return new self(
            aiId: $data['ai_id'] ?? '',
            fileName: $data['file_name'] ?? '',
            title: $data['title'] ?? '',
            description: $data['description'] ?? '',
            parentAiId: $data['parent_ai_id'] ?? null,
            deepIndex: $data['deep_index'] ?? 0,
            icon: $data['icon'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'ai_id'        => $this->aiId,
            'parent_ai_id' => $this->parentAiId,
            'deep_index'   => $this->deepIndex,
            'file_name'    => $this->fileName,
            'title'        => $this->title,
            'description'  => $this->description,
            'icon'         => $this->icon,
        ];
    }
}

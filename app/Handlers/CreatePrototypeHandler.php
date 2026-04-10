<?php

namespace App\Handlers;

use App\Ai\Agents\PrototypeNameGeneratorAgent;
use App\DTO\CreatePrototypeDto;
use App\Enums\PrototypeStatus;
use App\Models\PrototypeModel;

readonly class CreatePrototypeHandler
{
    public function __invoke(CreatePrototypeDto $dto): PrototypeModel
    {
        $name = $this->generateName($dto->name, $dto->formattedRequirements);

        return PrototypeModel::query()->create([
            'name'                   => $name,
            'status'                 => PrototypeStatus::New,
            'initial_requirements'   => $dto->initialRequirements,
            'formatted_requirements' => $dto->formattedRequirements,
        ]);
    }

    private function generateName(?string $name, string $requirements): string
    {
        $name = trim((string) $name);
        if (!empty($name)) {
            return $name;
        }

        return PrototypeNameGeneratorAgent::make()->prompt(
            prompt: $requirements,
            provider: config('ai.models.fast.provider'),
            model: config('ai.models.fast.model'),
        );
    }
}

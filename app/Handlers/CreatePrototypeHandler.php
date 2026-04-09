<?php

namespace App\Handlers;

use App\Ai\Agents\PrototypeNameGeneratorAgent;
use App\Ai\Agents\RequirementsInterpreterAgent;
use App\Commands\CreatePrototypeCommand;
use App\Enums\PrototypeStatus;
use App\Models\PrototypeModel;

readonly class CreatePrototypeHandler
{
    public function __invoke(CreatePrototypeCommand $command): PrototypeModel
    {
        $normalizedRequirements = $this->normalizeRequirements($command->initialRequirements);
        $name = $this->generateName($command->name, $normalizedRequirements);

        return PrototypeModel::query()->create([
            'name'                   => $name,
            'status'                 => PrototypeStatus::New,
            'initial_requirements'   => $command->initialRequirements,
            'formatted_requirements' => $normalizedRequirements,
        ]);
    }

    private function normalizeRequirements(string $rawRequirements): string
    {
        return (new RequirementsInterpreterAgent)->prompt(
            prompt: $rawRequirements,
            provider: config('ai.models.fast.provider'),
            model: config('ai.models.fast.model'),
        );
    }

    private function generateName(?string $name, string $requirements): string
    {
        $name = trim($name);
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

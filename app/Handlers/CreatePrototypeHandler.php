<?php

namespace App\Handlers;

use App\Ai\Agents\PrototypeNameGeneratorAgent;
use App\Ai\Agents\RequirementsInterpreterAgent;
use App\Commands\CreatePrototypeCommand;
use App\Models\PrototypeModel;

readonly class CreatePrototypeHandler
{
    public function __invoke(CreatePrototypeCommand $command): PrototypeModel
    {
        $normalizedRequirements = $this->normalizeRequirements($command->initialRequirements);
        $name = $this->generateName($command->name, $normalizedRequirements);

        return PrototypeModel::query()->create([
            'name'                   => $name,
            'initial_requirements'   => $command->initialRequirements,
            'formatted_requirements' => $normalizedRequirements,
        ]);
    }

    private function normalizeRequirements(string $rawRequirements): string
    {
        return (new RequirementsInterpreterAgent)->prompt(
            prompt: $rawRequirements,
            model: 'gpt-4o-mini',
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
            model: 'gpt-4o-mini',
        );
    }
}

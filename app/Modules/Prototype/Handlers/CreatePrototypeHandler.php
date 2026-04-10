<?php

namespace App\Modules\Prototype\Handlers;

use App\Ai\Agents\PrototypeNameGeneratorAgent;
use App\Modules\Prototype\Commands\CreatePrototypeCommand;
use App\Modules\Prototype\Enums\PrototypeStatus;
use App\Modules\Prototype\Models\PrototypeModel;

readonly class CreatePrototypeHandler
{
    public function __invoke(CreatePrototypeCommand $command): PrototypeModel
    {
        $name = $this->generateName($command->name, $command->formattedRequirements);

        return PrototypeModel::query()->create([
            'name'                   => $name,
            'status'                 => PrototypeStatus::New,
            'initial_requirements'   => $command->initialRequirements,
            'formatted_requirements' => $command->formattedRequirements,
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

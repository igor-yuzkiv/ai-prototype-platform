<?php

namespace App\Domains\Project\Handlers;

use App\Ai\Agents\ProjectNameGeneratorAgent;
use App\Ai\Agents\RequirementsInterpreterAgent;
use App\Domains\Project\Commands\CreateProjectCommand;
use App\Models\ProjectModel;

readonly class CreateProjectHandler
{
    public function __invoke(CreateProjectCommand $command): ProjectModel
    {
        $normalizedRequirements = $this->normalizeRequirements($command->requirements);
        $name = $this->generateName($command->name, $normalizedRequirements);

        return ProjectModel::query()->create([
            'name'                   => $name,
            'requirements'           => $command->requirements,
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

        return ProjectNameGeneratorAgent::make()->prompt(
            prompt: $requirements,
            model: 'gpt-4o-mini',
        );
    }
}

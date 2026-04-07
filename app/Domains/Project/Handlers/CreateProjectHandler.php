<?php

namespace App\Domains\Project\Handlers;

use App\Domains\Project\Commands\CreateProjectCommand;
use App\Models\ProjectModel;

readonly class CreateProjectHandler
{
    public function __construct(
        private GenerateProjectNameHandler $generateProjectNameHandler,
        private NormalizeProjectRequirementsHandler $normalizeProjectRequirementsHandler,
    ) {}

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
        $requirements = trim($rawRequirements);
        if (empty($requirements)) {
            throw new \InvalidArgumentException('Project requirements cannot be empty.');
        }

        return ($this->normalizeProjectRequirementsHandler)($requirements);
    }

    private function generateName(?string $name, string $requirements): string
    {
        $name = trim($name);
        if (empty($name)) {
            return ($this->generateProjectNameHandler)($requirements);
        }

        return $name;
    }
}

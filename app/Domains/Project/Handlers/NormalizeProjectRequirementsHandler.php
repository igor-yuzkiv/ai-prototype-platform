<?php

namespace App\Domains\Project\Handlers;

use App\Ai\Agents\RequirementsNormalizerAgent;

class NormalizeProjectRequirementsHandler
{
    public function __invoke(string $initialRequirements): string
    {
        $response = (new RequirementsNormalizerAgent)->prompt(
            prompt: $initialRequirements,
            model: 'gpt-5.4-mini',
        );

        return (string) $response;
    }
}

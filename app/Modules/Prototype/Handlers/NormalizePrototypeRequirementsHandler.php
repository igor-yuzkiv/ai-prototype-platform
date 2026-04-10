<?php

namespace App\Modules\Prototype\Handlers;

use App\Ai\Agents\RequirementsInterpreterAgent;
use App\Modules\Prototype\Commands\NormalizePrototypeRequirementsCommand;
use Laravel\Ai\Responses\StreamableAgentResponse;

readonly class NormalizePrototypeRequirementsHandler
{
    public function __invoke(NormalizePrototypeRequirementsCommand $command): StreamableAgentResponse
    {
        return (new RequirementsInterpreterAgent)->stream(
            prompt: $command->rawRequirements,
            provider: config('ai.models.fast.provider'),
            model: config('ai.models.fast.model'),
        );
    }
}

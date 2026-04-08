<?php

namespace App\Domains\Prototype\Handlers;

use App\Ai\Agents\PrototypePlannerAgent;
use Illuminate\Http\Client\RequestException;

class PlanPrototypeHandler
{
    public function __invoke(string $initialRequirements): string
    {
        try {
            $response = (new PrototypePlannerAgent)->prompt(
                prompt: $initialRequirements,
                model: 'gpt-5.4-mini',
            );
        } catch (RequestException $e) {
            dd($e->response->body());
        }

        dd($response);

        return (string) $response;
    }
}

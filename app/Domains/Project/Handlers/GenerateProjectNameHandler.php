<?php

namespace App\Domains\Project\Handlers;

use App\Ai\Agents\ProjectNameGeneratorAgent;

class GenerateProjectNameHandler
{
    public function __invoke(string $requirements): string
    {
        $response = ProjectNameGeneratorAgent::make()->prompt(
            prompt: $requirements,
            model: 'gpt-4o-mini',
        );

        return $this->normalizeName((string) $response);
    }

    private function normalizeName(string $name): string
    {
        $name = preg_replace('/\s+/', ' ', trim($name)) ?? '';
        $name = trim($name, " \t\n\r\0\x0B\"'");

        if ($name === '') {
            return 'Prototype '.now()->format('Y-m-d H:i:s');
        }

        return mb_substr($name, 0, 255);
    }
}

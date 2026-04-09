<?php

namespace App\Modules\Plan\DTO;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Laravel\Ai\Responses\AgentResponse;

class PrototypePlanDto implements Arrayable
{
    /**
     * @param  Collection<int, PrototypePlanPageDto>  $pages
     */
    public function __construct(
        public string $projectOverview,
        public string $globalRules,
        public Collection $pages
    ) {}

    public static function makeFromAgentResponse(AgentResponse $response): self
    {
        $pages = collect($response->structured['pages'] ?? [])->map(fn ($page) => PrototypePlanPageDto::makeFromArray($page));

        return new self(
            projectOverview: $response->structured['project_overview'] ?? '',
            globalRules: $response->structured['global_rules'] ?? '',
            pages: $pages,
        );
    }

    public function toArray(): array
    {
        return [
            'project_overview' => $this->projectOverview,
            'global_rules'     => $this->globalRules,
            'pages'            => $this->pages->toArray(),
        ];
    }
}

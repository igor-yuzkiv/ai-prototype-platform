<?php

namespace App\Handlers;

use App\Ai\Agents\PrototypePlannerAgent;
use App\DTO\PrototypePlanDto;
use App\DTO\PrototypePlanPageDto;
use App\Models\PrototypeModel;
use Illuminate\Support\Collection;

class GeneratePrototypePlan
{
    public function __invoke(PrototypeModel $prototype): PrototypeModel
    {
        if (empty($prototype->formatted_requirements)) {
            throw new \InvalidArgumentException('The prototype formatted_requirements is required.');
        }

        $planDto = $this->generatePlan($prototype);

        $prototype->project_overview = $planDto->projectOverview;
        $prototype->global_rules = $planDto->globalRules;
        $prototype->save();

        $this->savePages($prototype, $planDto->pages);

        $prototype->refresh();

        return $prototype;
    }

    private function generatePlan(PrototypeModel $prototype): PrototypePlanDto
    {
        $response = (new PrototypePlannerAgent)->prompt(
            prompt: $prototype->formatted_requirements,
            model: config('ai.models.smart'),
        );

        return PrototypePlanDto::makeFromAgentResponse($response);
    }

    /**
     * @param  Collection<int, PrototypePlanPageDto>  $pages
     */
    private function savePages(PrototypeModel $prototype, Collection $pages): void
    {
        foreach ($pages as $page) {
            $prototype->pages()->updateOrCreate(
                ['file_name' => $page->fileName],
                [
                    'file_name'   => $page->fileName,
                    'title'       => $page->title,
                    'description' => $page->description,
                ]
            );
        }

    }
}

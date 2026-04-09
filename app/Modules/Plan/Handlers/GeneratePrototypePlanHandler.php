<?php

namespace App\Modules\Plan\Handlers;

use App\Ai\Agents\PrototypePlannerAgent;
use App\Events\PrototypeStatusChangedEvent;
use App\Modules\Plan\DTO\PrototypePlanDto;
use App\Modules\Plan\DTO\PrototypePlanPageDto;
use App\Modules\Prototype\Enums\PrototypeStatus;
use App\Modules\Prototype\Models\PrototypeModel;
use Illuminate\Support\Collection;

class GeneratePrototypePlanHandler
{
    public function __invoke(PrototypeModel $prototype): PrototypeModel
    {
        if (empty($prototype->formatted_requirements)) {
            throw new \InvalidArgumentException('The prototype formatted_requirements is required.');
        }

        $planDto = $this->generatePlan($prototype);

        $prototype->project_overview = $planDto->projectOverview;
        $prototype->global_rules = $planDto->globalRules;
        $prototype->status = PrototypeStatus::Planned;
        $prototype->save();

        $this->savePages($prototype, $planDto->pages);

        $prototype->refresh();

        PrototypeStatusChangedEvent::broadcast($prototype);

        return $prototype;
    }

    private function generatePlan(PrototypeModel $prototype): PrototypePlanDto
    {
        $response = (new PrototypePlannerAgent)->prompt(
            prompt: $prototype->formatted_requirements,
            provider: config('ai.models.plan.provider'),
            model: config('ai.models.plan.model'),
        );

        return PrototypePlanDto::makeFromAgentResponse($response);
    }

    /**
     * @param  Collection<int, PrototypePlanPageDto>  $pages
     */
    private function savePages(PrototypeModel $prototype, Collection $pages): void
    {
        $prototype->pages()->delete();

        $modelsByAiId = [];

        foreach ($pages as $page) {
            $model = $prototype->pages()->updateOrCreate(
                ['file_name' => $page->fileName],
                [
                    'title'       => $page->title,
                    'description' => $page->description,
                    'deep_index'  => $page->deepIndex,
                ]
            );
            $modelsByAiId[$page->aiId] = $model;
        }

        foreach ($pages as $page) {
            if ($page->parentAiId === null) {
                continue;
            }
            $parent = $modelsByAiId[$page->parentAiId] ?? null;
            if ($parent === null) {
                continue;
            }
            $modelsByAiId[$page->aiId]->update(['parent_page_id' => $parent->id]);
        }
    }
}

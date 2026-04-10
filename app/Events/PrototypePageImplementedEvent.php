<?php

namespace App\Events;

use App\Broadcasting\BaseClientEvent;
use App\Modules\Page\Models\PrototypePageModel;

class PrototypePageImplementedEvent extends BaseClientEvent
{
    public function __construct(
        protected readonly PrototypePageModel $page
    ) {}

    public function getEventName(): string
    {
        return sprintf('project.%s.page.%s.implemented', $this->page->prototype->id, $this->page->id);
    }

    protected function getMessage(): string
    {
        return sprintf(
            'Prototype %s page %s implemented',
            $this->page->prototype->name,
            $this->page->title
        );
    }

    protected function getEventData(): array
    {
        return [
            'id'      => $this->page->prototype_id,
            'page_id' => $this->page->id,
        ];
    }
}

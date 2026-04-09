<?php

namespace App\Events;

use App\Broadcasting\BaseClientEvent;
use App\Modules\Prototype\Models\PrototypeModel;

class PrototypeStatusChangedEvent extends BaseClientEvent
{
    public function __construct(
        private readonly PrototypeModel $prototype
    ) {}

    public function getEventName(): string
    {
        return 'project.status.changed';
    }

    protected function getMessage(): string
    {
        return sprintf(
            'Prototype %s status changed to %s',
            $this->prototype->name,
            $this->prototype->status?->value ?? 'empty'
        );
    }

    protected function getEventData(): array
    {
        return [
            'id'     => $this->prototype->id,
            'status' => $this->prototype?->status->value,
        ];
    }
}

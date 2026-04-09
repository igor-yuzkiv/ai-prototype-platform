<?php

namespace App\Events;

use App\Broadcasting\BaseClientEvent;
use App\Modules\Prototype\Enums\PrototypeStatus;
use App\Modules\Prototype\Models\PrototypeModel;

class PrototypeStatusChangedEvent extends BaseClientEvent
{
    public function __construct(
        private readonly PrototypeModel $prototype,
        private readonly ?PrototypeStatus $origin = null,
    ) {}

    public function getEventName(): string
    {
        return 'project.status.changed';
    }

    protected function getMessage(): string
    {
        return sprintf(
            'Prototype %s status changed from %s to %s',
            $this->prototype->name,
            $this->origin?->value ?? 'empty',
            $this->prototype?->status->value ?? 'empty',
        );
    }

    protected function getEventData(): array
    {
        return [
            'id'      => $this->prototype->id,
            'origin'  => $this->origin?->value,
            'current' => $this->prototype?->status->value,
        ];
    }
}

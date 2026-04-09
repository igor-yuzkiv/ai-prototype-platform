<?php

namespace App\Broadcasting;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

abstract class BaseClientEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function broadcastOn(): Channel
    {
        return new Channel(config('project.client_broadcast.channel_name'));
    }

    public function broadcastAs(): string
    {
        return $this->getEventName();
    }

    public function broadcastWith(): array
    {
        return [
            'event'   => $this->getEventName(),
            'message' => $this->getMessage(),
            'data'    => $this->getEventData(),
        ];

    }

    abstract protected function getEventName(): string;

    abstract protected function getMessage(): string;

    abstract protected function getEventData(): array;
}

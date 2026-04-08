<?php

namespace App\Domains\Prototype\Handlers;

use App\Domains\Prototype\Commands\UpdatePrototypeCommand;
use App\Models\PrototypeModel;

class UpdatePrototypeHandler
{
    public function __invoke(PrototypeModel $prototype, UpdatePrototypeCommand $command): PrototypeModel
    {
        $attributes = [];

        if ($command->name !== null) {
            $attributes['name'] = $command->name;
        }

        if ($command->requirements !== null) {
            $attributes['requirements'] = $command->requirements;
        }

        if ($attributes !== []) {
            $prototype->update($attributes);
        }

        return $prototype->fresh();
    }
}

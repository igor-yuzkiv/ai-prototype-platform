<?php

namespace App\Http\Controllers;

use App\Ai\Agents\PrototypePageImplementationAgent;
use App\Modules\Prototype\Models\PrototypeModel;

class PrototypeGeneratorController extends Controller
{
    public function generate(PrototypeModel $prototype)
    {
        $model = 'gpt-5.3-codex'; // gpt-5.3-codex

        return (new PrototypePageImplementationAgent)
            ->stream(
                prompt: $prototype->formatted_requirements,
                model: $model
            );
    }
}

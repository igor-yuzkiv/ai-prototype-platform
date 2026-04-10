<?php

namespace App\Http\Controllers;

use App\Ai\Agents\RequirementsInterpreterAgent;
use App\Http\Resources\PrototypeResource;
use App\Http\Resources\PrototypeSummaryResource;
use App\Modules\Plan\Handlers\ImplementPrototypePlanHandler;
use App\Modules\Plan\Jobs\GeneratePrototypePlanJob;
use App\Modules\Prototype\Commands\CreatePrototypeCommand;
use App\Modules\Prototype\Enums\PrototypeStatus;
use App\Modules\Prototype\Handlers\CreatePrototypeHandler;
use App\Modules\Prototype\Handlers\DeletePrototypeHandler;
use App\Modules\Prototype\Handlers\PublishPrototypeHandler;
use App\Modules\Prototype\Models\PrototypeModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Ai\Responses\StreamableAgentResponse;

class PrototypeController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $prototypes = PrototypeModel::query()
            ->latest()
            ->paginate($request->integer('per_page', 15))
            ->withQueryString();

        return PrototypeSummaryResource::collection($prototypes);
    }

    public function create(Request $request, CreatePrototypeHandler $handler)
    {
        $validated = $request->validate([
            'name'                   => ['sometimes', 'nullable', 'string', 'max:255'],
            'initial_requirements'   => ['required', 'string'],
            'formatted_requirements' => ['required', 'string'],
        ]);

        $prototype = $handler(new CreatePrototypeCommand(
            initialRequirements: $validated['initial_requirements'],
            formattedRequirements: $validated['formatted_requirements'],
            name: $validated['name'] ?? null,
        ));

        GeneratePrototypePlanJob::dispatch($prototype);

        return (new PrototypeResource($prototype))
            ->response()
            ->setStatusCode(201);
    }

    public function normalizeRequirements(Request $request): StreamableAgentResponse
    {
        $validated = $request->validate([
            'initial_requirements' => ['required', 'string'],
        ]);

        return (new RequirementsInterpreterAgent)->stream(
            prompt: $validated['initial_requirements'],
            provider: config('ai.models.fast.provider'),
            model: config('ai.models.fast.model'),
        );
    }

    public function show(PrototypeModel $prototype): PrototypeResource
    {
        $prototype->load('pages');

        return new PrototypeResource($prototype);
    }

    public function destroy(PrototypeModel $prototype, DeletePrototypeHandler $handler): JsonResponse
    {
        $handler($prototype);

        return response()->json(status: 204);
    }

    public function publish(PrototypeModel $prototype, PublishPrototypeHandler $handler): PrototypeResource
    {
        $prototype->load('pages');
        $handler($prototype);
        $prototype->refresh();

        return new PrototypeResource($prototype);
    }

    public function implementPlan(PrototypeModel $prototype, ImplementPrototypePlanHandler $handler): JsonResponse
    {
        if ($prototype->status === PrototypeStatus::Implementing || $prototype->status === PrototypeStatus::Implemented) {
            return response()->json([
                'message' => 'Prototype is already being implemented or has been implemented.',
            ], 400);
        }

        if (!$prototype->pages()->exists()) {
            return response()->json([
                'message' => 'Prototype must have at least one page to be implemented.',
            ], 400);
        }

        $handler($prototype);

        return response()->json([
            'message' => 'Prototype implementation started',
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\PrototypeResource;
use App\Http\Resources\PrototypeSummaryResource;
use App\Modules\Plan\Handlers\GeneratePrototypePlanHandler;
use App\Modules\Prototype\Commands\CreatePrototypeCommand;
use App\Modules\Prototype\Handlers\CreatePrototypeHandler;
use App\Modules\Prototype\Handlers\DeletePrototypeHandler;
use App\Modules\Prototype\Handlers\PublishPrototypeHandler;
use App\Modules\Prototype\Models\PrototypeModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

    public function store(Request $request, CreatePrototypeHandler $handler)
    {
        $validated = $request->validate([
            'name'                 => ['sometimes', 'nullable', 'string', 'max:255'],
            'initial_requirements' => ['required', 'string'],
        ]);

        $prototype = $handler(new CreatePrototypeCommand(
            initialRequirements: $validated['initial_requirements'],
            name: $validated['name'] ?? null,
        ));

        return (new PrototypeResource($prototype))
            ->response()
            ->setStatusCode(201);
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

    public function generatePlan(PrototypeModel $prototype, GeneratePrototypePlanHandler $handler): PrototypeResource
    {
        $prototype = $handler($prototype);

        return new PrototypeResource($prototype);
    }

    public function publish(PrototypeModel $prototype, PublishPrototypeHandler $handler): PrototypeResource
    {
        $prototype->load('pages');
        $handler($prototype);
        $prototype->refresh();

        return new PrototypeResource($prototype);
    }
}

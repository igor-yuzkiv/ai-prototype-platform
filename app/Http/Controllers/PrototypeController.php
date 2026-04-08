<?php

namespace App\Http\Controllers;

use App\Commands\CreatePrototypeCommand;
use App\Handlers\CreatePrototypeHandler;
use App\Handlers\DeletePrototypeHandler;
use App\Http\Resources\PrototypeResource;
use App\Http\Resources\PrototypeSummaryResource;
use App\Models\PrototypeModel;
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
        return new PrototypeResource($prototype);
    }

    public function destroy(PrototypeModel $prototype, DeletePrototypeHandler $handler): JsonResponse
    {
        $handler($prototype);

        return response()->json(status: 204);
    }
}

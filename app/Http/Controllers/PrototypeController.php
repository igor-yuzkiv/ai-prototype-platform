<?php

namespace App\Http\Controllers;

use App\Domains\Prototype\Commands\CreatePrototypeCommand;
use App\Domains\Prototype\Commands\UpdatePrototypeCommand;
use App\Domains\Prototype\Handlers\CreatePrototypeHandler;
use App\Domains\Prototype\Handlers\DeletePrototypeHandler;
use App\Domains\Prototype\Handlers\UpdatePrototypeHandler;
use App\Http\Resources\PrototypeResource;
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

        return PrototypeResource::collection($prototypes);
    }

    public function store(Request $request, CreatePrototypeHandler $handler)
    {
        $validated = $request->validate([
            'name'         => ['sometimes', 'nullable', 'string', 'max:255'],
            'requirements' => ['required', 'string'],
        ]);

        $prototype = $handler(new CreatePrototypeCommand(
            requirements: $validated['requirements'],
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

    public function update(
        Request $request,
        PrototypeModel $prototype,
        UpdatePrototypeHandler $handler,
    ): PrototypeResource {
        $validated = $request->validate([
            'name'         => ['sometimes', 'string', 'max:255'],
            'requirements' => ['sometimes', 'string'],
        ]);

        $prototype = $handler($prototype, new UpdatePrototypeCommand(
            name: $validated['name'] ?? null,
            requirements: $validated['requirements'] ?? null,
        ));

        return new PrototypeResource($prototype);
    }

    public function destroy(PrototypeModel $prototype, DeletePrototypeHandler $handler): JsonResponse
    {
        $handler($prototype);

        return response()->json(status: 204);
    }
}

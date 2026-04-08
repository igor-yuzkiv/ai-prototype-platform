<?php

namespace App\Http\Controllers;

use App\Domains\Project\Commands\CreateProjectCommand;
use App\Domains\Project\Commands\UpdateProjectCommand;
use App\Domains\Project\Handlers\CreateProjectHandler;
use App\Domains\Project\Handlers\DeleteProjectHandler;
use App\Domains\Project\Handlers\UpdateProjectHandler;
use App\Http\Resources\ProjectResource;
use App\Models\ProjectModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $projects = ProjectModel::query()
            ->latest()
            ->paginate($request->integer('per_page', 15))
            ->withQueryString();

        return ProjectResource::collection($projects);
    }

    public function store(Request $request, CreateProjectHandler $handler)
    {
        $validated = $request->validate([
            'name'         => ['sometimes', 'nullable', 'string', 'max:255'],
            'requirements' => ['required', 'string'],
        ]);

        $project = $handler(new CreateProjectCommand(
            requirements: $validated['requirements'],
            name: $validated['name'] ?? null,
        ));

        return (new ProjectResource($project))
            ->response()
            ->setStatusCode(201);
    }

    public function show(ProjectModel $project): ProjectResource
    {
        return new ProjectResource($project);
    }

    public function update(
        Request $request,
        ProjectModel $project,
        UpdateProjectHandler $handler,
    ): ProjectResource {
        $validated = $request->validate([
            'name'         => ['sometimes', 'string', 'max:255'],
            'requirements' => ['sometimes', 'string'],
        ]);

        $project = $handler($project, new UpdateProjectCommand(
            name: $validated['name'] ?? null,
            requirements: $validated['requirements'] ?? null,
        ));

        return new ProjectResource($project);
    }

    public function destroy(ProjectModel $project, DeleteProjectHandler $handler): JsonResponse
    {
        $handler($project);

        return response()->json(status: 204);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectArtifactResource;
use App\Models\ProjectArtifactModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectArtifactController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $artifacts = ProjectArtifactModel::query()
            ->latest()
            ->paginate($request->integer('per_page', 15))
            ->withQueryString();

        return ProjectArtifactResource::collection($artifacts);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'name'       => ['required', 'string', 'max:255'],
            'content'    => ['required', 'string'],
        ]);

        $artifact = ProjectArtifactModel::query()->create($validated);

        return (new ProjectArtifactResource($artifact))
            ->response()
            ->setStatusCode(201);
    }

    public function show(ProjectArtifactModel $projectArtifact): ProjectArtifactResource
    {
        return new ProjectArtifactResource($projectArtifact);
    }

    public function update(Request $request, ProjectArtifactModel $projectArtifact): ProjectArtifactResource
    {
        $validated = $request->validate([
            'project_id' => ['sometimes', 'integer', 'exists:projects,id'],
            'name'       => ['sometimes', 'string', 'max:255'],
            'content'    => ['sometimes', 'string'],
        ]);

        $projectArtifact->update($validated);

        return new ProjectArtifactResource($projectArtifact->fresh());
    }

    public function destroy(ProjectArtifactModel $projectArtifact): JsonResponse
    {
        $projectArtifact->delete();

        return response()->json(status: 204);
    }
}

<?php

namespace App\Http\Resources;

use App\Domains\Project\Support\ProjectPrototypeLocator;
use App\Models\ProjectModel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin ProjectModel */ class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $prototypeLocator = app(ProjectPrototypeLocator::class);

        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'description'   => $this->description,
            'prototype_url' => $prototypeLocator->url($this->resource),
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
            'artifacts'     => ProjectArtifactResource::collection($this->whenLoaded('artifacts')),
        ];
    }
}

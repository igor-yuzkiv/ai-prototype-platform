<?php

namespace App\Http\Resources;

use App\Modules\Prototype\Models\PrototypeModel;
use App\Modules\Prototype\Support\PrototypePathResolver;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin PrototypeModel */ class PrototypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $prototypeLocator = app(PrototypePathResolver::class);

        return [
            'id'                     => $this->id,
            'name'                   => $this->name,
            'status'                 => $this->status?->value,
            'is_published'           => $this->is_published,
            'prototype_url'          => $this->is_published ? $prototypeLocator->url($this->resource) : null,
            'initial_requirements'   => $this->initial_requirements,
            'formatted_requirements' => $this->formatted_requirements,
            'project_overview'       => $this->project_overview,
            'global_rules'           => $this->global_rules,
            'pages'                  => PrototypePageResource::collection($this->whenLoaded('pages')),
            'created_at'             => $this->created_at,
            'updated_at'             => $this->updated_at,
        ];
    }
}

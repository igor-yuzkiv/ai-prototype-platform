<?php

namespace App\Http\Resources;

use App\Models\PrototypeModel;
use App\Support\PrototypePathResolver;
use App\Http\Resources\PrototypePageResource;
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
            'initial_requirements'   => $this->initial_requirements,
            'formatted_requirements' => $this->formatted_requirements,
            'project_overview'       => $this->project_overview,
            'global_rules'           => $this->global_rules,
            'prototype_url'          => $prototypeLocator->url($this->resource),
            'pages'                  => PrototypePageResource::collection($this->whenLoaded('pages')),
            'created_at'             => $this->created_at,
            'updated_at'             => $this->updated_at,
        ];
    }
}

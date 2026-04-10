<?php

namespace App\Http\Resources;

use App\Models\PrototypeModel;
use App\Support\PrototypePathResolver;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin PrototypeModel */
class PrototypeSummaryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $prototypeLocator = app(PrototypePathResolver::class);

        return [
            'id'               => $this->id,
            'name'             => $this->name,
            'status'           => $this->status?->value,
            'is_published'     => $this->is_published,
            'prototype_url'    => $this->is_published ? $prototypeLocator->url($this->resource) : null,
            'project_overview' => $this->project_overview,
            'created_at'       => $this->created_at,
            'updated_at'       => $this->updated_at,
        ];
    }
}

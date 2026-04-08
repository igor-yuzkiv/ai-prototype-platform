<?php

namespace App\Http\Resources;

use App\Domains\Prototype\Support\PrototypePathResolver;
use App\Models\PrototypeModel;
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
            'prototype_url'          => $prototypeLocator->url($this->resource),
            'created_at'             => $this->created_at,
            'updated_at'             => $this->updated_at,
        ];
    }
}

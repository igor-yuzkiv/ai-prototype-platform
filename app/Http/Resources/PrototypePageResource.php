<?php

namespace App\Http\Resources;

use App\Models\PrototypePageModel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin PrototypePageModel */
class PrototypePageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'file_name'      => $this->file_name,
            'title'          => $this->title,
            'description'    => $this->description,
            'implementation' => $this->implementation,
            'created_at'     => $this->created_at,
            'updated_at'     => $this->updated_at,
        ];
    }
}

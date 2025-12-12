<?php

namespace App\Http\Resources\Content;

use App\Http\Resources\Admin\UserResource;
use Illuminate\Http\Request;

class ParticipantResource extends UserResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            ...parent::toArray($request),
            'role' => $this->resource->pivot?->role,
        ];
    }
}

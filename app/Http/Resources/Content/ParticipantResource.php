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
        $data = parent::toArray($request);

        if ($this->resource->pivot) {
            $data['role'] = $this->resource->pivot->role;
        }

        return $data;
    }
}

<?php

namespace App\Http\Resources\Passport;

use App\Http\Resources\Admin\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'provider' => $this->provider,
            'redirect' => $this->redirect_uris,
            'grant_types' => $this->grant_types,
            'revoked' => $this->revoked,
            'owner' => $this->owner_id ? new UserResource($this->whenLoaded('owner')) : null,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}

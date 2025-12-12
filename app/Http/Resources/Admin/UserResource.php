<?php

namespace App\Http\Resources\Admin;

use App\Models\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class UserResource extends JsonResource
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
            'email' => $this->email,
            'created_at' => $this->whenNotNull($this->created_at->toDateTimeString()),
            'email_verified_at' => $this->whenNotNull($this->email_verified_at?->toDateTimeString()),
        ];
    }
}

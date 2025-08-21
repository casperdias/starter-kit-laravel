<?php

namespace App\Http\Resources;

use App\Http\Resources\Admin\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
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
            'message' => $this->message,
            'user' => $this->whenLoaded('user', fn () => new UserResource($this->user)),
            'taggedUser' => $this->whenLoaded('taggedUser', fn () => new UserResource($this->taggedUser)),
            'created_at' => $this->created_at->format('d/m/Y H:i:s').' GMT'.str_replace([':00', '+0', '-0'], ['', '+', '-'], $this->created_at->format('P')),
        ];
    }
}

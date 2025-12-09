<?php

namespace App\Http\Resources\Content;

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
        $hideContent = $request->boolean('hide_content', false);

        return [
            'uuid' => $this->uuid,
            'type' => $this->type,
            'message' => $this->message,
            $this->mergeWhen(! $hideContent, [
                'metadata' => $this->metadata,
            ]),
            'user' => new UserResource($this->whenLoaded('user')),
            'created_at' => $this->created_at->format('d F Y H:i:s T'),
        ];
    }
}

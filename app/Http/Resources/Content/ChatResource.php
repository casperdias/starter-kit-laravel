<?php

namespace App\Http\Resources\Content;

use App\Http\Resources\Admin\UserResource;
use App\Models\Content\Chat;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Chat
 */
class ChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<int|string, mixed>
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
            'updated_at' => $this->updated_at->format('d F Y H:i:s T'),
        ];
    }
}

<?php

namespace App\Http\Resources\Content;

use App\Models\Content\Conversation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin Conversation
 */
class ConversationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $avatarUrl = $this->avatar
            ? Storage::temporaryUrl($this->avatar, now()->addMinutes(5))
            : null;

        return [
            'id' => $this->id,
            'type' => $this->type,
            'name' => $this->name,
            'description' => $this->description,
            'avatar' => $avatarUrl,
            'created_at' => $this->created_at->format('d F Y H:i:s T'),
            'participants' => ParticipantResource::collection($this->whenLoaded('participants')),
            'last_message' => new ChatResource($this->whenLoaded('lastMessage')),
            'updated_at' => $this->updated_at->format('d F Y H:i:s T'),
        ];
    }
}

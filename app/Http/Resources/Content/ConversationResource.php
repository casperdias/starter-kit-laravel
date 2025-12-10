<?php

namespace App\Http\Resources\Content;

use App\Http\Resources\Admin\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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
            ? Storage::temporaryUrl($this->avatar, now()->plus(minutes: 5))
            : null;

        return [
            'id' => $this->id,
            'type' => $this->type,
            'name' => $this->name,
            'description' => $this->description,
            'avatar' => $avatarUrl,
            'created_at' => $this->created_at->format('d F Y H:i:s T'),
            'participants' => $this->whenLoaded('participants', function () use ($request) {
                return $this->participants->map(function ($participant) use ($request) {
                    return array_merge(
                        (new UserResource($participant))->toArray($request),
                        ['role' => $participant->pivot->role]
                    );
                });
            }),
            'last_message' => new ChatResource($this->whenLoaded('lastMessage')),
            'updated_at' => $this->updated_at->format('d F Y H:i:s T'),
        ];
    }
}

<?php

namespace App\Http\Resources\Content;

use App\Http\Resources\Admin\UserResource;
use App\Models\Auth\User;
use App\Models\Content\Chat;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property string $type
 * @property string|null $name
 * @property string|null $description
 * @property string|null $avatar
 * @property Collection<int, User> $participants
 * @property Chat|null $lastMessage
 * @property Carbon $created_at
 * @property Carbon $updated_at
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
            'participants' => $this->whenLoaded('participants', function () use ($request) {
                $participantsArray = [];
                foreach ($this->participants as $participant) {
                    $pivot = $participant->getRelationValue('pivot');
                    $participantsArray[] = array_merge(
                        (new UserResource($participant))->toArray($request),
                        ['role' => $pivot->role]
                    );
                }

                return $participantsArray;
            }),
            'last_message' => new ChatResource($this->whenLoaded('lastMessage')),
            'updated_at' => $this->updated_at->format('d F Y H:i:s T'),
        ];
    }
}

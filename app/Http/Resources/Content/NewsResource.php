<?php

namespace App\Http\Resources\Content;

use App\Actions\RelativeTime;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
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
            'title' => $this->title,
            'type' => $this->type,
            'content' => $this->content,
            'author' => $this->whenLoaded('author', fn () => $this->author->name),
            'created_at' => $this->created_at->format('d F Y H:i:s T'),
            'diff_created_at' => RelativeTime::forDate($this->created_at),
            'updated_at' => $this->updated_at->format('d F Y H:i:s T'),
        ];
    }
}

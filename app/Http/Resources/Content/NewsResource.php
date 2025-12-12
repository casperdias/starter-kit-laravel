<?php

namespace App\Http\Resources\Content;

use App\Models\Content\News;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin News
 */
class NewsResource extends JsonResource
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
            'id' => $this->id,
            'title' => $this->title,
            'type' => $this->type,
            $this->mergeWhen(! $hideContent, [
                'content' => $this->content,
            ]),
            'author' => $this->whenLoaded('author', fn () => $this->author->name),
            'created_at' => $this->created_at->format('d F Y H:i:s T'),
            'diff_created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->format('d F Y H:i:s T'),
        ];
    }
}

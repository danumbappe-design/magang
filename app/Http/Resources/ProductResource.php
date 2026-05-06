<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'description' => $this->description,
            'price' => $this->price,
            'creator_id' => $this->creator_id,
            'creator' => [
                'name' => $this->creator?->name,
                'email' => $this->creator?->email,
            ],
            'photos' => $this->photos->map(fn($photo) => [
                'id' => $photo->id,
                'url' => asset('storage/' . $photo->url),
            ]),
            'comments' => $this->comments->map(fn($comment) => [
                'id' => $comment->id,
                'text_comment' => $comment->text_comment,
                'user' => [
                    'name' => $comment->user?->name,
                ],
            ]),
        ];
    }
}

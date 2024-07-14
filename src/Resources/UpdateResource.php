<?php

namespace Azuriom\Plugin\Changelog\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \Azuriom\Plugin\Changelog\Models\Update */
class UpdateResource extends JsonResource
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
            'title' => $this->name,
            'content' => $this->description,
            'category' => new CategoryResource($this->category),
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}

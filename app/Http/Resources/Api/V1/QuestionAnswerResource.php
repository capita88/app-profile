<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class QuestionAnswerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'body' => $this->body,
            'answered_by' => [
                'name' => $this->user?->name,
                'avatar' => $this->user?->avatar ? Storage::disk('public')->url($this->user->avatar) : null,
            ],
            'answered_at' => $this->answered_at?->toIso8601String(),
        ];
    }
}

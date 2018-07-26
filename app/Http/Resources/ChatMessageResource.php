<?php

namespace App\Http\Resources;

use App\Helpers\Bbcode;
use ChristofferOK\LaravelEmojiOne\LaravelEmojiOne;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatMessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $emojiOne = app()->make(LaravelEmojiOne::class);

        return [
            'id' => $this->id,
            'user' => new ChatUserResource($this->whenLoaded('user')),
            'chatroom' => new ChatRoomResource($this->whenLoaded('chatroom')),
            'message' => htmlspecialchars_decode($emojiOne->toImage(Bbcode::parse("[left]﻿{$this->message}[/left]"))),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String()
        ];
    }
}

<?php

namespace App\Http\Resources;

use App\Helpers\Bbcode;
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
        return [
            'id' => $this->id,
            'user' => new UserResource($this->whenLoaded('user')),
            'chatroom' => new ChatRoomResource($this->whenLoaded('chatroom')),
            'message' => Bbcode::parse($this->message),
            'created_at' => $this->created_at->format('F jS h:i A'), // Mar 4th, 2018 at 02:15:16 PM,
            'updated_at' => $this->updated_at
        ];
    }
}

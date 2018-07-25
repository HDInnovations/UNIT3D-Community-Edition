<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatUserResource extends JsonResource
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
            'username' => $this->username,
            'chat_status' => $this->whenLoaded('chatStatus'),
            'chat_status_id' => $this->chat_status_id,
            'chatroom_id' => $this->chatroom_id,
            'group' => $this->whenLoaded('group'),
            'group_id' => $this->group_id,
            'title' => $this->title,
            'image' => $this->image
        ];
    }
}

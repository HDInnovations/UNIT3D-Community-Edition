<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Http\Resources;

use App\Helpers\Bbcode;
use ChristofferOK\LaravelEmojiOne\LaravelEmojiOne;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatMessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        $emojiOne = app()->make(LaravelEmojiOne::class);

        $logger = null;
        if ($this->user_id && $this->user_id == 1) {
            $bbcode = new Bbcode();
            $logger = $bbcode->parse('<div class="align-left"><div class="chatTriggers">'.$this->message.'</div></div>');
            $logger = $emojiOne->toImage($logger);
            $logger = str_replace('a href="/#', 'a trigger="bot" class="chatTrigger" href="/#', $logger);
        } else {
            $bbcode = new Bbcode();
            $logger = $bbcode->parse('<div class="align-left">'.$this->message.'</div>');
            $logger = $emojiOne->toImage($logger);
        }

        return [
            'id'         => $this->id,
            'bot'        => new BotResource($this->whenLoaded('bot')),
            'user'       => new UserResource($this->whenLoaded('user')),
            'receiver'   => new UserResource($this->whenLoaded('receiver')),
            'chatroom'   => new ChatRoomResource($this->whenLoaded('chatroom')),
            'message'    => htmlspecialchars_decode($logger),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}

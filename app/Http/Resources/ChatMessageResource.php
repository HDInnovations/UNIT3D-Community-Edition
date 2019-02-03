<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     Poppabear
 */

namespace App\Http\Resources;

use App\User;
use App\Helpers\Bbcode;
use Illuminate\Http\Resources\Json\JsonResource;
use ChristofferOK\LaravelEmojiOne\LaravelEmojiOne;

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

        return [
            'id' => $this->id,
            'user' => new UserResource($this->whenLoaded('user')),
            'receiver' => new UserResource($this->whenLoaded('receiver')),
            'chatroom' => new ChatRoomResource($this->whenLoaded('chatroom')),
            'message'    => htmlspecialchars_decode($emojiOne->toImage(Bbcode::parse('[left]'.clean($this->message).'[/left]'))),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}

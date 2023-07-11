<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     Roardom <roardom@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use voku\helper\AntiXSS;

/**
 * @see \Tests\Todo\Unit\Http\Requests\VoteOnPollTest
 */
class UpdatePlaylistRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $this->sanitize();

        return [
            'name' => [
                'required',
                'max:255',
            ],
            'description' => [
                'required',
                'max:65536',
            ],
            'is_private' => [
                'required',
                'boolean',
            ],
        ];
    }

    private function sanitize(): void
    {
        $input = $this->all();

        $input['description'] = htmlspecialchars((new AntiXSS())->xss_clean($input['description']), ENT_NOQUOTES);

        $this->replace($input);
    }
}

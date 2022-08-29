<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     Raordom <roardom@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Http\Livewire;

use App\Helpers\Bbcode;
use Livewire\Component;

class BbcodeInput extends Component
{
    public string $label = '';

    public string $name = '';

    public bool $isPreviewEnabled = false;

    public bool $isRequired = false;

    public string $contentBbcode = '';

    public string $contentHtml = '';

    final public function mount($name, $label, $required = false, $content = null)
    {
        $this->name = $name;
        $this->label = $label;
        $this->isRequired = $required;
        $this->contentBbcode = $content ?? old($name) ?? '';
    }

    final public function updatedIsPreviewEnabled(): void
    {
        if ($this->isPreviewEnabled) {
            $this->contentHtml = (new Bbcode())->parse($this->contentBbcode);
        }
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return \view('livewire.bbcode-input', [
            'contentHtml' => $this->contentHtml,
            'label'       => $this->label,
        ]);
    }
}

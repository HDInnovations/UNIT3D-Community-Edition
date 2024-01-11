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

namespace App\Rules;

use App\Helpers\EmailBlacklistUpdater;
use Illuminate\Support\Str;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EmailBlacklist implements ValidationRule
{
    /**
     * Array of blacklisted domains.
     */
    private array $domains = [];

    /**
     * Determine if the validation rule passes.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Load blacklisted domains
        $this->refresh();

        // Extract domain from supplied email address
        $domain = Str::after(strtolower((string) $value), '@');

        // Run validation check
        if (\in_array($domain, $this->domains)) {
            $fail('Email domain is not allowed. Throwaway email providers are blacklisted.');
        }
    }

    /**
     * Retrive the latest selection of blacklisted domains and cache them.
     */
    public function refresh(): void
    {
        $this->shouldUpdate();
        $this->domains = cache()->get(config('email-blacklist.cache-key'));
        $this->appendCustomDomains();
    }

    /**
     * Should update blacklist?.
     */
    protected function shouldUpdate(): void
    {
        $autoupdate = config('email-blacklist.auto-update');

        if ($autoupdate && !cache()->has(config('email-blacklist.cache-key'))) {
            EmailBlacklistUpdater::update();
        }
    }

    /**
     * Append custom defined blacklisted domains.
     */
    protected function appendCustomDomains(): void
    {
        $appendList = config('email-blacklist.append');

        if ($appendList === null) {
            return;
        }

        $appendDomains = explode('|', strtolower((string) $appendList));
        $this->domains = array_merge($this->domains, $appendDomains);
    }
}

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

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StoreWhitelistedImageDomainRequest;
use App\Http\Requests\Staff\UpdateWhitelistedImageDomainRequest;
use App\Models\WhitelistedImageDomain;

class WhitelistedImageDomainController extends Controller
{
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('Staff.whitelisted-image-domain.index', [
            'whitelistedImageDomains' => WhitelistedImageDomain::orderBy('domain')->get(),
        ]);
    }

    public function update(UpdateWhitelistedImageDomainRequest $request, WhitelistedImageDomain $whitelistedImageDomain): \Illuminate\Http\RedirectResponse
    {
        $whitelistedImageDomain->update($request->validated());

        cache()->forget('whitelisted-image-domains');

        return to_route('staff.whitelisted_image_domains.index')
            ->withSuccess('Domain updated successfully.');
    }

    public function store(StoreWhitelistedImageDomainRequest $request): \Illuminate\Http\RedirectResponse
    {
        WhitelistedImageDomain::create($request->validated());

        cache()->forget('whitelisted-image-domains');

        return to_route('staff.whitelisted_image_domains.index')
            ->withSuccess('New image domain whitelisted.');
    }

    public function destroy(WhitelistedImageDomain $whitelistedImageDomain): \Illuminate\Http\RedirectResponse
    {
        $whitelistedImageDomain->delete();

        cache()->forget('whitelisted-image-domains');

        return to_route('staff.whitelisted_image_domains.index')
            ->withSuccess('Domain removed from whitelist.');
    }
}

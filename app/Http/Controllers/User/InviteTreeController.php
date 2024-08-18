<?php

declare(strict_types=1);

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

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Invite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InviteTreeController extends Controller
{
    /**
     * Invite Tree.
     */
    public function index(Request $request, User $user): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        abort_unless($request->user()->group->is_modo || $request->user()->is($user), 403);

        $inviters = User::query()
            ->withTrashed()
            ->join(
                DB::raw('
                (
                    WITH RECURSIVE cte AS (
                        SELECT invites.user_id
                        FROM invites
                        WHERE invites.user_id != '.(int) User::SYSTEM_USER_ID.'
                            AND invites.accepted_by = '.(int) $user->id.'
                        UNION ALL
                        SELECT invites.user_id
                        FROM invites
                            JOIN cte
                                ON cte.user_id = invites.accepted_by
                        WHERE invites.user_id != '.(int) User::SYSTEM_USER_ID.'
                            AND invites.accepted_by IS NOT NULL
                            AND invites.accepted_by != '.(int) User::SYSTEM_USER_ID.'
                    )
                    SELECT cte.*
                    FROM cte
                ) AS tree
            '),
                fn ($join) => $join->on('users.id', '=', 'tree.user_id')
            )
            ->with('group')
            ->withCount([
                'warnings' => function ($query): void {
                    $query->whereNotNull('torrent')->where('active', '=', true);
                },
            ])
            ->get();

        $invites = Invite::query()
            ->join(
                DB::raw('
                (
                    WITH RECURSIVE cte AS (
                        SELECT invites.id, invites.accepted_by, 0 as depth, CAST(invites.accepted_by AS CHAR(200)) AS path
                        FROM invites
                        WHERE invites.user_id = '.(int) $user->id.'
                            AND invites.accepted_by IS NOT NULL
                            AND invites.accepted_by != '.(int) User::SYSTEM_USER_ID.'
                        UNION ALL
                        SELECT invites.id, invites.accepted_by, cte.depth + 1, CONCAT(cte.path, ", ", invites.accepted_by)
                        FROM invites
                            JOIN cte
                                ON cte.accepted_by = invites.user_id
                        WHERE invites.user_id != '.(int) User::SYSTEM_USER_ID.'
                            AND invites.accepted_by IS NOT NULL
                            AND invites.accepted_by != '.(int) User::SYSTEM_USER_ID.'
                    )
                    SELECT cte.*
                    FROM cte
                    ORDER BY path
                ) AS tree
            '),
                fn ($join) => $join->on('invites.id', '=', 'tree.id')
            )
            ->with([
                'receiver' => fn ($query) => $query
                    ->withTrashed()
                    ->with('group')
                    ->withAvg('history', 'seedtime')
                    ->withSum('history', 'seedtime')
                    ->withSum('seedingTorrents', 'size')
                    ->withCount([
                        'warnings' => function ($query): void {
                            $query->whereNotNull('torrent')->where('active', '=', true);
                        },
                    ])
            ])
            ->orderBy('path')
            ->get();

        return view('user.invite-tree.index', [
            'user'           => $user,
            'invites'        => $invites,
            'inviters'       => $inviters,
            'total_uploaded' => $invites
                ->filter(fn ($invite) => $request->user()->isAllowed($invite->receiver, 'profile', 'show_profile_torrent_ratio'))
                ->sum('receiver.uploaded'),
            'total_downloaded' => $invites
                ->filter(fn ($invite) => $request->user()->isAllowed($invite->receiver, 'profile', 'show_profile_torrent_ratio'))
                ->sum('receiver.downloaded'),
            'average_ratio' => $invites
                ->filter(fn ($invite) => $request->user()->isAllowed($invite->receiver, 'profile', 'show_profile_torrent_ratio'))
                ->filter(fn ($invite) => $invite->receiver->downloaded > 0)
                ->map(fn ($invite) => $invite->receiver->ratio)
                ->average(),
            'total_seedtime' => $invites
                ->filter(fn ($invite) => $request->user()->isAllowed($invite->receiver, 'profile', 'show_profile_torrent_seed'))
                ->sum('receiver.history_sum_seedtime'),
            'average_seedtime' => $invites
                ->filter(fn ($invite) => $request->user()->isAllowed($invite->receiver, 'profile', 'show_profile_torrent_seed'))
                ->avg('receiver.history_avg_seedtime'),
            'total_seedsize' => $invites
                ->filter(fn ($invite) => $request->user()->isAllowed($invite->receiver, 'profile', 'show_profile_torrent_seed'))
                ->sum('receiver.seeding_torrents_sum_size'),
            'groups' => $invites
                ->pluck('receiver.group')
                ->sortByDesc('position')
                ->groupBy('id'),
        ]);
    }
}

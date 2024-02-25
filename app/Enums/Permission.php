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

declare(strict_types=1);

namespace App\Enums;

use App\Models\GroupRole;
use App\Models\PermissionRole;
use App\Models\RoleUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

enum Permission: int
{
    case MESSAGE_CREATE = 1;
    case COMMENT_CREATE = 2;
    case ANNOUNCE_PEER_VIEW = 3;
    case REQUEST_CREATE = 4;
    case INVITE_CREATE = 5;
    case TORRENT_CREATE = 6;

    public function nameTranslationKey(): string
    {
        return 'rbac.permission-enum.'.$this->value.'.name';
    }

    public function descriptionTranslationKey(): string
    {
        return 'rbac.permission-enum.'.$this->value.'.description';
    }

    public function gate(): string
    {
        return 'permission'.$this->value;
    }

    public static function init(): void
    {
        // Has to be wrapped in a try-catch, since the framework is booted up to run
        // migrations, which includes executing these queries, which will fail.
        try {
            /*
             * $rolePermissions[$permissionId]['allow']: List of role ids that allow the specified permission
             * $rolePermissions[$permissionId]['deny']: List of role ids that deny the specified permission
             */
            $rolePermissions = cache()->remember('rbac-role-permissions', 30, function (): array {
                return PermissionRole::query()
                    ->get()
                    ->groupBy('permission_id')
                    ->map(
                        fn (Collection $roles) => $roles
                            ->groupBy(fn (PermissionRole $roles) => $roles->authorized ? 'allow' : 'deny')
                            ->map(fn (Collection $roles) => $roles->pluck('role_id'))
                    )
                    ->toArray();
            });

            /**
             * $groupRoles['group_id']: List of role ids belonging to the specified group.
             */
            $groupRoles = cache()->remember('rbac-group-roles', 30, function (): array {
                return GroupRole::query()
                    ->get()
                    ->groupBy('group_id')
                    ->map
                    ->pluck('role_id')
                    ->toArray();
            });

            /**
             * $userRoles['user_id']: List of role ids belonging to the specified user.
             */
            $userRoles = cache()->remember('rbac-user-roles', 30, function (): array {
                return RoleUser::query()
                    ->get()
                    ->groupBy('user_id')
                    ->map
                    ->pluck('role_id')
                    ->toArray();
            });

            foreach ($rolePermissions as $permissionId => $permissionRoles) {
                $permission = Permission::from($permissionId);

                Gate::define($permission->gate(), function (User $user) use ($permissionRoles, $groupRoles, $userRoles): bool {
                    $allowedCount =
                        \count(array_intersect($permissionRoles['allow'] ?? [], $groupRoles[$user->group_id] ?? [])) +
                        \count(array_intersect($permissionRoles['allow'] ?? [], $userRoles[$user->id] ?? []));
                    $deniedCount =
                        \count(array_intersect($permissionRoles['deny'] ?? [], $groupRoles[$user->group_id] ?? [])) +
                        \count(array_intersect($permissionRoles['deny'] ?? [], $userRoles[$user->id] ?? []));

                    return $deniedCount === 0 && $allowedCount > 0;
                });
            }
        } catch (QueryException $e) {
            Log::notice('RBAC query failed.', [
                'error' => $e,
            ]);

            return;
        }
    }
}

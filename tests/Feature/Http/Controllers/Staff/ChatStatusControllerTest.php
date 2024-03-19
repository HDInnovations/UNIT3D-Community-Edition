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

namespace Tests\Feature\Http\Controllers\Staff;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\Attributes\Test;
use App\Models\ChatStatus;
use App\Models\Group;
use App\Models\User;
use Database\Seeders\GroupsTableSeeder;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Staff\ChatStatusController
 */
final class ChatStatusControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function createStaffUser(): Collection|Model
    {
        return User::factory()->create([
            'group_id' => fn () => Group::factory()->create([
                'is_owner' => true,
                'is_admin' => true,
                'is_modo'  => true,
            ])->id,
        ]);
    }

    #[Test]
    public function destroy_returns_an_ok_response(): void
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();
        $chat_status = ChatStatus::factory()->create();

        $response = $this->actingAs($user)->delete(route('staff.statuses.destroy', ['chatStatus' => $chat_status]));
        $response->assertRedirect(route('staff.statuses.index'));
    }

    #[Test]
    public function index_returns_an_ok_response(): void
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();

        $response = $this->actingAs($user)->get(route('staff.statuses.index'));

        $response->assertOk();
        $response->assertViewIs('Staff.chat.status.index');
        $response->assertViewHas('chatstatuses');
    }

    #[Test]
    public function store_returns_an_ok_response(): void
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();
        $chat_status = ChatStatus::factory()->make();

        $response = $this->actingAs($user)->post(route('staff.statuses.store'), [
            'name'  => $chat_status->name,
            'color' => $chat_status->color,
            'icon'  => $chat_status->icon,
        ]);

        $response->assertRedirect(route('staff.statuses.index'));
    }

    #[Test]
    public function update_returns_an_ok_response(): void
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();
        $chat_status = ChatStatus::factory()->create();

        $response = $this->actingAs($user)->post(route('staff.statuses.update', ['chatStatus' => $chat_status]), [
            'name'  => $chat_status->name,
            'color' => $chat_status->color,
            'icon'  => $chat_status->icon,
        ]);

        $response->assertRedirect(route('staff.statuses.index'));
    }
}

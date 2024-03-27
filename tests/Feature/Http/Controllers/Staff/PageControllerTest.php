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
use App\Models\Group;
use App\Models\Page;
use App\Models\User;
use Database\Seeders\GroupsTableSeeder;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Staff\PageController
 */
final class PageControllerTest extends TestCase
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
    public function create_returns_an_ok_response(): void
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();

        $response = $this->actingAs($user)->get(route('staff.pages.create'));

        $response->assertOk();
        $response->assertViewIs('Staff.page.create');
    }

    #[Test]
    public function destroy_returns_an_ok_response(): void
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();
        $page = Page::factory()->create();

        $response = $this->actingAs($user)->delete(route('staff.pages.destroy', ['page' => $page]));

        $response->assertRedirect(route('staff.pages.index'));
    }

    #[Test]
    public function edit_returns_an_ok_response(): void
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();
        $page = Page::factory()->create();

        $response = $this->actingAs($user)->get(route('staff.pages.edit', ['page' => $page]));

        $response->assertOk();
        $response->assertViewIs('Staff.page.edit');
        $response->assertViewHas('page');
    }

    #[Test]
    public function index_returns_an_ok_response(): void
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();

        $response = $this->actingAs($user)->get(route('staff.pages.index'));

        $response->assertOk();
        $response->assertViewIs('Staff.page.index');
        $response->assertViewHas('pages');
    }

    #[Test]
    public function store_returns_an_ok_response(): void
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();
        $page = Page::factory()->make();

        $response = $this->actingAs($user)->post(route('staff.pages.store'), [
            'name'    => $page->name,
            'content' => $page->content,
        ]);

        $response->assertRedirect(route('staff.pages.index'));
    }

    #[Test]
    public function update_returns_an_ok_response(): void
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();
        $page = Page::factory()->create();

        $response = $this->actingAs($user)->patch(route('staff.pages.update', ['page' => $page]), [
            'name'    => $page->name,
            'content' => $page->content,
        ]);

        $response->assertRedirect(route('staff.pages.index'));
    }
}

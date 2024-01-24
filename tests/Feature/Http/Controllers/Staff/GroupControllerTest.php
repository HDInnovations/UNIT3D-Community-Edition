<?php

namespace Tests\Feature\Http\Controllers\Staff;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\Attributes\Test;
use App\Models\Group;
use App\Models\User;
use Database\Seeders\GroupsTableSeeder;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Staff\GroupController
 */
final class GroupControllerTest extends TestCase
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

        $response = $this->actingAs($user)->get(route('staff.groups.create'));

        $response->assertOk();
        $response->assertViewIs('Staff.group.create');
    }

    #[Test]
    public function edit_returns_an_ok_response(): void
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();
        $group = Group::factory()->create();

        $response = $this->actingAs($user)->get(route('staff.groups.edit', ['group' => $group]));

        $response->assertOk();
        $response->assertViewIs('Staff.group.edit');
        $response->assertViewHas('group');
    }

    #[Test]
    public function index_returns_an_ok_response(): void
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();

        $response = $this->actingAs($user)->get(route('staff.groups.index'));

        $response->assertOk();
        $response->assertViewIs('Staff.group.index');
        $response->assertViewHas('groups');
    }

    #[Test]
    public function store_returns_an_ok_response(): void
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();
        $group = Group::factory()->make();

        $response = $this->actingAs($user)->post(route('staff.groups.store'), [
            'name'             => $group->name,
            'slug'             => $group->slug,
            'position'         => $group->position,
            'level'            => $group->level,
            'color'            => $group->color,
            'icon'             => $group->icon,
            'effect'           => $group->effect,
            'is_internal'      => $group->is_internal,
            'is_owner'         => $group->is_owner,
            'is_admin'         => $group->is_admin,
            'is_modo'          => $group->is_modo,
            'is_editor'        => $group->is_editor,
            'is_trusted'       => $group->is_trusted,
            'is_immune'        => $group->is_immune,
            'is_freeleech'     => $group->is_freeleech,
            'is_double_upload' => $group->is_double_upload,
            'is_refundable'    => $group->is_refundable,
            'can_upload'       => $group->can_upload,
            'is_incognito'     => $group->is_incognito,
            'autogroup'        => $group->autogroup,
        ]);

        $response->assertRedirect(route('staff.groups.index'));
    }

    #[Test]
    public function update_returns_an_ok_response(): void
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();
        $group = Group::factory()->create();

        $response = $this->actingAs($user)->patch(route('staff.groups.update', ['group' => $group]), [
            'name'             => $group->name,
            'slug'             => $group->slug,
            'position'         => $group->position,
            'level'            => $group->level,
            'color'            => $group->color,
            'icon'             => $group->icon,
            'effect'           => $group->effect,
            'is_internal'      => $group->is_internal,
            'is_owner'         => $group->is_owner,
            'is_admin'         => $group->is_admin,
            'is_modo'          => $group->is_modo,
            'is_editor'        => $group->is_editor,
            'is_trusted'       => $group->is_trusted,
            'is_immune'        => $group->is_immune,
            'is_freeleech'     => $group->is_freeleech,
            'is_double_upload' => $group->is_double_upload,
            'is_refundable'    => $group->is_refundable,
            'can_upload'       => $group->can_upload,
            'is_incognito'     => $group->is_incognito,
            'autogroup'        => $group->autogroup,
        ]);

        $response->assertRedirect(route('staff.groups.index'));
    }
}

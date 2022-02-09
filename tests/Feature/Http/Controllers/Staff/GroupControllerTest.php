<?php

namespace Tests\Feature\Http\Controllers\Staff;

use App\Models\Group;
use App\Models\User;
use Database\Seeders\GroupsTableSeeder;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Staff\GroupController
 */
class GroupControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function createStaffUser(): \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
    {
        return User::factory()->create([
            'group_id' => fn () => Group::factory()->create([
                'is_owner' => true,
                'is_admin' => true,
                'is_modo'  => true,
            ])->id,
        ]);
    }

    /**
     * @test
     */
    public function create_returns_an_ok_response(): void
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();

        $response = $this->actingAs($user)->get(route('staff.groups.create'));

        $response->assertOk();
        $response->assertViewIs('Staff.group.create');
    }

    /**
     * @test
     */
    public function edit_returns_an_ok_response(): void
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();
        $group = Group::factory()->create();

        $response = $this->actingAs($user)->get(route('staff.groups.edit', ['id' => $group->id]));

        $response->assertOk();
        $response->assertViewIs('Staff.group.edit');
        $response->assertViewHas('group');
    }

    /**
     * @test
     */
    public function index_returns_an_ok_response(): void
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();

        $response = $this->actingAs($user)->get(route('staff.groups.index'));

        $response->assertOk();
        $response->assertViewIs('Staff.group.index');
        $response->assertViewHas('groups');
    }

    /**
     * @test
     */
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
            'is_trusted'       => $group->is_trusted,
            'is_immune'        => $group->is_immune,
            'is_freeleech'     => $group->is_freeleech,
            'is_double_upload' => $group->is_double_upload,
            'can_upload'       => $group->can_upload,
            'is_incognito'     => $group->is_incognito,
            'autogroup'        => $group->autogroup,
        ]);

        $response->assertRedirect(route('staff.groups.index'));
    }

    /**
     * @test
     */
    public function update_returns_an_ok_response(): void
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();
        $group = Group::factory()->create();

        $response = $this->actingAs($user)->post(route('staff.groups.update', ['id' => $group->id]), [
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
            'is_trusted'       => $group->is_trusted,
            'is_immune'        => $group->is_immune,
            'is_freeleech'     => $group->is_freeleech,
            'is_double_upload' => $group->is_double_upload,
            'can_upload'       => $group->can_upload,
            'is_incognito'     => $group->is_incognito,
            'autogroup'        => $group->autogroup,
        ]);

        $response->assertRedirect(route('staff.groups.index'));
    }
}

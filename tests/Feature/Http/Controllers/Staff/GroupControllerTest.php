<?php

namespace Tests\Feature\Http\Controllers\Staff;

use App\Models\Group;
use App\Models\User;
use GroupsTableSeeder;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Staff\GroupController
 */
class GroupControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function createStaffUser()
    {
        return factory(User::class)->create([
            'group_id' => function () {
                return factory(Group::class)->create([
                    'is_owner' => true,
                    'is_admin' => true,
                    'is_modo'  => true,
                ])->id;
            },
        ]);
    }

    /**
     * @test
     */
    public function create_returns_an_ok_response()
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
    public function edit_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();
        $group = factory(Group::class)->create();

        $response = $this->actingAs($user)->get(route('staff.groups.edit', ['id' => $group->id]));

        $response->assertOk();
        $response->assertViewIs('Staff.group.edit');
        $response->assertViewHas('group');
    }

    /**
     * @test
     */
    public function index_returns_an_ok_response()
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
    public function store_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();
        $group = factory(Group::class)->make();

        $response = $this->actingAs($user)->post(route('staff.groups.store'), [
            'name'         => $group->name,
            'slug'         => $group->slug,
            'position'     => $group->position,
            'level'        => $group->level,
            'color'        => $group->color,
            'icon'         => $group->icon,
            'effect'       => $group->effect,
            'is_internal'  => $group->is_internal,
            'is_owner'     => $group->is_owner,
            'is_admin'     => $group->is_admin,
            'is_modo'      => $group->is_modo,
            'is_trusted'   => $group->is_trusted,
            'is_immune'    => $group->is_immune,
            'is_freeleech' => $group->is_freeleech,
            'can_upload'   => $group->can_upload,
            'is_incognito' => $group->is_incognito,
            'autogroup'    => $group->autogroup,
        ]);

        $response->assertRedirect(route('staff.groups.index'));
    }

    /**
     * @test
     */
    public function update_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();
        $group = factory(Group::class)->create();

        $response = $this->actingAs($user)->post(route('staff.groups.update', ['id' => $group->id]), [
            'name'         => $group->name,
            'slug'         => $group->slug,
            'position'     => $group->position,
            'level'        => $group->level,
            'color'        => $group->color,
            'icon'         => $group->icon,
            'effect'       => $group->effect,
            'is_internal'  => $group->is_internal,
            'is_owner'     => $group->is_owner,
            'is_admin'     => $group->is_admin,
            'is_modo'      => $group->is_modo,
            'is_trusted'   => $group->is_trusted,
            'is_immune'    => $group->is_immune,
            'is_freeleech' => $group->is_freeleech,
            'can_upload'   => $group->can_upload,
            'is_incognito' => $group->is_incognito,
            'autogroup'    => $group->autogroup,
        ]);

        $response->assertRedirect(route('staff.groups.index'));
    }
}

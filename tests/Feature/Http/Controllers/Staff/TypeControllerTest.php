<?php

namespace Tests\Feature\Http\Controllers\Staff;

use App\Models\Group;
use App\Models\Type;
use App\Models\User;
use GroupsTableSeeder;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Staff\TypeController
 */
class TypeControllerTest extends TestCase
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

        $response = $this->actingAs($user)->get(route('staff.types.create'));

        $response->assertOk();
        $response->assertViewIs('Staff.type.create');
    }

    /**
     * @test
     */
    public function destroy_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();
        $type = factory(Type::class)->create();

        $response = $this->actingAs($user)->delete(route('staff.types.destroy', ['id' => $type->id]));

        $response->assertRedirect(route('staff.types.index'));
    }

    /**
     * @test
     */
    public function edit_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();
        $type = factory(Type::class)->create();

        $response = $this->actingAs($user)->get(route('staff.types.edit', ['id' => $type->id]));

        $response->assertOk();
        $response->assertViewIs('Staff.type.edit');
        $response->assertViewHas('type');
    }

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();

        $response = $this->actingAs($user)->get(route('staff.types.index'));

        $response->assertOk();
        $response->assertViewIs('Staff.type.index');
        $response->assertViewHas('types');
    }

    /**
     * @test
     */
    public function store_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();
        $type = factory(Type::class)->make();

        $response = $this->actingAs($user)->post(route('staff.types.store'), [
            'name'     => $type->name,
            'slug'     => $type->slug,
            'position' => $type->position,
        ]);

        $response->assertRedirect(route('staff.types.index'));
    }

    /**
     * @test
     */
    public function update_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();
        $type = factory(Type::class)->create();

        $response = $this->actingAs($user)->patch(route('staff.types.update', ['id' => $type->id]), [
            'name'     => $type->name,
            'slug'     => $type->slug,
            'position' => $type->position,
        ]);

        $response->assertRedirect(route('staff.types.index'));
    }
}

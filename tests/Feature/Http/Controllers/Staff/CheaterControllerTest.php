<?php

namespace Tests\Feature\Http\Controllers\Staff;

use App\Models\Group;
use App\Models\User;
use GroupsTableSeeder;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Staff\CheaterController
 */
class CheaterControllerTest extends TestCase
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
    public function index_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();

        $response = $this->actingAs($user)->get(route('staff.cheaters.index'));

        $response->assertOk();
        $response->assertViewIs('Staff.cheater.index');
        $response->assertViewHas('cheaters');
    }
}

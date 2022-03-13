<?php

namespace Tests\Feature\Http\Controllers\Staff;

use App\Models\Privilege;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\GroupsTableSeeder;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Staff\CheaterController
 */
class CheaterControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function createStaffUser(): \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
    {
        $role = Role::factory()->create();
        $privileges = Privilege::all();
        foreach ($privileges as $privilege) {
            $role->privileges()->attach($privilege);
        }

        return User::factory()->create([
            'role_id' => $role->id,
        ]);
    }

    /**
     * @test
     */
    public function index_returns_an_ok_response(): void
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();

        $response = $this->actingAs($user)->get(route('staff.cheaters.index'));

        $response->assertOk();
        $response->assertViewIs('Staff.cheater.index');
        $response->assertViewHas('cheaters');
    }
}

<?php

namespace Tests\Feature\Http\Controllers\Staff;

use App\Models\Group;
use App\Models\PrivateMessage;
use App\Models\User;
use GroupsTableSeeder;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Staff\MassActionController
 */
class MassActionControllerTest extends TestCase
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

        $response = $this->actingAs($user)->get(route('staff.mass-pm.create'));

        $response->assertOk();
        $response->assertViewIs('Staff.masspm.index');
    }

    /**
     * @test
     */
    public function store_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();
        $message = factory(PrivateMessage::class)->create();

        $response = $this->actingAs($user)->post(route('staff.mass-pm.store'), [
            'sender_id'   => $user->id,
            'receiver_id' => $message->receiver_id,
            'subject'     => $message->subject,
            'message'     => $message->message,
            'read'        => $message->read,
            'related_to'  => $message->related_to,
        ]);

        $response->assertRedirect(route('staff.mass-pm.create'));
    }

    /**
     * @test
     */
    public function update_returns_an_ok_response()
    {
        $this->seed(GroupsTableSeeder::class);

        $user = $this->createStaffUser();

        $response = $this->actingAs($user)->get(route('staff.mass-actions.validate'));

        $response->assertRedirect(route('staff.dashboard.index'));
    }
}

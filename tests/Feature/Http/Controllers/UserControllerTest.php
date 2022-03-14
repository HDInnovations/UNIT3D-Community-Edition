<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\UserController
 */
class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function accept_rules_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $response = $this->post(route('accept.rules'), [
            // TODO: send request data
        ]);

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function active_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\Models\User::factory()->create();
        $peers = \App\Models\Peer::factory()->times(3)->create();

        $response = $this->get(route('user_active', ['username' => $user->username]));

        $response->assertOk();
        $response->assertViewIs('user.private.active');
        $response->assertViewHas('user', $user);
        $response->assertViewHas('route');
        $response->assertViewHas('active');
        $response->assertViewHas('his_upl');
        $response->assertViewHas('his_upl_cre');
        $response->assertViewHas('his_downl');
        $response->assertViewHas('his_downl_cre');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function active_by_client_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\Models\User::factory()->create();
        $peers = \App\Models\Peer::factory()->times(3)->create();

        $response = $this->get(route('user_active_by_client', ['username' => $user->username, 'ip' => $user->ip, 'port' => $user->port]));

        $response->assertOk();
        $response->assertViewIs('user.private.active');
        $response->assertViewHas('user', $user);
        $response->assertViewHas('route');
        $response->assertViewHas('active');
        $response->assertViewHas('his_upl');
        $response->assertViewHas('his_upl_cre');
        $response->assertViewHas('his_downl');
        $response->assertViewHas('his_downl_cre');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function change_api_token_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\Models\User::factory()->create();

        $response = $this->post(route('change_api_token', ['username' => $user->username]), [
            // TODO: send request data
        ]);

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function change_p_i_d_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\Models\User::factory()->create();

        $response = $this->post(route('change_pid', ['username' => $user->username]), [
            // TODO: send request data
        ]);

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function change_r_i_d_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\Models\User::factory()->create();

        $response = $this->post(route('change_rid', ['username' => $user->username]), [
            // TODO: send request data
        ]);

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function change_settings_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\Models\User::factory()->create();

        $response = $this->post(route('change_settings', ['username' => $user->username]), [
            // TODO: send request data
        ]);

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function disable_notifications_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\Models\User::factory()->create();

        $response = $this->post(route('notification_disable', ['username' => $user->username]), [
            // TODO: send request data
        ]);

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function download_history_torrents_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\Models\User::factory()->create();
        $torrent = \App\Models\Torrent::factory()->create();

        $response = $this->get(route('download_history_torrents', ['username' => $user->username]));

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function downloads_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\Models\User::factory()->create();
        $histories = \App\Models\History::factory()->times(3)->create();

        $response = $this->get(route('user_downloads', ['username' => $user->username]));

        $response->assertOk();
        $response->assertViewIs($logger);
        $response->assertViewHas('route');
        $response->assertViewHas('user', $user);
        $response->assertViewHas('downloads');
        $response->assertViewHas('his_upl');
        $response->assertViewHas('his_upl_cre');
        $response->assertViewHas('his_downl');
        $response->assertViewHas('his_downl_cre');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function edit_profile_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\Models\User::factory()->create();

        $response = $this->post(route('user_edit_profile', ['username' => $user->username]), [
            // TODO: send request data
        ]);

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function edit_profile_form_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\Models\User::factory()->create();

        $response = $this->get(route('user_edit_profile_form', ['username' => $user->username]));

        $response->assertOk();
        $response->assertViewIs('user.edit_profile');
        $response->assertViewHas('user', $user);
        $response->assertViewHas('route');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function enable_notifications_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\Models\User::factory()->create();

        $response = $this->post(route('notification_enable', ['username' => $user->username]), [
            // TODO: send request data
        ]);

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function flush_own_ghost_peers_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\Models\User::factory()->create();
        $history = \App\Models\History::factory()->create();
        $peers = \App\Models\Peer::factory()->times(3)->create();

        $response = $this->post(route('flush_own_ghost_peers', ['username' => $user->username]), [
            // TODO: send request data
        ]);

        $response->assertRedirect(withErrors('You can only flush twice a day!'));

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function followers_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\Models\User::factory()->create();
        $follows = \App\Models\Follow::factory()->times(3)->create();

        $response = $this->get(route('user_followers', ['username' => $user->username]));

        $response->assertOk();
        $response->assertViewIs('user.followers');
        $response->assertViewHas('route');
        $response->assertViewHas('results');
        $response->assertViewHas('user', $user);

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function get_bans_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\Models\User::factory()->create();
        $bans = \App\Models\Ban::factory()->times(3)->create();

        $response = $this->get(route('banlog', ['username' => $user->username]));

        $response->assertOk();
        $response->assertViewIs('user.banlog');
        $response->assertViewHas('user', $user);
        $response->assertViewHas('bans', $bans);

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function make_hidden_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\Models\User::factory()->create();

        $response = $this->post(route('user_hidden', ['username' => $user->username]), [
            // TODO: send request data
        ]);

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function make_private_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\Models\User::factory()->create();

        $response = $this->post(route('user_private', ['username' => $user->username]), [
            // TODO: send request data
        ]);

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function make_public_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\Models\User::factory()->create();

        $response = $this->post(route('user_public', ['username' => $user->username]), [
            // TODO: send request data
        ]);

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function make_visible_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\Models\User::factory()->create();

        $response = $this->post(route('user_visible', ['username' => $user->username]), [
            // TODO: send request data
        ]);

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function my_filter_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\Models\User::factory()->create();

        $response = $this->post(route('myfilter', ['username' => $user->username]), [
            // TODO: send request data
        ]);

        $response->assertOk();
        $response->assertViewIs();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function notification_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\Models\User::factory()->create();
        $roles = \App\Models\Role::factory()->times(3)->create();

        $response = $this->get(route('user_notification', ['username' => $user->username]));

        $response->assertOk();
        $response->assertViewIs('user.notification');
        $response->assertViewHas('user', $user);
        $response->assertViewHas('groups');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function posts_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\Models\User::factory()->create();
        $posts = \App\Models\Post::factory()->times(3)->create();

        $response = $this->get(route('user_posts', ['username' => $user->username]));

        $response->assertOk();
        $response->assertViewIs('user.posts');
        $response->assertViewHas('route');
        $response->assertViewHas('results');
        $response->assertViewHas('user', $user);

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function privacy_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\Models\User::factory()->create();
        $roles = \App\Models\Role::factory()->times(3)->create();

        $response = $this->get(route('user_privacy', ['username' => $user->username]));

        $response->assertOk();
        $response->assertViewIs('user.privacy');
        $response->assertViewHas('user', $user);
        $response->assertViewHas('groups');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function requested_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\Models\User::factory()->create();
        $torrentRequests = \App\Models\TorrentRequest::factory()->times(3)->create();

        $response = $this->get(route('user_requested', ['username' => $user->username]));

        $response->assertOk();
        $response->assertViewIs($logger);
        $response->assertViewHas('route');
        $response->assertViewHas('user', $user);
        $response->assertViewHas('torrentRequests', $torrentRequests);

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function resurrections_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\Models\User::factory()->create();
        $graveyards = \App\Models\Graveyard::factory()->times(3)->create();

        $response = $this->get(route('user_resurrections', ['username' => $user->username]));

        $response->assertOk();
        $response->assertViewIs('user.private.resurrections');
        $response->assertViewHas('route');
        $response->assertViewHas('user', $user);
        $response->assertViewHas('resurrections');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function security_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\Models\User::factory()->create();

        $response = $this->get(route('user_security', ['username' => $user->username]));

        $response->assertOk();
        $response->assertViewIs('user.security');
        $response->assertViewHas('user', $user);

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function seeds_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\Models\User::factory()->create();
        $peers = \App\Models\Peer::factory()->times(3)->create();

        $response = $this->get(route('user_seeds', ['username' => $user->username]));

        $response->assertOk();
        $response->assertViewIs('user.private.seeds');
        $response->assertViewHas('user', $user);
        $response->assertViewHas('route');
        $response->assertViewHas('seeds');
        $response->assertViewHas('his_upl');
        $response->assertViewHas('his_upl_cre');
        $response->assertViewHas('his_downl');
        $response->assertViewHas('his_downl_cre');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function settings_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\Models\User::factory()->create();

        $response = $this->get(route('user_settings', ['username' => $user->username]));

        $response->assertOk();
        $response->assertViewIs('user.settings');
        $response->assertViewHas('user', $user);
        $response->assertViewHas('route');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function show_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\Models\User::factory()->create();
        $invite = \App\Models\Invite::factory()->create();
        $roles = \App\Models\Role::factory()->times(3)->create();
        $follows = \App\Models\Follow::factory()->times(3)->create();
        $warnings = \App\Models\Warning::factory()->times(3)->create();
        $peers = \App\Models\Peer::factory()->times(3)->create();

        $response = $this->get(route('users.show', ['username' => $user->username]));

        $response->assertOk();
        $response->assertViewIs('user.profile');
        $response->assertViewHas('route');
        $response->assertViewHas('user', $user);
        $response->assertViewHas('roles', $roles);
        $response->assertViewHas('followers');
        $response->assertViewHas('history');
        $response->assertViewHas('warnings', $warnings);
        $response->assertViewHas('hitrun');
        $response->assertViewHas('realdownload');
        $response->assertViewHas('def_download');
        $response->assertViewHas('his_down');
        $response->assertViewHas('free_down');
        $response->assertViewHas('realupload');
        $response->assertViewHas('def_upload');
        $response->assertViewHas('his_upl');
        $response->assertViewHas('multi_upload');
        $response->assertViewHas('bonupload');
        $response->assertViewHas('man_upload');
        $response->assertViewHas('requested');
        $response->assertViewHas('filled');
        $response->assertViewHas('invitedBy');
        $response->assertViewHas('peers', $peers);

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function topics_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\Models\User::factory()->create();
        $topics = \App\Models\Topic::factory()->times(3)->create();

        $response = $this->get(route('user_topics', ['username' => $user->username]));

        $response->assertOk();
        $response->assertViewIs('user.topics');
        $response->assertViewHas('route');
        $response->assertViewHas('results');
        $response->assertViewHas('user', $user);

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function torrents_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\Models\User::factory()->create();
        $histories = \App\Models\History::factory()->times(3)->create();

        $response = $this->get(route('user_torrents', ['username' => $user->username]));

        $response->assertOk();
        $response->assertViewIs('user.private.torrents');
        $response->assertViewHas('route');
        $response->assertViewHas('user', $user);
        $response->assertViewHas('history');
        $response->assertViewHas('his_upl');
        $response->assertViewHas('his_upl_cre');
        $response->assertViewHas('his_downl');
        $response->assertViewHas('his_downl_cre');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function unsatisfieds_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\Models\User::factory()->create();
        $histories = \App\Models\History::factory()->times(3)->create();

        $response = $this->get(route('user_unsatisfieds', ['username' => $user->username]));

        $response->assertOk();
        $response->assertViewIs('user.private.unsatisfieds');
        $response->assertViewHas('route');
        $response->assertViewHas('user', $user);
        $response->assertViewHas('downloads');
        $response->assertViewHas('his_upl');
        $response->assertViewHas('his_upl_cre');
        $response->assertViewHas('his_downl');
        $response->assertViewHas('his_downl_cre');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function uploads_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = \App\Models\User::factory()->create();
        $torrents = \App\Models\Torrent::factory()->times(3)->create();

        $response = $this->get(route('user_uploads', ['username' => $user->username]));

        $response->assertOk();
        $response->assertViewIs($logger);
        $response->assertViewHas('route');
        $response->assertViewHas('user', $user);
        $response->assertViewHas('uploads');
        $response->assertViewHas('his_upl');
        $response->assertViewHas('his_upl_cre');
        $response->assertViewHas('his_downl');
        $response->assertViewHas('his_downl_cre');

        // TODO: perform additional assertions
    }

    // test cases...
}

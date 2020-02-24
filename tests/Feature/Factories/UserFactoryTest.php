<?php

namespace Tests\Feature\Factories;

use App\Models\User;
use Tests\TestCase;

class UserFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function factoryReturnsCorrectValuesWhenCreated()
    {
        $user = factory(User::class)->create();

        $this->assertInstanceOf(User::class, $user);

        $this->assertArrayHasKey('username', $user);
        $this->assertArrayHasKey('email', $user);
        $this->assertArrayHasKey('password', $user);
        $this->assertArrayHasKey('passkey', $user);
        $this->assertArrayHasKey('group_id', $user);
        $this->assertArrayHasKey('active', $user);
        $this->assertArrayHasKey('uploaded', $user);
        $this->assertArrayHasKey('downloaded', $user);
        $this->assertArrayHasKey('image', $user);
        $this->assertArrayHasKey('title', $user);
        $this->assertArrayHasKey('about', $user);
        $this->assertArrayHasKey('signature', $user);
        $this->assertArrayHasKey('fl_tokens', $user);
        $this->assertArrayHasKey('seedbonus', $user);
        $this->assertArrayHasKey('invites', $user);
        $this->assertArrayHasKey('hitandruns', $user);
        $this->assertArrayHasKey('rsskey', $user);
        $this->assertArrayHasKey('chatroom_id', $user);
        $this->assertArrayHasKey('censor', $user);
        $this->assertArrayHasKey('chat_hidden', $user);
        $this->assertArrayHasKey('hidden', $user);
        $this->assertArrayHasKey('style', $user);
        $this->assertArrayHasKey('nav', $user);
        $this->assertArrayHasKey('torrent_layout', $user);
        $this->assertArrayHasKey('torrent_filters', $user);
        $this->assertArrayHasKey('custom_css', $user);
        $this->assertArrayHasKey('ratings', $user);
        $this->assertArrayHasKey('read_rules', $user);
        $this->assertArrayHasKey('can_chat', $user);
        $this->assertArrayHasKey('can_comment', $user);
        $this->assertArrayHasKey('can_download', $user);
        $this->assertArrayHasKey('can_request', $user);
        $this->assertArrayHasKey('can_invite', $user);
        $this->assertArrayHasKey('can_upload', $user);
        $this->assertArrayHasKey('show_poster', $user);
        $this->assertArrayHasKey('peer_hidden', $user);
        $this->assertArrayHasKey('private_profile', $user);
        $this->assertArrayHasKey('block_notifications', $user);
        $this->assertArrayHasKey('stat_hidden', $user);
        $this->assertArrayHasKey('twostep', $user);
        $this->assertArrayHasKey('remember_token', $user);
        $this->assertArrayHasKey('api_token', $user);
        //$this->assertArrayHasKey('last_login', $user);
        $this->assertArrayHasKey('last_action', $user);
        //$this->assertArrayHasKey('disabled_at', $user);
        //$this->assertArrayHasKey('deleted_by', $user);
        $this->assertArrayHasKey('locale', $user);
        $this->assertArrayHasKey('chat_status_id', $user);
    }
}

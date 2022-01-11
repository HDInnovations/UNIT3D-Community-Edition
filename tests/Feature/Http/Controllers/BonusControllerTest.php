<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\BonExchange;
use App\Models\Post;
use App\Models\Torrent;
use App\Models\User;
use Database\Seeders\BotsTableSeeder;
use Database\Seeders\ChatroomTableSeeder;
use Database\Seeders\GroupsTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\BonusController
 */
class BonusControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(GroupsTableSeeder::class);
    }

    /** @test */
    public function bonus_returns_an_ok_response(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('bonus'));

        $response->assertOk()
            ->assertViewIs('bonus.index')
            ->assertViewHas('userbon')
            ->assertViewHas('dying')
            ->assertViewHas('legendary')
            ->assertViewHas('old')
            ->assertViewHas('huge')
            ->assertViewHas('large')
            ->assertViewHas('regular')
            ->assertViewHas('participant')
            ->assertViewHas('teamplayer')
            ->assertViewHas('committed')
            ->assertViewHas('mvp')
            ->assertViewHas('legend')
            ->assertViewHas('total')
            ->assertViewHas('daily')
            ->assertViewHas('weekly')
            ->assertViewHas('monthly')
            ->assertViewHas('yearly')
            ->assertViewHas('username')
            ->assertViewHas('minute')
            ->assertViewHas('second');
    }

    /** @test */
    public function upload_exchange_returns_an_ok_response(): void
    {
        // User's seed bonus must be >= cost for exchange to succeed.

        $user = User::factory()->create([
            'seedbonus' => 2,
        ]);

        $bon = BonExchange::factory()->create([
            'cost'               => 1,
            'upload'             => true,
            'download'           => false,
            'personal_freeleech' => false,
            'invite'             => false,
        ]);

        $response = $this->actingAs($user)->post(route('bonus_exchange', ['id' => $bon->id]));

        $response->assertRedirect(route('bonus_store'))
            ->assertSessionHas('success', 'Bonus Exchange Successful');
    }

    /** @test */
    public function download_exchange_returns_an_ok_response(): void
    {
        // User's seed bonus must be >= cost for exchange to succeed.

        // Likewise, User's downloaded value must be >= Bon value.

        $user = User::factory()->create([
            'seedbonus'  => 2,
            'downloaded' => 2,
        ]);

        $bon = BonExchange::factory()->create([
            'cost'               => 1,
            'value'              => 1,
            'upload'             => false,
            'download'           => true,
            'personal_freeleech' => false,
            'invite'             => false,
        ]);

        $response = $this->actingAs($user)->post(route('bonus_exchange', ['id' => $bon->id]));

        $response->assertRedirect(route('bonus_store'))
            ->assertSessionHas('success', 'Bonus Exchange Successful');
    }

    /** @test */
    public function personal_freeleech_exchange_returns_an_ok_response(): void
    {
        // User's seed bonus must be >= cost for exchange to succeed.

        // Likewise, User's downloaded value must be >= Bon value.

        $user = User::factory()->create([
            'seedbonus'  => 2,
            'downloaded' => 2,
        ]);

        $bon = BonExchange::factory()->create([
            'cost'               => 1,
            'value'              => 1,
            'upload'             => false,
            'download'           => false,
            'personal_freeleech' => true,
            'invite'             => false,
        ]);

        $response = $this->actingAs($user)->post(route('bonus_exchange', ['id' => $bon->id]));

        $response->assertRedirect(route('bonus_store'))
            ->assertSessionHas('success', 'Bonus Exchange Successful');
    }

    /** @test */
    public function gift_returns_an_ok_response(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('bonus_gift'));

        $response->assertOk()
            ->assertViewIs('bonus.gift')
            ->assertViewHas('userbon');
    }

    /** @test */
    public function gifts_returns_an_ok_response(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('bonus_gifts'));

        $response->assertOk()
            ->assertViewIs('bonus.gifts')
            ->assertViewHas('user')
            ->assertViewHas('gifttransactions')
            ->assertViewHas('userbon')
            ->assertViewHas('gifts_sent')
            ->assertViewHas('gifts_received');
    }

    /** @test */
    public function send_gift_returns_an_ok_response(): void
    {
        $this->seed(UsersTableSeeder::class);
        $this->seed(BotsTableSeeder::class);
        $this->seed(ChatroomTableSeeder::class);

        $senderUser = User::factory()->create([
            'seedbonus' => 2,
        ]);

        $recipientUser = User::factory()->create();

        $response = $this->actingAs($senderUser)->post(route('bonus_send_gift'), [
            'to_username'   => $recipientUser->username,
            'bonus_message' => 'foo',
            'bonus_points'  => 1,
        ]);

        $response->assertRedirect(route('bonus_gift'))
            ->assertSessionHas('success', 'Gift Sent');
    }

    /** @test */
    public function store_returns_an_ok_response(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('bonus_store'));

        $response->assertOk()
            ->assertViewIs('bonus.store')
            ->assertViewHas('userbon')
            ->assertViewHas('activefl')
            ->assertViewHas('bontransactions')
            ->assertViewHas('uploadOptions')
            ->assertViewHas('downloadOptions')
            ->assertViewHas('personalFreeleech')
            ->assertViewHas('invite');
    }

    /** @test */
    public function tip_poster_returns_an_ok_response(): void
    {
        // User's seed bonus must be >= tip amount for exchange to succeed.

        $user = User::factory()->create([
            'seedbonus' => 2,
        ]);

        $post = Post::factory()->create();

        $response = $this->actingAs($user)->post(route('tip_poster', ['id' => $post->id]), [
            'post' => $post->id,
            'tip'  => '1',
        ]);

        $response->assertRedirect(route('forum_topic', ['id' => $post->topic->id]))
            ->assertSessionHas('success', 'Your Tip Was Successfully Applied!');
    }

    /** @test */
    public function tip_uploader_returns_an_ok_response(): void
    {
        // User's seed bonus must be >= tip amount for exchange to succeed.

        $user = User::factory()->create([
            'seedbonus' => 2,
        ]);

        $torrent = Torrent::factory()->create();

        $response = $this->actingAs($user)->post(route('tip_uploader', ['id' => $torrent->id]), [
            'tip' => 1,
        ]);

        $response->assertRedirect(route('torrent', ['id' => $torrent->id]))
            ->assertSessionHas('success', 'Your Tip Was Successfully Applied!');
    }

    /** @test */
    public function tips_returns_an_ok_response(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('bonus_tips'));

        $response->assertOk()
            ->assertViewIs('bonus.tips')
            ->assertViewHas('user')
            ->assertViewHas('bontransactions')
            ->assertViewHas('userbon')
            ->assertViewHas('tips_sent')
            ->assertViewHas('tips_received');
    }
}

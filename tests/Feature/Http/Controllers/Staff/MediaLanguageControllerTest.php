<?php

namespace Tests\Feature\Http\Controllers\Staff;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Staff\MediaLanguageController
 */
class MediaLanguageControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function create_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $response = $this->get(route('staff.media_languages.create'));

        $response->assertOk();
        $response->assertViewIs('Staff.media_language.create');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function destroy_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $mediaLanguage = \App\Models\MediaLanguage::factory()->create();

        $response = $this->delete(route('staff.media_languages.destroy', ['id' => $mediaLanguage->id]));

        $response->assertOk();
        $this->assertDeleted($staff.mediaLanguage);

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function edit_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $mediaLanguage = \App\Models\MediaLanguage::factory()->create();

        $response = $this->get(route('staff.media_languages.edit', ['id' => $mediaLanguage->id]));

        $response->assertOk();
        $response->assertViewIs('Staff.media_language.edit');
        $response->assertViewHas('media_language');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $mediaLanguages = \App\Models\MediaLanguage::factory()->times(3)->create();

        $response = $this->get(route('staff.media_languages.index'));

        $response->assertOk();
        $response->assertViewIs('Staff.media_language.index');
        $response->assertViewHas('media_languages');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function store_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $response = $this->post(route('staff.media_languages.store'), [
            // TODO: send request data
        ]);

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function update_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $mediaLanguage = \App\Models\MediaLanguage::factory()->create();

        $response = $this->post(route('staff.media_languages.update', ['id' => $mediaLanguage->id]), [
            // TODO: send request data
        ]);

        $response->assertOk();

        // TODO: perform additional assertions
    }

    // test cases...
}

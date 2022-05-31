<?php

declare(strict_types=1);

namespace Tests\Todo\Feature\Http\Controllers\Staff;

use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Staff\ReportController
 */
class ReportControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function index_returns_an_ok_response(): void
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('staff.reports.index'));

        $response->assertOk();
        $response->assertViewIs('Staff.report.index');
        $response->assertViewHas('reports');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function show_returns_an_ok_response(): void
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $report = Report::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('staff.reports.show', ['id' => $report->id]));

        $response->assertOk();
        $response->assertViewIs('Staff.report.show');
        $response->assertViewHas('report');
        $response->assertViewHas('urls');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function update_returns_an_ok_response(): void
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $report = Report::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('staff.reports.update', ['id' => $report->id]), [
            // TODO: send request data
        ]);

        $response->assertRedirect(withErrors('This Report Has Already Been Solved'));

        // TODO: perform additional assertions
    }

    // test cases...
}

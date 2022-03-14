<?php

namespace Tests\Feature\Http\Controllers\Staff;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
    public function index_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $reports = \App\Models\Report::factory()->times(3)->create();

        $response = $this->get(route('staff.reports.index'));

        $response->assertOk();
        $response->assertViewIs('Staff.report.index');
        $response->assertViewHas('reports', $reports);

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function show_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $report = \App\Models\Report::factory()->create();

        $response = $this->get(route('staff.reports.show', ['id' => $report->id]));

        $response->assertOk();
        $response->assertViewIs('Staff.report.show');
        $response->assertViewHas('report', $report);
        $response->assertViewHas('urls');

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function update_returns_an_ok_response()
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $report = \App\Models\Report::factory()->create();

        $response = $this->post(route('staff.reports.update', ['id' => $report->id]), [
            // TODO: send request data
        ]);

        $response->assertOk();

        // TODO: perform additional assertions
    }

    // test cases...
}

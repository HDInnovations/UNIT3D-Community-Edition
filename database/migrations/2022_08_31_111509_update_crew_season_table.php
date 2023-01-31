<?php

use App\Models\Season;
use App\Models\Tv;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('crew_season', function (Blueprint $table) {
            $table->string('department')->nullable();
            $table->string('job')->nullable();
        });

        foreach (Tv::all() as $tv) {
            $data = (new \App\Services\Tmdb\Client\TV($tv->id))->getData();

            foreach ($data['seasons'] as $season) {
                $season = (new \App\Services\Tmdb\Client\Season($tv->id, \sprintf('%02d', $season['season_number'])))->getData();
                $seasonModel = Season::find($season['id']);

                if (isset($season['credits']['crew'])) {
                    foreach ($season['credits']['crew'] as $crewMember) {
                        $seasonModel->crew()->updateExistingPivot($crewMember['id'], [
                            'department' => $crewMember['department'],
                            'job'        => $crewMember['job'],
                        ]);
                    }
                }
            }
        }
    }
};

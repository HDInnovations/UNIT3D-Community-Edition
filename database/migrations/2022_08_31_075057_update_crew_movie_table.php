<?php

use App\Models\Movie;
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
        Schema::table('crew_movie', function (Blueprint $table) {
            $table->string('department')->nullable();
            $table->string('job')->nullable();
        });

        foreach (Movie::all() as $movie) {
            $crew = (new \App\Services\Tmdb\Client\Movie($movie->id))->get_crew();

            if (isset($crew)) {
                foreach ($crew as $crewMember) {
                    $movie->crew()->updateExistingPivot($crewMember['id'], [
                        'department' => $crewMember['department'],
                        'job'        => $crewMember['job'],
                    ]);
                }
            }
        }
    }
};

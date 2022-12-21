<?php

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
        Schema::table('crew_tv', function (Blueprint $table) {
            $table->string('department')->nullable();
            $table->string('job')->nullable();
        });

        foreach (Tv::all() as $tv) {
            $data = (new \App\Services\Tmdb\Client\TV($tv->id))->getData();

            if (isset($data['credits']['crew'])) {
                foreach ($data['credits']['crew'] as $crewMember) {
                    $tv->crew()->updateExistingPivot($crewMember['id'], [
                        'department' => $crewMember['department'],
                        'job'        => $crewMember['job'],
                    ]);
                }
            }
        }
    }
};

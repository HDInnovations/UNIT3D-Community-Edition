<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // Delete duplicates
        DB::table('follows')
            ->whereNotIn('id', DB::query()->fromSub(function ($query): void {
                $query->from('follows')->selectRaw('MIN(id)')->groupBy('user_id', 'target_id');
            }, 'f'))
            ->delete();

        Schema::table('follows', function (Blueprint $table): void {
            $table->dropColumn('id');
            $table->primary(['user_id', 'target_id']);
        });
    }
};

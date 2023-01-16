<?php

use App\Models\History;
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
    public function up()
    {
        History::withoutGlobalScopes()
            ->update([
                'agent'             => DB::raw("COALESCE(agent, '')"),
                'uploaded'          => DB::raw('COALESCE(uploaded, 0)'),
                'actual_uploaded'   => DB::raw('COALESCE(actual_uploaded, 0)'),
                'client_uploaded'   => DB::raw('COALESCE(client_uploaded, 0)'),
                'downloaded'        => DB::raw('COALESCE(downloaded, 0)'),
                'actual_downloaded' => DB::raw('COALESCE(actual_downloaded, 0)'),
                'client_downloaded' => DB::raw('COALESCE(client_downloaded, 0)'),
                'updated_at'        => DB::raw('updated_at'),
            ]);

        Schema::table('history', function (Blueprint $table) {
            $table->dropForeign(['info_hash']);
            $table->dropIndex('info_hash');
            $table->dropColumn('info_hash');

            $table->string('agent', 64)->nullable(false)->change();
            $table->unsignedBigInteger('uploaded')->nullable(false)->default('0')->change();
            $table->unsignedBigInteger('actual_uploaded')->nullable(false)->default('0')->change();
            $table->unsignedBigInteger('client_uploaded')->nullable(false)->change();
            $table->unsignedBigInteger('downloaded')->nullable(false)->default('0')->change();
            $table->unsignedBigInteger('actual_downloaded')->nullable(false)->default('0')->change();
            $table->unsignedBigInteger('client_downloaded')->nullable(false)->change();
            $table->boolean('seeder')->default(null)->change();
            $table->boolean('active')->default('1')->change();
            $table->boolean('immune')->default(null)->change();
        });
    }
};

<?php

use App\Models\Peer;
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
        Peer::truncate();

        Schema::disableForeignKeyConstraints();

        Schema::table('peers', function (Blueprint $table) {
            $table->dropColumn(['md5_peer_id', 'info_hash']);
            $table->unsignedSmallInteger('port')->nullable(false)->change();
            $table->string('agent', 64)->nullable(false)->change();
            $table->unsignedBigInteger('uploaded')->nullable(false)->change();
            $table->unsignedBigInteger('downloaded')->nullable(false)->change();
            $table->unsignedBigInteger('left')->nullable(false)->change();
            $table->boolean('seeder')->nullable(false)->change();
            $table->unsignedInteger('torrent_id')->nullable(false)->change();
            $table->unsignedInteger('user_id')->nullable(false)->change();
        });

        Schema::enableForeignKeyConstraints();

        DB::statement('ALTER TABLE `peers` MODIFY `peer_id` BINARY(20) NOT NULL');
        DB::statement('ALTER TABLE `peers` MODIFY `ip` VARBINARY(16) NOT NULL');
    }
};

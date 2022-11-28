<?php

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
        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn('is_incognito');
            $table->boolean('can_download')->after('can_upload')->default(true);
            $table->boolean('can_request')->after('can_download')->default(true);
            $table->boolean('can_invite')->after('can_request')->default(true);
        });
    }
};

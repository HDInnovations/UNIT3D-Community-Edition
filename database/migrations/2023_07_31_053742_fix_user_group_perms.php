<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->boolean('can_upload')->nullable()->change();
            $table->boolean('can_chat')->nullable()->change();
            $table->boolean('can_comment')->nullable()->change();
            $table->boolean('can_download')->nullable()->change();
            $table->boolean('can_request')->nullable()->change();
            $table->boolean('can_invite')->nullable()->change();
            $table->boolean('has_reached_warning_limit')->after('can_upload');
        });

        Schema::table('groups', function (Blueprint $table): void {
            $table->boolean('can_chat')->after('is_refundable');
            $table->boolean('can_comment')->after('can_chat');
            $table->boolean('can_download')->after('can_comment');
            $table->boolean('can_request')->after('can_download');
            $table->boolean('can_invite')->after('can_request');
        });

        DB::table('users')->update([
            'has_reached_warning_limit' => DB::raw('can_download'),
        ]);

        DB::table('users')->update([
            'can_upload'   => null,
            'can_chat'     => null,
            'can_download' => null,
            'can_comment'  => null,
            'can_request'  => null,
            'can_invite'   => null,
        ]);

        DB::table('groups')
            ->whereNotIn('slug', [
                'validating',
                'guest',
                'banned',
                'bot',
                'leech',
                'disabled',
                'pruned',
            ])
            ->update([
                'can_comment'  => true,
                'can_chat'     => true,
                'can_download' => true,
                'can_request'  => true,
                'can_invite'   => true,
            ]);
    }
};

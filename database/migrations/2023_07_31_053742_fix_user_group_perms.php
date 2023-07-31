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
            $table->boolean('can_request')->nullable()->change();
        });

        Schema::table('groups', function (Blueprint $table): void {
            $table->boolean('can_chat')->after('is_refundable');
            $table->boolean('can_comment')->after('can_chat');
            $table->boolean('can_request')->after('can_comment');
        });

        DB::table('users')->update([
            'can_upload'  => null,
            'can_chat'    => null,
            'can_comment' => null,
            'can_request' => null,
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
                'can_comment' => true,
                'can_chat'    => true,
                'can_request' => true,
            ]);
    }
};

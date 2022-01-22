<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $doctrineTable = $sm->listTableDetails('articles');

            if (! $doctrineTable->hasIndex('created_at')) {
                $table->index('created_at');
            }
        });

        Schema::table('bon_transactions', function (Blueprint $table) {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $doctrineTable = $sm->listTableDetails('bon_transactions;');

            if (! $doctrineTable->hasIndex('itemID')) {
                $table->index('itemID');
            }

            if (! $doctrineTable->hasIndex('sender')) {
                $table->index('sender');
            }

            if (! $doctrineTable->hasIndex('receiver')) {
                $table->index('receiver');
            }

            if (! $doctrineTable->hasIndex('torrent_id')) {
                $table->index('torrent_id');
            }
        });

        Schema::table('bookmarks', function (Blueprint $table) {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $doctrineTable = $sm->listTableDetails('bookmarks');

            if (! $doctrineTable->hasIndex('user_id')) {
                $table->index('user_id');
            }

            if (! $doctrineTable->hasIndex('torrent_id')) {
                $table->index('torrent_id');
            }
        });

        Schema::table('posts', function (Blueprint $table) {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $doctrineTable = $sm->listTableDetails('posts');

            if (! $doctrineTable->hasIndex('created_at')) {
                $table->index('created_at');
            }
        });

        Schema::table('topics', function (Blueprint $table) {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $doctrineTable = $sm->listTableDetails('topics');

            if (! $doctrineTable->hasIndex('created_at')) {
                $table->index('created_at');
            }
        });

        Schema::table('torrents', function (Blueprint $table) {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $doctrineTable = $sm->listTableDetails('torrents');

            if (! $doctrineTable->hasIndex('status')) {
                $table->index('status');
            }

            if (! $doctrineTable->hasIndex('seeders')) {
                $table->index('seeders');
            }

            if (! $doctrineTable->hasIndex('leechers')) {
                $table->index('leechers');
            }

            if (! $doctrineTable->hasIndex('sticky')) {
                $table->index('sticky');
            }

            if (! $doctrineTable->hasIndex('created_at')) {
                $table->index('created_at');
            }

            if (! $doctrineTable->hasIndex('bumped_at')) {
                $table->index('bumped_at');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $doctrineTable = $sm->listTableDetails('users');

            if (! $doctrineTable->hasIndex('deleted_at')) {
                $table->index('deleted_at');
            }
        });
    }
};

<?php

namespace Tests;

use App\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use JMac\Testing\Traits\AdditionalAssertions;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use AdditionalAssertions;
    use RefreshDatabase;

    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('config:clear');

        $this->artisan('route:clear');

        $this->artisan('cache:clear');

        // For LARAVEL_START used in sub-footer
        if (! defined('LARAVEL_START')) {
            define('LARAVEL_START', microtime(true));
        }
    }

    /**
     * Overrides the method in the default trait.
     *
     * @see https://alexvanderbist.com/posts/2019/how-migrations-might-be-slowing-down-your-laravel-tests
     */
    protected function refreshTestDatabase()
    {
        if (! RefreshDatabaseState::$migrated) {
            if (config('database.pristine-db-file')) {
                // If a flat file is defined, load it.

                $this->artisan('db:load --quiet');

                $this->artisan('migrate');
            } else {
                // Otherwise, proceed using default strategy.

                $this->artisan('migrate:fresh', [
                    '--drop-views' => $this->shouldDropViews(),
                    '--drop-types' => $this->shouldDropTypes(),
                ]);
            }

            $this->app[Kernel::class]->setArtisan(null);

            RefreshDatabaseState::$migrated = true;
        }

        $connectionsNotToTransact = ['sqlite', 'pgsql', 'sqlsrv'];

        $this->connectionsToTransact = array_keys(
            array_diff_key(config('database.connections'), array_flip($connectionsNotToTransact))
        );

        $this->beginDatabaseTransaction();
    }
}

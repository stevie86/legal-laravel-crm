<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Vite;

abstract class TestCase extends BaseTestCase
{
    /**
     * Creates the application.
     */
    public function createApplication(): Application
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        // Register all application service providers
        foreach ($app->make('config')->get('app.providers') as $provider) {
            $app->register($provider);
        }

        // Mock the Vite facade to prevent manifest not found errors during tests
        Vite::shouldReceive('asset')->andReturn('/fake-vite-asset.js');
        Vite::shouldReceive('__invoke')->andReturn('/fake-vite-asset.js');

        return $app;
    }
}
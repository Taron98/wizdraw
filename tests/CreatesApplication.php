<?php

namespace Wizdraw\Tests;

use Illuminate\Contracts\Console\Kernel;

/**
 * Class CreatesApplication
 * @package Wizdraw\Tests
 */
trait CreatesApplication
{

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

}

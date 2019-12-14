<?php


namespace App\Websocket\AsyncTasks;

use App\Console\Kernel;

/**
 * This trait allows to use laravel facades in asynchronous tasks
 *
 * Trait Laravelable
 * @package App\Websocket\AsyncTasks
 */
trait Laravelable
{
    protected $app;

    protected function setContainer()
    {
        $this->app = require __DIR__ . '/../../../bootstrap/app.php';
        $this->app->make(Kernel::class)->bootstrap();
    }
}

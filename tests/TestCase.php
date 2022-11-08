<?php

namespace Tests;

use App\Exceptions\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use LazilyRefreshDatabase;

    protected function setUp() : void
    {
        parent::setUp();
        $this->disableExceptionHandling();
    }

    protected function disableExceptionHandling(): void
    {
        $this->oldExceptionHandler = $this->app->make(
            abstract: ExceptionHandler::class
        );

        $this->app->instance(
            abstract: ExceptionHandler::class,
            instance: new class extends Handler {
                public function __construct() {}
                public function report( $e) {}
                public function render($request, \Throwable $e) {
                    throw $e;
                }
            }
        );
    }

    protected function withExceptionHandling(): static
    {
        $this->app->instance(
            abstract: ExceptionHandler::class,
            instance: $this->oldExceptionHandler
        );

        return $this;
    }
}

<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the Apache-2.0 License.
 */

namespace Plugins\SsoClient\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Debug\ExceptionHandler;
use ZhenMu\Support\Traits\ResponseTrait;

class ExceptionServiceProvider extends ServiceProvider
{
    use ResponseTrait;
    
    /**
     * Register any services.
     *
     * @return void
     */
    public function boot()
    {
        $handler = resolve(ExceptionHandler::class);

        if (method_exists($handler, 'reportable')) {
            $handler->reportable($this->reportable());
        }

        if (method_exists($handler, 'renderable')) {
            $handler->renderable($this->renderable());
        }
    }

    /**
     * Register a reportable callback.
     *
     * @param  callable  $reportUsing
     * @return \Illuminate\Foundation\Exceptions\ReportableHandler
     */
    public function reportable()
    {
        return function (\Throwable $e) {
            //
        };
    }

    /**
     * Register a renderable callback.
     *
     * @param  callable  $renderUsing
     * @return $this
     */
    public function renderable()
    {
        return function (\Throwable $e) {
            if ($e instanceof \RuntimeException) {
                return $this->fail($e->getMessage(), $e->getCode());
            }
        };
    }
}

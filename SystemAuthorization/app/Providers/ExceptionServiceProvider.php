<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the Apache-2.0 License.
 */

namespace Plugins\SystemAuthorization\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Debug\ExceptionHandler;
use ZhenMu\Support\Traits\ResponseTrait;
use Symfony\Component\HttpFoundation\Response;

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
                if (!\request()->wantsJson()) {
                    return back()->with([
                        'tips' => $e->getMessage(),
                        'tips_type' => 'error',
                    ]);
                }
            }
            
            if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                return $this->fail('登录失败，请稍后重试', $e->getCode() ?: config('laravel-init-template.auth.unauthorize_code', 401));
            }

            if ($e instanceof \Illuminate\Validation\ValidationException) {
                return $this->fail($e->validator->errors()->first(), Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return $this->fail('404 Data Not Found.', Response::HTTP_NOT_FOUND);
            }

            if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                return $this->fail('404 Url Not Found.', Response::HTTP_NOT_FOUND);
            }

            \info('error', [
                'class' => get_class($e),
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
                'file_line' => sprintf('%s:%s', $e->getFile(), $e->getLine()),
            ]);

            return $this->fail($e->getMessage(), $e->getCode() ?: Response::HTTP_INTERNAL_SERVER_ERROR);
        };
    }
}

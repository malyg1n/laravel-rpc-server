<?php

declare(strict_types = 1);

namespace Malyg1n\JsonRpcServer;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Malyg1n\JsonRpcServer\Http\JsonRpcController;

class JsonRpcServerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        Route::macro('rpc', fn (string $uri, string $operation, ?string $delimiter = null) => Route::post($uri, [JsonRpcController::class, '__invoke'])
                ->setDefaults([
                    'operation'  => $operation,
                    'delimiter'  => $delimiter,
                ]));

        $this->app->singleton(Kernel::class, fn ($container) => new Kernel($container));
    }
}

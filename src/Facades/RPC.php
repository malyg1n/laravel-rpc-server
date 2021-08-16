<?php

declare(strict_types = 1);

namespace Malyg1n\JsonRpcServer\Facades;

use Malyg1n\JsonRpcServer\Kernel;
use Illuminate\Support\Facades\Facade;
use Malyg1n\JsonRpcServer\Http\Request;

/**
 * Class RPC.
 *
 * @method static void bind(string $key, callable|string $binder)
 * @method static array bindResolve(Request $request): array
 *
 * @see \Malyg1n\JsonRpcServer\Kernel
 */
class RPC extends Facade
{
    /**
     * @return string
     */
    public static function getFacadeAccessor(): string
    {
        return Kernel::class;
    }
}

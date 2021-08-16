<?php

declare(strict_types = 1);

namespace Malyg1n\LaravelRpcServer\Exceptions;

class RuntimeRpcException extends RpcException
{
    /**
     * @return string
     */
    protected function getDefaultMessage(): string
    {
        return 'Unknown';
    }

    /**
     * @return int
     */
    protected function getDefaultCode(): int
    {
        return -1;
    }
}

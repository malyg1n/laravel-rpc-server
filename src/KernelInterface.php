<?php

namespace Malyg1n\JsonRpcServer;

interface KernelInterface
{
    /**
     * @param string         $key
     * @param Closure|string $action
     */
    public function bind(string $key, $action): void;
}

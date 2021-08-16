<?php

declare(strict_types = 1);

namespace Malyg1n\JsonRpcServer\Exceptions;

class InvalidParams extends RpcException
{
    /**
     * @param mixed|null $data
     */
    public function __construct($data = null)
    {
        parent::__construct();

        $this->setData($data);
    }

    /**
     * @return int
     */
    protected function getDefaultCode(): int
    {
        return -32602;
    }

    /**
     * @return string
     */
    protected function getDefaultMessage(): string
    {
        return 'Invalid params';
    }
}

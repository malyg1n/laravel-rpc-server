<?php

declare(strict_types = 1);

namespace Malyg1n\JsonRpcServer\Exceptions;

class JsonException extends RpcException
{
    /**
     * @param null $data
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
        return -32700;
    }

    /**
     * @return string
     */
    protected function getDefaultMessage(): string
    {
        return 'Json parsing error';
    }
}

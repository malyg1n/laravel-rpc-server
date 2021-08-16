<?php

declare(strict_types = 1);

namespace Malyg1n\JsonRpcServer\Exceptions;

class InternalErrorException extends RpcException
{
    /**
     * InternalErrorException constructor.
     *
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
        return -32603;
    }

    /**
     * @return string
     */
    protected function getDefaultMessage(): string
    {
        return 'Internal error';
    }
}

<?php

declare(strict_types = 1);

namespace Malyg1n\JsonRpcServer\Exceptions;

abstract class RpcException extends \RuntimeException implements \JsonSerializable
{
    protected $data;

    /**
     * @param string|null            $message
     * @param int|null               $code
     * @param \RuntimeException|null $previous
     */
    public function __construct(?string $message = null, ?int $code = null, ?\RuntimeException $previous = null)
    {
        parent::__construct(
            $message ?? $this->getDefaultMessage(),
            $code ?? $this->getDefaultCode(),
            $previous
        );
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed|null $data
     *
     * @return $this
     */
    public function setData($data = null): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'code'    => $this->getCode(),
            'message' => $this->getMessage(),
            'data'    => $this->getData(),
        ];
    }

    /**
     * @return string
     */
    abstract protected function getDefaultMessage(): string;

    /**
     * @return int
     */
    abstract protected function getDefaultCode(): int;
}

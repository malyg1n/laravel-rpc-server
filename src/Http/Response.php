<?php

declare(strict_types = 1);

namespace Malyg1n\JsonRpcServer\Http;

class Response implements \JsonSerializable
{
    /**
     * @var \Exception|null
     */
    protected ?\Exception $error = null;

    /**
     * @var int|string|null
     */
    protected $id;

    /**
     * @var mixed
     */
    protected $result;

    /**
     * @var string|null
     */
    protected ?string $version;

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $response = ['id' => $this->getId()];

        if ($this->isError()) {
            $response['error'] = $this->getError();
        } else {
            $response['result'] = $this->getResult();
        }

        if (null !== ($version = $this->getVersion())) {
            $response['jsonrpc'] = $version;
        }

        return $response;
    }

    /**
     * @return int|string|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int|string|null $name
     *
     * @return self
     */
    public function setId($name): self
    {
        $this->id = $name;

        return $this;
    }

    /**
     * @return bool
     */
    public function isError(): bool
    {
        return $this->getError() instanceof \Exception;
    }

    /**
     * @return \Exception|null
     */
    public function getError(): ?\Exception
    {
        return $this->error;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $value
     *
     * @return self
     */
    public function setResult($value): self
    {
        if ($value instanceof \Exception) {
            $this->setError($value);

            return $this;
        }

        $this->result = $value;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getVersion(): ?string
    {
        return $this->version;
    }

    /**
     * @param string $version
     *
     * @return self
     */
    public function setVersion(string $version): self
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @param \Exception|null $error
     *
     * @return Response
     */
    public function setError(?\Exception $error = null): self
    {
        $this->error = $error;

        return $this;
    }
}

<?php

declare(strict_types = 1);

namespace Malyg1n\JsonRpcServer\Http;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class Request implements \JsonSerializable
{
    /**
     * Request ID.
     *
     * @var mixed
     */
    protected $id;

    /**
     * Requested method.
     *
     * @var string|null
     */
    protected ?string $method;

    /**
     * Request parameters.
     *
     * @var Collection
     */
    protected Collection $params;

    /**
     * JSON-RPC version of request.
     *
     * @var string
     */
    protected string $version = '2.0';

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->params = new Collection();
    }

    /**
     * Set request state based on array.
     *
     * @param array $collection
     *
     * @return Request
     */
    public static function loadArray(array $collection): self
    {
        $request = new static();
        $methods = get_class_methods($request);

        Collect($collection)
            ->each(static function ($value, string $key) use ($request, $methods): void {
                $method = Str::start(ucfirst($key), 'set');

                if (\in_array($method, $methods, true)) {
                    $request->{$method}($value);
                }

                if ($key === 'jsonrpc') {
                    $request->setVersion($value);
                }
            });

        return $request;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $jsonArray = [
            'jsonrpc' => $this->getVersion(),
            'method'  => $this->getMethod(),
        ];

        if ($this->getParams()->isNotEmpty()) {
            $jsonArray['params'] = $this->getParams()->toArray();
        }

        if (null !== ($id = $this->getId())) {
            $jsonArray['id'] = $id;
        }

        return $jsonArray;
    }

    /**
     * Retrieve JSON-RPC version.
     *
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * Set JSON-RPC version.
     *
     * @param string $version
     *
     * @return Request
     */
    public function setVersion(string $version = '2.0'): self
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get request method name.
     *
     * @return string|null
     */
    public function getMethod(): ?string
    {
        return $this->method;
    }

    /**
     * Set request method.
     *
     * @param string $name
     *
     * @return Request
     */
    public function setMethod(string $name): self
    {
        $this->method = $name;

        return $this;
    }

    /**
     * Retrieve parameters.
     *
     * @return Collection
     */
    public function getParams(): Collection
    {
        return $this->params;
    }

    /**
     * Overwrite params.
     *
     * @param array $params
     *
     * @return Request
     */
    public function setParams(array $params)
    {
        $this->params = $this->params->merge($params);

        return $this;
    }

    /**
     * Retrieve request identifier.
     *
     * @return int|string|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set request identifier.
     *
     * @param int|string $name
     *
     * @return Request
     */
    public function setId($name): self
    {
        $this->id = (string) $name;

        return $this;
    }
}

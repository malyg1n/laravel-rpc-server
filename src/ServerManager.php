<?php

declare(strict_types = 1);

namespace Malyg1n\JsonRpcServer;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Malyg1n\JsonRpcServer\Facades\RPC;
use Malyg1n\JsonRpcServer\Http\Request;
use Malyg1n\JsonRpcServer\Http\Response;
use Malyg1n\JsonRpcServer\Exceptions\MethodNotFound;

class ServerManager
{
    /**
     * @const string
     */
    private const DEFAULT_DELIMITER = '@';

    /**
     * @var Collection
     */
    private Collection $map;

    /**
     * @var string
     */
    private string $delimiter;

    /**
     * @param array       $commands
     * @param string|null $delimiter
     */
    public function __construct(array $commands, ?string $delimiter = null)
    {
        $this->delimiter = $delimiter ?? static::DEFAULT_DELIMITER;

        $this->map = new Collection();
        foreach ($commands as $command) {
            $this->map->add($command);
        }
    }

    /**
     * @param string $content
     *
     * @return mixed
     */
    public function handle(string $content = '')
    {
        $parser = new Parser($content);
        $result = collect($parser->getRequest())
            ->map(
                fn ($request) => $request instanceof Request
                    ? $this->handleOperation($request)
                    : $this->makeResponse($request)
            );

        return $result->first();
    }

    /**
     * @param null         $result
     * @param Request|null $request
     *
     * @return Response
     */
    private function makeResponse($result = null, ?Request $request = null): Response
    {
        $request ??= new Request();

        return tap(
            new Response(),
            fn (Response $response) => $response->setId($request->getId())
                ->setVersion($request->getVersion())
                ->setResult($result)
        );
    }

    private function handleOperation(Request $request)
    {
        \request()->replace($request->getParams()->toArray());

        $operation = $this->findOperation($request);

        if ($operation === null) {
            return $this->makeResponse(new MethodNotFound(), $request);
        }

        $result = App::call($operation, RPC::bindResolve($request));

        return $this->makeResponse($result, $request);
    }

    /**
     * @param Request $request
     *
     * @return string|null
     */
    private function findOperation(Request $request): ?string
    {
        $class  = Str::beforeLast($request->getMethod(), $this->delimiter);
        $method = Str::afterLast($request->getMethod(), $this->delimiter);

        return $this->map
            ->filter(fn (string $operation) => $this->getOperationName($operation) === $class)
            ->filter(fn (string $operation) => $this->checkExistPublicMethod($operation, $method))
            ->map(fn (string $operation)    => Str::finish($operation, '@' . $method))
            ->first();
    }

    /**
     * @param string $operation
     * @param string $method
     *
     * @throws \ReflectionException
     *
     * @return bool
     */
    private function checkExistPublicMethod(string $operation, string $method): bool
    {
        return (new \ReflectionMethod($operation, $method))->isPublic();
    }

    /**
     * @param string $operation
     *
     * @throws \ReflectionException
     *
     * @return string
     */
    private function getOperationName(string $operation): string
    {
        return (new \ReflectionClass($operation))->getStaticPropertyValue('name');
    }
}

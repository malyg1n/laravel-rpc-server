<?php

declare(strict_types = 1);

namespace Malyg1n\JsonRpcServer;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Container\Container;
use Illuminate\Routing\RouteBinding;
use Malyg1n\JsonRpcServer\Http\Request;

class Kernel implements KernelInterface
{
    /**
     * @var Container
     */
    private Container $container;

    /**
     * @var array
     */
    private array $binders = [];

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string         $key
     * @param Closure|string $action
     */
    public function bind(string $key, $action): void
    {
        $this->binders[$key] = RouteBinding::forCallback($this->container, $action);
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function bindResolve(Request $request): array
    {
        $possibleBindings = Arr::dot($request->getParams());

        return collect($possibleBindings)
            ->map(fn ($value, string $key) => with($value, $this->binders[$key] ?? null))
            ->mapWithKeys(function ($value, string $key) {
                $nameForArgument = (string) Str::of($key)->replace('.', '_')->camel();

                return [$nameForArgument => $value];
            })
            ->toArray();
    }
}

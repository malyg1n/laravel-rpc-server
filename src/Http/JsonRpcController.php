<?php

declare(strict_types = 1);

namespace Malyg1n\JsonRpcServer\Http;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Malyg1n\JsonRpcServer\Kernel;
use Malyg1n\JsonRpcServer\ServerManager;

class JsonRpcController
{
    /**
     * @var Kernel|null
     */
    protected ?Kernel $guide;

    /**
     * @param Request     $request
     * @param string      $operation
     * @param string|null $delimiter
     *
     * @return JsonResponse
     */
    public function __invoke(Request $request, string $operation, ?string $delimiter = null): JsonResponse
    {
        $manager = new ServerManager([$operation], $delimiter);

        $response = $manager->handle((string) $request->getContent());

        return response()->json($response);
    }
}

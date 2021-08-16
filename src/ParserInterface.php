<?php

namespace Malyg1n\JsonRpcServer;

interface ParserInterface
{
    public function getRequest(): array;
}

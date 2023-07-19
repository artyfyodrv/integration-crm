<?php

namespace Sync\Factories;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sync\Handlers\AuthHandler;

class AuthHandlerFactory
{
    public function __invoke(ServerRequestInterface $request): RequestHandlerInterface
    {
        return new AuthHandler();
    }
}
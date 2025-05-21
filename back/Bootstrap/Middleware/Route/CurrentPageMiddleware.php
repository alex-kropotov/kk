<?php

declare(strict_types=1);

namespace App\Bootstrap\Middleware\Route;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use App\Domain\Service\URI\UriManager;

class CurrentPageMiddleware implements Middleware
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        // Create a new UriManager instance with the request URI
        $uriManager = new UriManager($request->getUri());

        // Register UriManager in the container
        $this->container->set(UriManager::class, $uriManager);

        return $handler->handle($request);
    }
}

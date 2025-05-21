<?php

namespace App\Feature\Admin\Auth\Api\LoginCheck;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionException;
use Tools\CommandBus\BaseController;
use Tools\Utils\NamedLog;

final class AdminAuthLoginCheckApiController extends BaseController
{
    /**
     * @throws ReflectionException
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface
    {
        $params = $request->getParsedBody();

        $command = $this->dtoFactory->create(AdminAuthLoginCheckApiCommand::class, $params);
        $result = $this->commandBus->dispatch($command);

        $response->getBody()->write(json_encode($result->getAsArray()));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}

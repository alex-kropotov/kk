<?php

namespace App\Feature\Admin\Api\CitySearch;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionException;
use Tools\CommandBus\BaseController;

final class AdminCitySearchApiController extends BaseController
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

        $command = $this->dtoFactory->create(AdminCitySearchApiCommand::class, $params);
        $result = $this->commandBus->dispatch($command);

        $response->getBody()->write(json_encode($result->getAsArray()));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}
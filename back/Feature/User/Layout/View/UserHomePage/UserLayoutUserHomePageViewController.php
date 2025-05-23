<?php

namespace App\Feature\User\Layout\View\UserHomePage;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tools\CommandBus\BaseController;

class UserLayoutUserHomePageViewController extends BaseController {
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface
    {
        $params = $request->getParsedBody();
        
        $command = $this->dtoFactory->create(UserLayoutUserHomePageViewCommand::class, $params);
        $result = $this->commandBus->dispatch($command);
        $response->getBody()->write($result->getHtml());
        return $response;
    }
}
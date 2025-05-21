<?php

namespace App\Feature\Admin\Layout\View\HomePage;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tools\CommandBus\BaseController;

class AdminLayoutHomePageViewController extends BaseController {
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface
    {
        $params = $request->getParsedBody();
        
        $command = $this->dtoFactory->create(AdminLayoutHomePageViewCommand::class, $params);
        $result = $this->commandBus->dispatch($command);
        $response->getBody()->write($result->getHtml());
        return $response;
    }
}
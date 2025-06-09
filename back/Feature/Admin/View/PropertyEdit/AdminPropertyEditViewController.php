<?php

namespace App\Feature\Admin\View\PropertyEdit;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tools\CommandBus\BaseController;

class AdminPropertyEditViewController extends BaseController {
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface
    {
        $params = $request->getParsedBody();
        $params['id'] = $args['id']; // Добавляем ID из URL

        $command = $this->dtoFactory->create(AdminPropertyEditViewCommand::class, $params);
        $result = $this->commandBus->dispatch($command);
        $response->getBody()->write($result->getHtml());
        return $response;
    }
}

<?php

namespace App\Feature\Admin\Auth\View\LoginForm;

use App\Domain\Service\SessionKeyEnum;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tools\CommandBus\BaseController;
use Tools\Utils\NamedLog;

class AdminAuthLoginFormViewController extends BaseController {

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface
    {
        $params = ['redirectPath' => $this->session->get( SessionKeyEnum::RedirectFromLogin, '/admin')];

        $command = $this->dtoFactory->create(AdminAuthLoginFormViewCommand::class, $params);
        $result = $this->commandBus->dispatch($command);
        $response->getBody()->write($result->getHtml());
        return $response;
    }
}

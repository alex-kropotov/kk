<?php

namespace App\Feature\Admin\Auth\Api\Logout;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionException;
use Tools\CommandBus\BaseController;

final class AdminAuthLogoutApiController extends BaseController
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
        $command = $this->dtoFactory->create(AdminAuthLogoutApiCommand::class, []);
        $this->commandBus->dispatch($command);
        return $response
            ->withHeader('Location', '/admin/adminLogin')
            ->withStatus(301);
//        return $response->withPermanentRedirect('/admin/adminLogin');
    }
}

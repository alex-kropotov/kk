<?php

namespace App\Feature\Admin\Api\Logout;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionException;
use Tools\CommandBus\BaseController;

final class AdminLogoutApiController extends BaseController
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
        $command = $this->dtoFactory->create(AdminLogoutApiCommand::class, []);
        $this->commandBus->dispatch($command);
        return $response
            ->withHeader('Location', '/admin/adminLogin')
            ->withStatus(301);
//        return $response->withPermanentRedirect('/admin/adminLogin');
    }
}

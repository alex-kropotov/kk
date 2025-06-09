<?php

namespace App\Feature\User\View\PropertyDetail;

use App\Render\Layout\User\UserRender;
use App\Render\Services\Renderers\ViewRendererNoBundle;
use Tools\CommandBus\AsCommandHandler;
use Tools\CommandBus\CommandHandlerInterface;
use Tools\CommandBus\CommandHandlerResult;
use Tools\CommandBus\CommandHandlerResultInterface;
use Tools\CommandBus\CommandInterface;

#[AsCommandHandler(UserPropertyDetailViewCommand::class)]
readonly class UserPropertyDetailViewHandler implements CommandHandlerInterface
{
    public function __construct(
        private ViewRendererNoBundle $renderer
    ) {}

    public function handle(CommandInterface $command): CommandHandlerResultInterface
    {
        $body = 'BODY';
        $result = new CommandHandlerResult();
        $html = $this->renderer->renderPageWithLayout(
            'vUserLayoutBase',
            [
                'HEADER' => UserRender::header(),
                'BODY' => '',
                'FOOTER' => UserRender::footer()
            ]
        );



        $result->setHtml($html);
        return $result;
    }
}

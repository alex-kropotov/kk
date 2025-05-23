<?php

namespace App\Feature\User\Layout\View\UserHomePage;

use Tools\CommandBus\AsCommandHandler;
use Tools\CommandBus\CommandHandlerInterface;
use Tools\CommandBus\CommandHandlerResult;
use Tools\CommandBus\CommandHandlerResultInterface;
use Tools\CommandBus\CommandInterface;
use Tools\Template\ViewRenderer;

#[AsCommandHandler(UserLayoutUserHomePageViewCommand::class)]
readonly class UserLayoutUserHomePageViewHandler implements CommandHandlerInterface
{
    public function __construct(
        private ViewRenderer $renderer
    ) {}

    public function handle(CommandInterface $command): CommandHandlerResultInterface
    {
        $body = 'BODY';
        $result = new CommandHandlerResult();
        $html = $this->renderer->renderPageWithLayout(
            'vAdminLayout',
            ['BODY' => $body]
        );



        $result->setHtml($html);
        return $result;
    }
}

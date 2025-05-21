<?php

namespace App\Feature\Admin\Layout\View\HomePage;

use Tools\CommandBus\AsCommandHandler;
use Tools\CommandBus\CommandHandlerInterface;
use Tools\CommandBus\CommandHandlerResult;
use Tools\CommandBus\CommandHandlerResultInterface;
use Tools\CommandBus\CommandInterface;
use Tools\Template\ViewRenderer;

#[AsCommandHandler(AdminLayoutHomePageViewCommand::class)]
readonly class AdminLayoutHomePageViewHandler implements CommandHandlerInterface
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

<?php

namespace App\Feature\Admin\Auth\View\LoginForm;

use App\Render\Admin\AdminRender;
use Tools\CommandBus\AsCommandHandler;
use Tools\CommandBus\CommandHandlerInterface;
use Tools\CommandBus\CommandHandlerResult;
use Tools\CommandBus\CommandHandlerResultInterface;
use Tools\CommandBus\CommandInterface;
use Tools\Template\ViewRenderer;

#[AsCommandHandler(AdminAuthLoginFormViewCommand::class)]
readonly class AdminAuthLoginFormViewHandler implements CommandHandlerInterface
{
    public function __construct(
        private ViewRenderer $renderer
    ) {}

    public function handle(CommandInterface $command): CommandHandlerResultInterface
    {
        $redirectFrom = $command->redirectPath ?? '/';
        $body = AdminRender::adminLogin($redirectFrom);
        $result = new CommandHandlerResult();
        $html = $this->renderer->renderPageWithLayout(
            'vAdminLayout',
            ['BODY' => $body]
        );



        $result->setHtml($html);
        return $result;
    }
}

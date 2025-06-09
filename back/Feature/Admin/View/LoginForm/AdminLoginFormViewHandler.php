<?php

namespace App\Feature\Admin\View\LoginForm;

use App\Render\Admin\Login\AdminLoginRender;
use App\Render\Services\Renderers\ViewRenderer;
use Tools\CommandBus\AsCommandHandler;
use Tools\CommandBus\CommandHandlerInterface;
use Tools\CommandBus\CommandHandlerResult;
use Tools\CommandBus\CommandHandlerResultInterface;
use Tools\CommandBus\CommandInterface;

#[AsCommandHandler(AdminLoginFormViewCommand::class)]
readonly class AdminLoginFormViewHandler implements CommandHandlerInterface
{
    public function __construct(
        private ViewRenderer $renderer
    ) {}

    public function handle(CommandInterface $command): CommandHandlerResultInterface
    {
        $redirectFrom = $command->redirectPath ?? '/';
        return (new CommandHandlerResult())->setHtml(
            $this->renderer->renderPageWithLayout(
                'vAdminLayout', 'adminLogin',
                ['BODY' => AdminLoginRender::render($redirectFrom)]
            )
        );
    }
}

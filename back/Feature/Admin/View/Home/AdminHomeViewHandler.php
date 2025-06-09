<?php

namespace App\Feature\Admin\View\Home;

use App\Render\Admin\Layout\AdminNavbarRender;
use App\Render\Services\Renderers\ViewRenderer;
use Tools\CommandBus\AsCommandHandler;
use Tools\CommandBus\CommandHandlerInterface;
use Tools\CommandBus\CommandHandlerResult;
use Tools\CommandBus\CommandHandlerResultInterface;
use Tools\CommandBus\CommandInterface;
use Tools\Template\Template8;
use Tools\Template\TplLoader;

#[AsCommandHandler(AdminHomeViewCommand::class)]
readonly class AdminHomeViewHandler implements CommandHandlerInterface
{
    public function __construct(
        private ViewRenderer $renderer
    ) {}

    public function handle(CommandInterface $command): CommandHandlerResultInterface
    {
        $vAdminBody = TplLoader::get('vAdminHome');
        $body = $vAdminBody->fetch();
        $result = new CommandHandlerResult();
        $html = $this->renderer->renderPageWithLayout(
            'vAdminLayout', 'homePage',
            [
                'BODY' => $body,
                'HEADER' => AdminNavbarRender::get('properties')
            ]
        );



        $result->setHtml($html);
        return $result;
    }
}

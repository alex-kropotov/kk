<?php

namespace App\Feature\Admin\View\PropertyEdit;

use App\Render\Admin\Layout\AdminNavbarRender;
use App\Render\Services\Renderers\ViewRenderer;
use Tools\CommandBus\AsCommandHandler;
use Tools\CommandBus\CommandHandlerInterface;
use Tools\CommandBus\CommandHandlerResult;
use Tools\CommandBus\CommandHandlerResultInterface;
use Tools\CommandBus\CommandInterface;
use Tools\Template\TplLoader;

#[AsCommandHandler(AdminPropertyEditViewCommand::class)]
readonly class AdminPropertyEditViewHandler implements CommandHandlerInterface
{
    public function __construct(
        private ViewRenderer $renderer
    ) {}

    public function handle(CommandInterface $command): CommandHandlerResultInterface
    {
        $vAdminBody = TplLoader::get('vAdminPropertyEdit');
        $body = $vAdminBody->fetch();
        $result = new CommandHandlerResult();
        $html = $this->renderer->renderPageWithLayout(
            'vAdminLayout', 'adminPropertyEdit',
            [
                'BODY' => $body,
                'HEADER' => AdminNavbarRender::get('property0')
            ]
        );



        $result->setHtml($html);
        return $result;
    }
}

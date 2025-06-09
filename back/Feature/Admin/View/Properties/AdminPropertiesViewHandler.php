<?php

namespace App\Feature\Admin\View\Properties;

use App\Render\Admin\Layout\AdminNavbarRender;
use App\Render\Services\Renderers\ViewRenderer;
use Tools\CommandBus\AsCommandHandler;
use Tools\CommandBus\CommandHandlerInterface;
use Tools\CommandBus\CommandHandlerResult;
use Tools\CommandBus\CommandHandlerResultInterface;
use Tools\CommandBus\CommandInterface;
use Tools\Persist\RepositoryFactory;
use Tools\Template\TplLoader;
use Tools\Utils\NamedLog;

#[AsCommandHandler(AdminPropertiesViewCommand::class)]
readonly class AdminPropertiesViewHandler implements CommandHandlerInterface
{
    public function __construct(
        private ViewRenderer $renderer,
        private RepositoryFactory $repositoryFactory
    ) {}

    public function handle(CommandInterface $command): CommandHandlerResultInterface
    {
        $vAdminBody = TplLoader::get('vAdminPropertiesRoot');
//        $repoPlace = $this->repositoryFactory->make('App\Domain\Repositories\PlaceRepository');
//        $repoProperty = $this->repositoryFactory->make('App\Domain\Repositories\PropertyRepository');
//        $array = $repoPlace->getCities();
//        $array2 = $repoProperty->with(['place', 'street'], true)->get();
//        $array2 = $repoProperty->with(['place'], true)->get();
//        $array2 = $repoProperty->get();
//        $repoStreet = $this->repositoryFactory->make('App\Domain\Repositories\StreetRepository');
        $repoOwner = $this->repositoryFactory->make('App\Domain\Repositories\OwnerRepository');
        $array = $repoOwner->with(['properties'])->get();
//        $array = $repoStreet->get();
//        foreach ($array as $street) {
//            $street->loadRelation('properties');
//        }
        NamedLog::write('owners', $array);
        $body = 'ok';
//        $body = $vAdminBody->fetch();
//        $body = implode("\n", array_map(fn($obj) => "<p>$obj->namePlaceRs</p>", $array));
//        $body .= '<p>'.$array2[0]->street->nameStreetRu.'</p>';
        $result = new CommandHandlerResult();
        $html = $this->renderer->renderPageWithLayout(
            'vAdminLayout', 'propertyList',
            [
                'BODY' => $body,
                'HEADER' => AdminNavbarRender::get('properties')
            ]
        );



        $result->setHtml($html);
        return $result;
    }
}

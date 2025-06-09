<?php

namespace App\Feature\Admin\Api\CitySearch;

use Tools\CommandBus\AsCommandHandler;
use Tools\CommandBus\CommandHandlerInterface;
use Tools\CommandBus\CommandHandlerResult;
use Tools\CommandBus\CommandHandlerResultInterface;
use Tools\CommandBus\CommandInterface;
use Tools\Persist\RepositoryFactory;

#[AsCommandHandler(AdminCitySearchApiCommand::class)]
readonly class AdminCitySearchApiHandler implements CommandHandlerInterface
{
    public function __construct(
        private RepositoryFactory $repositoryFactory
    ) {}

    public function handle(CommandInterface $command): CommandHandlerResultInterface
    {
        $repoPlace = $this->repositoryFactory->make('App\Domain\Repositories\PlaceRepository');
//        error_log($command);
        return (new CommandHandlerResult())
            ->addDataArray(
                $repoPlace->getSearchCities($command->searchTemplate)
            );
    }
}

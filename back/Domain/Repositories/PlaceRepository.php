<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\PlaceData;
use App\Domain\Enum\PlaceTypeEnum;
use PDO;
use Tools\Persist\BaseRepository;
use Tools\Utils\NamedLog;

class PlaceRepository extends BaseRepository
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'places', PlaceData::class);
    }

    public function getCities(): array
    {
        return $this->where('type_place', '=', PlaceTypeEnum::CITY->value)->get();
    }

    public function getSearchCities(string $searchTemplate): array
    {
        $ar = $this
            ->where('type_place', '=', PlaceTypeEnum::CITY->value)
            ->where('name_place_rs', 'like', $searchTemplate.'%')
            ->get();
        NamedLog::write('citySearch', $searchTemplate, $ar);
        return $ar;
    }
}

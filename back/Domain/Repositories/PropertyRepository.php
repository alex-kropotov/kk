<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use Tools\Persist\BaseRepository;
use App\Domain\Entities\PropertyData;
use App\Domain\Entities\PlaceData;
use App\Domain\Entities\StreetData;
use PDO;

class PropertyRepository extends BaseRepository
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'properties', PropertyData::class);
    }

    public function findWithRelations(int $id): ?PropertyData
    {
        $property = $this->where('idProperty', '=', $id)->first();

        if ($property) {
            $this->loadRelations($property, ['place', 'street']);
        }

        return $property;
    }

    public function findManyWithRelations(array $ids): array
    {
        $properties = $this->whereIn('idProperty', $ids)->get();

        if (!empty($properties)) {
            $this->loadRelationsForCollection($properties, ['place', 'street']);
        }

        return $properties;
    }

    protected function loadRelations(PropertyData $property, array $relations): void
    {
        foreach ($relations as $relation) {
            $property->loadRelation($relation);
        }
    }

    protected function loadRelationsForCollection(array $properties, array $relations): void
    {
        // Для эффективности загружаем все связи одним запросом для всей коллекции
        foreach ($relations as $relation) {
            $this->loadRelationEagerly($properties, $relation);
        }
    }

    public function findByPlace(int $placeId): array
    {
        return $this->where('idPlace', '=', $placeId)
            ->with(['place', 'street'], true)
            ->get();
    }

    public function findByStreet(int $streetId): array
    {
        return $this->where('idStreet', '=', $streetId)
            ->with(['place', 'street'], true)
            ->get();
    }

    public function findByDistrict(int $districtId): array
    {
        return $this->where('idDistrict', '=', $districtId)
            ->with(['place', 'street'], true)
            ->get();
    }
}

<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\Repositories\PlaceRepository;
use App\Domain\Repositories\StreetRepository;
use Tools\Persist\BaseClassData;

use App\Domain\Entities\PlaceData;
use App\Domain\Entities\StreetData;

class PropertyData extends BaseClassData
{
    protected static array $map = [
        'idProperty' => 'int',
        'idPlace' => 'int',
        'idPropertyType' => 'int',
        'idDistrict' => 'int',
        'idDistrictSub' => 'int',
        'idStreet' => 'int',
        'nameStreet' => 'string',
        'isNameStreetRecognized' => 'int',
        'houseNumber' => 'string',
        'idBuilding' => 'int',
        'houseNumberModifier' => 'string',
        'floorTypeEnum' => 'int',
        'floorNumber' => 'int',
        'floorsTotal' => 'int',
        'areaProperty' => 'float',
        'areaLand' => 'float',
        'propertyCondition' => 'int',
        'heatingTypeEnum' => 'int',
        'roomStructureEnum' => 'int',
        'roomCount' => 'int',
        'bedroomCount' => 'int',
        'bathroomCount' => 'int',
        'furnishingStatusEnum' => 'int',
    ];

    protected static array $nullable = [
        'idProperty' => true,
        'idPlace' => true,
        'idPropertyType' => true,
        'idDistrict' => true,
        'idDistrictSub' => true,
        'idStreet' => true,
        'nameStreet' => true,
        'isNameStreetRecognized' => true,
        'houseNumber' => true,
        'idBuilding' => true,
        'houseNumberModifier' => true,
        'floorTypeEnum' => true,
        'floorNumber' => true,
        'floorsTotal' => true,
        'areaProperty' => true,
        'areaLand' => true,
        'propertyCondition' => true,
        'heatingTypeEnum' => true,
        'roomStructureEnum' => true,
        'roomCount' => true,
        'bedroomCount' => true,
        'bathroomCount' => true,
        'furnishingStatusEnum' => true,
    ];

    protected static array $defaults = [
        'isNameStreetRecognized' => 0,
    ];

    public ?int $idProperty;
    public ?int $idPlace;
    public ?int $idPropertyType;
    public ?int $idDistrict;
    public ?int $idDistrictSub;
    public ?int $idStreet;
    public ?string $nameStreet;
    public ?int $isNameStreetRecognized;
    public ?string $houseNumber;
    public ?int $idBuilding;
    public ?string $houseNumberModifier;
    public ?int $floorTypeEnum;
    public ?int $floorNumber;
    public ?int $floorsTotal;
    public ?float $areaProperty;
    public ?float $areaLand;
    public ?int $propertyCondition;
    public ?int $heatingTypeEnum;
    public ?int $roomStructureEnum;
    public ?int $roomCount;
    public ?int $bedroomCount;
    public ?int $bathroomCount;
    public ?int $furnishingStatusEnum;

    public ?PlaceData $place = null;
    public ?StreetData $street = null;

    protected static array $relations = [
        'place' => [
            'type' => 'one',
            'relatedRepo' => PlaceRepository::class,
            'localKey' => 'idPlace',
            'foreignKey' => 'idPlace',
            'name' => 'place'
        ],
        'street' => [
            'type' => 'one',
            'relatedRepo' => StreetRepository::class,
            'localKey' => 'idStreet',
            'foreignKey' => 'idStreet',
            'name' => 'street'
        ]
    ];

    public function __construct(
        ?int $idProperty,
        ?int $idPlace,
        ?int $idPropertyType,
        ?int $idDistrict,
        ?int $idDistrictSub,
        ?int $idStreet,
        ?string $nameStreet,
        ?int $isNameStreetRecognized,
        ?string $houseNumber,
        ?int $idBuilding,
        ?string $houseNumberModifier,
        ?int $floorTypeEnum,
        ?int $floorNumber,
        ?int $floorsTotal,
        ?float $areaProperty,
        ?float $areaLand,
        ?int $propertyCondition,
        ?int $heatingTypeEnum,
        ?int $roomStructureEnum,
        ?int $roomCount,
        ?int $bedroomCount,
        ?int $bathroomCount,
        ?int $furnishingStatusEnum
    ) {
        $this->idProperty = $idProperty;
        $this->idPlace = $idPlace;
        $this->idPropertyType = $idPropertyType;
        $this->idDistrict = $idDistrict;
        $this->idDistrictSub = $idDistrictSub;
        $this->idStreet = $idStreet;
        $this->nameStreet = $nameStreet;
        $this->isNameStreetRecognized = $isNameStreetRecognized;
        $this->houseNumber = $houseNumber;
        $this->idBuilding = $idBuilding;
        $this->houseNumberModifier = $houseNumberModifier;
        $this->floorTypeEnum = $floorTypeEnum;
        $this->floorNumber = $floorNumber;
        $this->floorsTotal = $floorsTotal;
        $this->areaProperty = $areaProperty;
        $this->areaLand = $areaLand;
        $this->propertyCondition = $propertyCondition;
        $this->heatingTypeEnum = $heatingTypeEnum;
        $this->roomStructureEnum = $roomStructureEnum;
        $this->roomCount = $roomCount;
        $this->bedroomCount = $bedroomCount;
        $this->bathroomCount = $bathroomCount;
        $this->furnishingStatusEnum = $furnishingStatusEnum;
    }

    protected static function table(): string
    {
        return 'properties';
    }

    protected static function primaryKey(): string
    {
        return 'idProperty';
    }

    protected function primaryKeyValue(): ?int
    {
        return $this->idProperty;
    }

    public static function relations(): array
    {
        return self::$relations;
    }

    public static function createObj(
        ?int $idProperty,
        ?int $idPlace,
        ?int $idPropertyType,
        ?int $idDistrict,
        ?int $idDistrictSub,
        ?int $idStreet,
        ?string $nameStreet,
        ?int $isNameStreetRecognized,
        ?string $houseNumber,
        ?int $idBuilding,
        ?string $houseNumberModifier,
        ?int $floorTypeEnum,
        ?int $floorNumber,
        ?int $floorsTotal,
        ?float $areaProperty,
        ?float $areaLand,
        ?int $propertyCondition,
        ?int $heatingTypeEnum,
        ?int $roomStructureEnum,
        ?int $roomCount,
        ?int $bedroomCount,
        ?int $bathroomCount,
        ?int $furnishingStatusEnum
    ): self {
        return new self(
            $idProperty,
            $idPlace,
            $idPropertyType,
            $idDistrict,
            $idDistrictSub,
            $idStreet,
            $nameStreet,
            $isNameStreetRecognized,
            $houseNumber,
            $idBuilding,
            $houseNumberModifier,
            $floorTypeEnum,
            $floorNumber,
            $floorsTotal,
            $areaProperty,
            $areaLand,
            $propertyCondition,
            $heatingTypeEnum,
            $roomStructureEnum,
            $roomCount,
            $bedroomCount,
            $bathroomCount,
            $furnishingStatusEnum
        );
    }
}

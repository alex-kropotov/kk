<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\Repositories\PropertyRepository;
use Tools\Persist\BaseClassData;

use App\Domain\Entities\PlaceData;

class StreetData extends BaseClassData
{
    protected static array $map = [
        'idStreet' => 'int',
        'idPlace' => 'int',
        'idDistrict' => 'int',
        'nameStreetRs' => 'string',
        'nameStreetRu' => 'string',
    ];

    protected static array $nullable = [
        'idStreet' => false,
        'idPlace' => false,
        'idDistrict' => false,
        'nameStreetRs' => true,
        'nameStreetRu' => true,
    ];

    protected static array $defaults = [
        'idDistrict' => 0,
    ];

    public int $idStreet;
    public int $idPlace;
    public int $idDistrict;
    public ?string $nameStreetRs;
    public ?string $nameStreetRu;
    public ?PlaceData $place = null;
    public array $properties = [];

    protected static array $relations = [
        'properties' => [
            'type' => 'many',
            'relatedRepo' => PropertyRepository::class,
            'localKey' => 'idStreet',
            'foreignKey' => 'idStreet',
            'name' => 'properties'
        ]
    ];

    public static function relations(): array
    {
        return self::$relations;
    }

    public function __construct(
        int $idStreet = 0,
        int $idPlace = 0,
        int $idDistrict = 0,
        ?string $nameStreetRs = null,
        ?string $nameStreetRu = null
    ) {
        $this->idStreet = $idStreet;
        $this->idPlace = $idPlace;
        $this->idDistrict = $idDistrict;
        $this->nameStreetRs = $nameStreetRs;
        $this->nameStreetRu = $nameStreetRu;
    }

    protected static function table(): string
    {
        return 'streets';
    }

    protected static function primaryKey(): string
    {
        return 'idStreet';
    }

    protected function primaryKeyValue(): ?int
    {
        return $this->idStreet;
    }

    public static function createObj(
        int $idStreet = 0,
        int $idPlace = 0,
        int $idDistrict = 0,
        ?string $nameStreetRs = null,
        ?string $nameStreetRu = null
    ): self {
        return new self(
            $idStreet,
            $idPlace,
            $idDistrict,
            $nameStreetRs,
            $nameStreetRu
        );
    }




}

<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use Tools\Persist\BaseClassData;

class PlaceData extends BaseClassData
{
    protected static array $map = [
        'idPlace' => 'int',
        'typePlace' => 'int',
        'idCountry' => 'int',
        'idRegion' => 'int',
        'idCounty' => 'int',
        'idMunicipality' => 'int',
        'idCity' => 'int',
        'idDistrict' => 'int',
        'idSubDistrict' => 'int',
        'idParentDistrict' => 'int',
        'idSettlement' => 'int',
        'idVillage' => 'int',
        'namePlaceRs' => 'string',
        'namePlaceRu' => 'string',
        'dtCreated' => 'datetime',
        'dtChanged' => 'datetime',
    ];

    protected static array $nullable = [
        'idPlace' => true,
        'typePlace' => false,
        'idCountry' => false,
        'idRegion' => false,
        'idCounty' => false,
        'idMunicipality' => false,
        'idCity' => false,
        'idDistrict' => false,
        'idSubDistrict' => false,
        'idParentDistrict' => false,
        'idSettlement' => false,
        'idVillage' => false,
        'namePlaceRs' => false,
        'namePlaceRu' => false,
        'dtCreated' => true,
        'dtChanged' => true,
    ];

    protected static array $defaults = [
        'typePlace' => 0,
        'idCountry' => 0,
        'idRegion' => 0,
        'idCounty' => 0,
        'idMunicipality' => 0,
        'idCity' => 0,
        'idDistrict' => 0,
        'idSubDistrict' => 0,
        'idParentDistrict' => 0,
        'idSettlement' => 0,
        'idVillage' => 0,
        'namePlaceRs' => '',
        'namePlaceRu' => '',
        'dtCreated' => null,
        'dtChanged' => null,
    ];

    public ?int $idPlace;
    public int $typePlace;
    public int $idCountry;
    public int $idRegion;
    public int $idCounty;
    public int $idMunicipality;
    public int $idCity;
    public int $idDistrict;
    public int $idSubDistrict;
    public int $idParentDistrict;
    public int $idSettlement;
    public int $idVillage;
    public string $namePlaceRs;
    public string $namePlaceRu;
    public ?int $dtCreated;
    public ?int $dtChanged;

    public function __construct(
        ?int $idPlace,
        int $typePlace = 0,
        int $idCountry = 0,
        int $idRegion = 0,
        int $idCounty = 0,
        int $idMunicipality = 0,
        int $idCity = 0,
        int $idDistrict = 0,
        int $idSubDistrict = 0,
        int $idParentDistrict = 0,
        int $idSettlement = 0,
        int $idVillage = 0,
        string $namePlaceRs = '',
        string $namePlaceRu = '',
        ?int $dtCreated = null,
        ?int $dtChanged = null
    ) {
        $this->idPlace = $idPlace;
        $this->typePlace = $typePlace;
        $this->idCountry = $idCountry;
        $this->idRegion = $idRegion;
        $this->idCounty = $idCounty;
        $this->idMunicipality = $idMunicipality;
        $this->idCity = $idCity;
        $this->idDistrict = $idDistrict;
        $this->idSubDistrict = $idSubDistrict;
        $this->idParentDistrict = $idParentDistrict;
        $this->idSettlement = $idSettlement;
        $this->idVillage = $idVillage;
        $this->namePlaceRs = $namePlaceRs;
        $this->namePlaceRu = $namePlaceRu;
        $this->dtCreated = $dtCreated;
        $this->dtChanged = $dtChanged;
    }

    protected static function table(): string
    {
        return 'places';
    }

    protected static function primaryKey(): string
    {
        return 'idPlace';
    }

    protected function primaryKeyValue(): ?int
    {
        return $this->idPlace;
    }

    public static function createObj(
        ?int $idPlace,
        int $typePlace = 0,
        int $idCountry = 0,
        int $idRegion = 0,
        int $idCounty = 0,
        int $idMunicipality = 0,
        int $idCity = 0,
        int $idDistrict = 0,
        int $idSubDistrict = 0,
        int $idParentDistrict = 0,
        int $idSettlement = 0,
        int $idVillage = 0,
        string $namePlaceRs = '',
        string $namePlaceRu = '',
        ?int $dtCreated = null,
        ?int $dtChanged = null
    ): self {
        return new self(
            $idPlace,
            $typePlace,
            $idCountry,
            $idRegion,
            $idCounty,
            $idMunicipality,
            $idCity,
            $idDistrict,
            $idSubDistrict,
            $idParentDistrict,
            $idSettlement,
            $idVillage,
            $namePlaceRs,
            $namePlaceRu,
            $dtCreated,
            $dtChanged
        );
    }
}

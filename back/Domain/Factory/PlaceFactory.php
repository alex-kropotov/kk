<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entities\PlaceData;
use App\Domain\Enum\PlaceTypeEnum;

class PlaceFactory
{
    public static function addCountry(string $namePlaceRs, string $namePlaceRu = ''): PlaceData
    {
        return new PlaceData(
            idPlace: null,
            typePlace: PlaceTypeEnum::COUNTRY->value,
            idCountry: 0,
            idRegion: 0,
            idCounty: 0,
            idMunicipality: 0,
            idCity: 0,
            idDistrict: 0,
            idSubDistrict: 0,
            idParentDistrict: 0,
            idSettlement: 0,
            idVillage: 0,
            namePlaceRs: $namePlaceRs,
            namePlaceRu: $namePlaceRu,
            dtCreated: time(),
            dtChanged: time()
        );
    }

    public static function addRegion(int $idCountry, string $namePlaceRs, string $namePlaceRu = ''): PlaceData
    {
        return new PlaceData(
            null,
            PlaceTypeEnum::REGION->value,
            $idCountry,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            $namePlaceRs,
            $namePlaceRu,
            time(),
            time()
        );
    }

    public static function addCounty(int $idRegion, string $namePlaceRs, string $namePlaceRu = ''): PlaceData
    {
        return new PlaceData(
            null,
            PlaceTypeEnum::COUNTY->value,
            0,
            $idRegion,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            $namePlaceRs,
            $namePlaceRu,
            time(),
            time()
        );
    }

    public static function addMunicipality(int $idCounty, string $namePlaceRs, string $namePlaceRu = ''): PlaceData
    {
        return new PlaceData(
            null,
            PlaceTypeEnum::MUNICIPALITY->value,
            0,
            0,
            $idCounty,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            $namePlaceRs,
            $namePlaceRu,
            time(),
            time()
        );
    }

    public static function addCity(int $idMunicipality, string $namePlaceRs, string $namePlaceRu = ''): PlaceData
    {
        return new PlaceData(
            null,
            PlaceTypeEnum::CITY->value,
            0,
            0,
            0,
            $idMunicipality,
            0,
            0,
            0,
            0,
            0,
            0,
            $namePlaceRs,
            $namePlaceRu,
            time(),
            time()
        );
    }

    public static function addDistrict(int $idCity, string $namePlaceRs, string $namePlaceRu = ''): PlaceData
    {
        return new PlaceData(
            null,
            PlaceTypeEnum::DISTRICT->value,
            0,
            0,
            0,
            0,
            $idCity,
            0,
            0,
            0,
            0,
            0,
            $namePlaceRs,
            $namePlaceRu,
            time(),
            time()
        );
    }

    public static function addSubdistrict(int $idDistrict, string $namePlaceRs, string $namePlaceRu = ''): PlaceData
    {
        return new PlaceData(
            null,
            PlaceTypeEnum::SUBDISTRICT->value,
            0,
            0,
            0,
            0,
            0,
            $idDistrict,
            0,
            0,
            0,
            0,
            $namePlaceRs,
            $namePlaceRu,
            time(),
            time()
        );
    }

    public static function addSettlement(int $idMunicipality, string $namePlaceRs, string $namePlaceRu = ''): PlaceData
    {
        return new PlaceData(
            null,
            PlaceTypeEnum::SETTLEMENT->value,
            0,
            0,
            0,
            $idMunicipality,
            0,
            0,
            0,
            0,
            0,
            0,
            $namePlaceRs,
            $namePlaceRu,
            time(),
            time()
        );
    }

    public static function addVillage(int $idSettlement, string $namePlaceRs, string $namePlaceRu = ''): PlaceData
    {
        return new PlaceData(
            null,
            PlaceTypeEnum::VILLAGE->value,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            $idSettlement,
            0,
            $namePlaceRs,
            $namePlaceRu,
            time(),
            time()
        );
    }

    public static function addUnknown(string $namePlaceRs, string $namePlaceRu = ''): PlaceData
    {
        return new PlaceData(
            null,
            PlaceTypeEnum::UNKNOWN->value,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            $namePlaceRs,
            $namePlaceRu,
            time(),
            time()
        );
    }
}

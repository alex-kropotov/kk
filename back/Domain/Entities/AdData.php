<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use Tools\Persist\BaseClassData;

use App\Domain\Entities\PropertieData;

class AdData extends BaseClassData
{
    protected static array $map = [
        'idAds' => 'int',
        'idAdsType' => 'int',
        'idProperty' => 'int',
        'adsText' => 'string',
        'adsDescription' => 'string',
        'idPhotoset' => 'int',
        'price' => 'float',
        'agencyFee' => 'int',
        'utilitiesPaymentType' => 'int',
        'atStartShow' => 'datetime',
        'atStopShow' => 'datetime',
        'isActive' => 'int',
    ];

    protected static array $nullable = [
        'idAds' => true,
        'idAdsType' => false,
        'idProperty' => false,
        'adsText' => true,
        'adsDescription' => true,
        'idPhotoset' => true,
        'price' => true,
        'agencyFee' => true,
        'utilitiesPaymentType' => true,
        'atStartShow' => true,
        'atStopShow' => false,
        'isActive' => true,
    ];

    public ?int $idAds;
    public int $idAdsType;
    public int $idProperty;
    public ?string $adsText;
    public ?string $adsDescription;
    public ?int $idPhotoset;
    public ?float $price;
    public ?int $agencyFee;
    public ?int $utilitiesPaymentType;
    public ?int $atStartShow;
    public int $atStopShow;
    public ?int $isActive;
    public ?PropertyData $property = null;

    public function __construct(
        ?int $idAds,
        int $idAdsType,
        int $idProperty,
        ?string $adsText,
        ?string $adsDescription,
        ?int $idPhotoset,
        ?float $price,
        ?int $agencyFee,
        ?int $utilitiesPaymentType,
        ?int $atStartShow,
        int $atStopShow,
        ?int $isActive
    ) {
        $this->idAds = $idAds;
        $this->idAdsType = $idAdsType;
        $this->idProperty = $idProperty;
        $this->adsText = $adsText;
        $this->adsDescription = $adsDescription;
        $this->idPhotoset = $idPhotoset;
        $this->price = $price;
        $this->agencyFee = $agencyFee;
        $this->utilitiesPaymentType = $utilitiesPaymentType;
        $this->atStartShow = $atStartShow;
        $this->atStopShow = $atStopShow;
        $this->isActive = $isActive;
    }

    protected static function table(): string
    {
        return 'ads';
    }

    protected static function primaryKey(): string
    {
        return 'idAds';
    }

    protected function primaryKeyValue(): ?int
    {
        return $this->idAds;
    }

    public static function createObj(
        ?int $idAds,
        int $idAdsType,
        int $idProperty,
        ?string $adsText,
        ?string $adsDescription,
        ?int $idPhotoset,
        ?float $price,
        ?int $agencyFee,
        ?int $utilitiesPaymentType,
        ?int $atStartShow,
        int $atStopShow,
        ?int $isActive
    ): self {
        return new self(
            $idAds,
            $idAdsType,
            $idProperty,
            $adsText,
            $adsDescription,
            $idPhotoset,
            $price,
            $agencyFee,
            $utilitiesPaymentType,
            $atStartShow,
            $atStopShow,
            $isActive
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use Tools\Persist\BaseClassData;

use App\Domain\Entities\PropertieData;

class PhotosetData extends BaseClassData
{
    protected static array $map = [
        'idPhotoset' => 'int',
        'namePhotoset' => 'string',
        'idProperty' => 'int',
        'atCreated' => 'datetime',
        'atChanged' => 'datetime',
        'isActive' => 'int',
    ];

    protected static array $nullable = [
        'idPhotoset' => true,
        'namePhotoset' => true,
        'idProperty' => false,
        'atCreated' => false,
        'atChanged' => true,
        'isActive' => false,
    ];

    protected static array $defaults = [
        'isActive' => 1,
    ];

    public ?int $idPhotoset;
    public ?string $namePhotoset;
    public int $idProperty;
    public int $atCreated;
    public ?int $atChanged;
    public int $isActive;
    public ?PropertyData $property = null;

    public function __construct(
        ?int $idPhotoset,
        ?string $namePhotoset,
        int $idProperty,
        int $atCreated,
        ?int $atChanged,
        int $isActive = 1
    ) {
        $this->idPhotoset = $idPhotoset;
        $this->namePhotoset = $namePhotoset;
        $this->idProperty = $idProperty;
        $this->atCreated = $atCreated;
        $this->atChanged = $atChanged;
        $this->isActive = $isActive;
    }

    protected static function table(): string
    {
        return 'photoset';
    }

    protected static function primaryKey(): string
    {
        return 'idPhotoset';
    }

    protected function primaryKeyValue(): ?int
    {
        return $this->idPhotoset;
    }

    public static function createObj(
        ?int $idPhotoset,
        ?string $namePhotoset,
        int $idProperty,
        int $atCreated,
        ?int $atChanged,
        int $isActive = 1
    ): self {
        return new self(
            $idPhotoset,
            $namePhotoset,
            $idProperty,
            $atCreated,
            $atChanged,
            $isActive
        );
    }
}

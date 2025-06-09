<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use Tools\Persist\BaseClassData;

use App\Domain\Entities\OwnerData;
use App\Domain\Entities\PropertyData;

class PropertyOwnerData extends BaseClassData
{
    protected static array $map = [
        'idPropertyOwner' => 'int',
        'idProperty' => 'int',
        'idOwner' => 'int',
        'atStarted' => 'datetime',
        'atCanceled' => 'datetime',
        'isActive' => 'int',
    ];

    protected static array $nullable = [
        'idPropertyOwner' => true,
        'idProperty' => false,
        'idOwner' => false,
        'atStarted' => false,
        'atCanceled' => true,
        'isActive' => true,
    ];

    public ?int $idPropertyOwner;
    public int $idProperty;
    public int $idOwner;
    public int $atStarted;
    public ?int $atCanceled;
    public ?int $isActive;
    public ?OwnerData $owner = null;
    public ?PropertyData $property = null;

    public function __construct(
        ?int $idPropertyOwner,
        int $idProperty,
        int $idOwner,
        int $atStarted,
        ?int $atCanceled,
        ?int $isActive
    ) {
        $this->idPropertyOwner = $idPropertyOwner;
        $this->idProperty = $idProperty;
        $this->idOwner = $idOwner;
        $this->atStarted = $atStarted;
        $this->atCanceled = $atCanceled;
        $this->isActive = $isActive;
    }

    protected static function table(): string
    {
        return 'property_owners';
    }

    protected static function primaryKey(): string
    {
        return 'idPropertyOwner';
    }

    protected function primaryKeyValue(): ?int
    {
        return $this->idPropertyOwner;
    }

    public static function createObj(
        ?int $idPropertyOwner,
        int $idProperty,
        int $idOwner,
        int $atStarted,
        ?int $atCanceled,
        ?int $isActive
    ): self {
        return new self(
            $idPropertyOwner,
            $idProperty,
            $idOwner,
            $atStarted,
            $atCanceled,
            $isActive
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use Tools\Persist\BaseClassData;

use App\Domain\Entities\StreetData;

class BuildingData extends BaseClassData
{
    protected static array $map = [
        'idBuilding' => 'int',
        'idStreet' => 'int',
        'buildingNumber' => 'int',
        'buildingLetter' => 'string',
        'buildingStage' => 'int',
        'buildingDescription' => 'string',
    ];

    protected static array $nullable = [
        'idBuilding' => true,
        'idStreet' => false,
        'buildingNumber' => false,
        'buildingLetter' => true,
        'buildingStage' => true,
        'buildingDescription' => true,
    ];

    public ?int $idBuilding;
    public int $idStreet;
    public int $buildingNumber;
    public ?string $buildingLetter;
    public ?int $buildingStage;
    public ?string $buildingDescription;
    public ?StreetData $street = null;

    public function __construct(
        ?int $idBuilding,
        int $idStreet,
        int $buildingNumber,
        ?string $buildingLetter,
        ?int $buildingStage,
        ?string $buildingDescription
    ) {
        $this->idBuilding = $idBuilding;
        $this->idStreet = $idStreet;
        $this->buildingNumber = $buildingNumber;
        $this->buildingLetter = $buildingLetter;
        $this->buildingStage = $buildingStage;
        $this->buildingDescription = $buildingDescription;
    }

    protected static function table(): string
    {
        return 'buildings';
    }

    protected static function primaryKey(): string
    {
        return 'idBuilding';
    }

    protected function primaryKeyValue(): ?int
    {
        return $this->idBuilding;
    }

    public static function createObj(
        ?int $idBuilding,
        int $idStreet,
        int $buildingNumber,
        ?string $buildingLetter,
        ?int $buildingStage,
        ?string $buildingDescription
    ): self {
        return new self(
            $idBuilding,
            $idStreet,
            $buildingNumber,
            $buildingLetter,
            $buildingStage,
            $buildingDescription
        );
    }
}

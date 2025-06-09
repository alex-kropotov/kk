<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\Repositories\PropertyOwnerRepository;
use App\Domain\Repositories\PropertyRepository;
use Tools\Persist\BaseClassData;

class OwnerData extends BaseClassData
{
    protected static array $map = [
        'idOwner' => 'int',
        'firstnameOwner' => 'string',
        'lastnameOwner' => 'string',
        'phoneOwner' => 'string',
        'emailOwner' => 'string',
        'addressOwner' => 'string',
    ];

    protected static array $nullable = [
        'idOwner' => true,
        'firstnameOwner' => true,
        'lastnameOwner' => false,
        'phoneOwner' => true,
        'emailOwner' => true,
        'addressOwner' => true,
    ];

    public ?int $idOwner;
    public ?string $firstnameOwner;
    public string $lastnameOwner;
    public ?string $phoneOwner;
    public ?string $emailOwner;
    public ?string $addressOwner;

    public array $properties = [];

    protected static array $relations = [
        'properties' => [
            'type' => 'many-to-many',
            'relatedRepo' => PropertyRepository::class,
            'localKey' => 'idOwner',
            'pivotRepo' => PropertyOwnerRepository::class,
            'pivotForeignKey' => 'idProperty',
            'pivotLocalKey' => 'idOwner',
            'relatedKey' => 'idProperty',
            'name' => 'properties'
        ]
    ];

    public static function relations(): array
    {
        return self::$relations;
    }

    public function __construct(
        ?int $idOwner,
        ?string $firstnameOwner,
        string $lastnameOwner,
        ?string $phoneOwner,
        ?string $emailOwner,
        ?string $addressOwner
    ) {
        $this->idOwner = $idOwner;
        $this->firstnameOwner = $firstnameOwner;
        $this->lastnameOwner = $lastnameOwner;
        $this->phoneOwner = $phoneOwner;
        $this->emailOwner = $emailOwner;
        $this->addressOwner = $addressOwner;
    }

    protected static function table(): string
    {
        return 'owners';
    }

    protected static function primaryKey(): string
    {
        return 'idOwner';
    }

    protected function primaryKeyValue(): ?int
    {
        return $this->idOwner;
    }

    public static function createObj(
        ?int $idOwner,
        ?string $firstnameOwner,
        string $lastnameOwner,
        ?string $phoneOwner,
        ?string $emailOwner,
        ?string $addressOwner
    ): self {
        return new self(
            $idOwner,
            $firstnameOwner,
            $lastnameOwner,
            $phoneOwner,
            $emailOwner,
            $addressOwner
        );
    }
}

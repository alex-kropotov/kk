<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\PropertyOwnerData;
use PDO;
use Tools\Persist\BaseRepository;

class PropertyOwnerRepository extends BaseRepository
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'property_owners', PropertyOwnerData::class);
    }
}

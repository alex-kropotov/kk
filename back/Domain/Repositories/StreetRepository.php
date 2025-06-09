<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\StreetData;
use PDO;
use Tools\Persist\BaseRepository;

class StreetRepository extends BaseRepository
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'streets', StreetData::class);
    }
}

<?php

namespace App\Domain\Service;

use Tools\Services\SessionKeyInterface;

enum SessionKeyEnum: string implements SessionKeyInterface
{
    case IdUser = 'idUser';
    case RedirectFromLogin = 'redirectFromLogin';

    public function value(): string
    {
        return $this->value;
    }
}

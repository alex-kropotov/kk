<?php

namespace App\Domain\Service\Security;

class AdminLoginChecker
{
    public static function checkUser(string $name, string $pass): int
    {
        $users = [
            ['id' => 1, 'name' => 'sas', 'pass' => 's'],
        ];
        foreach ($users as $user) {
            if (($user['name'] === $name) && ($user['pass'] === $pass)) {
                return $user['id'];
            }
        }

        return -1;
    }
}

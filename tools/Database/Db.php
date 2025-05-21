<?php

declare(strict_types=1);

namespace Tools\Database;

use PDO;
use PDOException;

class Db
{
    protected PDO $PdoConn;

    public function __construct(string $host, string $dbName, string $dbUser, string $dbPwd, string $dbCharSet)
    {
        $dsn = "mysql:host={$host};dbname={$dbName};charset={$dbCharSet}";
        $opt = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
        ];

        try {
            $this->PdoConn = new PDO($dsn, $dbUser, $dbPwd, $opt);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getPdoCon(): PDO
    {
        return $this->PdoConn;
    }
}

<?php
declare(strict_types=1);

namespace Tools\Database;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Connection;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

class EloquentManager
{
    private Capsule $capsule;

    public function __construct()
    {
        $this->capsule = new Capsule;
    }

    public function initialize(array $config): void
    {
        $this->capsule->addConnection([
            'driver'    => $config['driver'] ?? 'mysql',
            'host'      => $config['host'] ?? 'localhost',
            'port'      => $config['port'] ?: 3312, // <--- добавляем порт
            'database'  => $config['database'],
            'username'  => $config['username'],
            'password'  => $config['password'],
            'charset'   => $config['charset'] ?? 'utf8mb4',
            'collation' => $config['collation'] ?? 'utf8mb4_unicode_ci',
            'prefix'    => $config['prefix'] ?? '',
        ]);

        // Set the event dispatcher used by Eloquent models
        $this->capsule->setEventDispatcher(new Dispatcher(new Container));

        // Make this Capsule instance available globally
        $this->capsule->setAsGlobal();

        // Setup the Eloquent ORM
        $this->capsule->bootEloquent();
    }

    public function getCapsule(): Capsule
    {
        return $this->capsule;
    }

    public function getConnection(): Connection
    {
        return $this->capsule->getConnection();
    }
}

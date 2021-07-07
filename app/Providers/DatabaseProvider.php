<?php

namespace App\Providers;

use Exception;
use Lightpack\Container\Container;
use Lightpack\Database\Adapters\Mysql;
use Lightpack\Database\Adapters\Sqlite;

class DatabaseProvider implements ProviderInterface
{
    public function register(Container $container)
    {
        $container->register('db', function ($container) {
            $config = $container->get('config');

            if ('sqlite' === $config->get('db.driver')) {
                return $this->sqlite($config);
            }

            if ('mysql' === $config->get('db.driver')) {
                return $this->mysql($config);
            }

            $this->throwException($config);
        });
    }

    protected function sqlite($config)
    {
        return new Sqlite([
            'database' => $config->get('db.sqlite.database')
        ]);
    }

    protected function mysql($config)
    {
        return new Mysql([
            'host'      => $config->get('db.mysql.host'),
            'port'      => $config->get('db.mysql.port'),
            'username'  => $config->get('db.mysql.username'),
            'password'  => $config->get('db.mysql.password'),
            'database'  => $config->get('db.mysql.database'),
            'options'   => $config->get('db.mysql.options'),
        ]);
    }

    protected function throwException($config)
    {
        throw new Exception(
            'Unsupported database driver type: ',
            $config->get('db.driver')
        );
    }
}

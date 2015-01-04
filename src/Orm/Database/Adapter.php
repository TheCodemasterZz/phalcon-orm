<?php

namespace LukeZbihlyj\PhalconOrm\Orm\Database;

use Phalcon\DI\Injectable;
use Phalcon\DI\InjectionAwareInterface;
use Phalcon\Db\Adapter\Pdo as DatabaseAdapter;

/**
 * @package LukeZbihlyj\PhalconOrm\Orm\Database\Adapter
 */
class Adapter extends Injectable implements InjectionAwareInterface
{
    /**
     * @return Phalcon\Db\Adapter
     */
    public function resolve()
    {
        /** @var Phalcon\DI $di */
        $di = $this->getDI();

        /** @var Phalcon\Config $config */
        $config = $di->get('config')->get('phalcon-orm');

        /** @var string|null $driver */
        $driver = $config->get('connection')->get('driver');

        switch ($driver) {
            case 'mysql':
                $connection = $config->get('connection');

                return new DatabaseAdapter\Mysql([
                    'host' => $connection->get('host'),
                    'port' => $connection->get('port'),
                    'username' => $connection->get('user'),
                    'password' => $connection->get('password'),
                    'dbname' => $connection->get('database')
                ]);
                break;

            default:
                throw new Exception('No connection details were specified in the configuration.');
                break;
        }
    }
}

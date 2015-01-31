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
        $config = $di->get('config');

        /** @var string|null $driver */
        $driver = $config->get('phalcon-orm/connection/driver');

        switch ($driver) {
            case 'mysql':
                return new DatabaseAdapter\Mysql([
                    'host' => $config->get('phalcon-orm/connection/host'),
                    'port' => $config->get('phalcon-orm/connection/port'),
                    'username' => $config->get('phalcon-orm/connection/user'),
                    'password' => $config->get('phalcon-orm/connection/password'),
                    'dbname' => $config->get('phalcon-orm/connection/database')
                ]);
                break;

            default:
                throw new Exception('No connection details were specified in the configuration.');
                break;
        }
    }
}

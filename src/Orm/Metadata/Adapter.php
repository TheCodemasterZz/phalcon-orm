<?php

namespace LukeZbihlyj\PhalconOrm\Orm\Metadata;

use Phalcon\DI\Injectable;
use Phalcon\DI\InjectionAwareInterface;
use Phalcon\Mvc\Model\MetaData as MetadataAdapter;

/**
 * @package LukeZbihlyj\PhalconOrm\Orm\Metadata\Adapter
 */
class Adapter extends Injectable implements InjectionAwareInterface
{
    /**
     * @return Phalcon\Mvc\Model\Metadata\Adapter
     */
    public function resolve()
    {
        /** @var Phalcon\DI $di */
        $di = $this->getDI();

        /** @var Phalcon\Config $config */
        $config = $di->get('config');

        /** @var string|null $driver */
        $driver = $config->get('phalcon-orm/metadata/driver');

        switch ($driver) {
            case 'files':
                return new MetadataAdapter\Files([
                    'metaDataDir' => $config->get('phalcon-orm/metadata/path')
                ]);
                break;

            case 'apc':
                return new MetadataAdapter\Apc([
                    'prefix' => $config->get('phalcon-orm/metadata/prefix')
                ]);
                break;

            case 'xcache':
                return new MetadataAdapter\Xcache([
                    'prefix' => $config->get('phalcon-orm/metadata/prefix')
                ]);
                break;

            default:
                return new MetadataAdapter\Memory;
                break;
        }
    }
}

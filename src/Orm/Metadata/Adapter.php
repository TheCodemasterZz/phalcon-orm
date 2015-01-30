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
        $config = $di->get('config')->get('phalcon-orm');

        /** @var string|null $driver */
        $driver = $config->get('metadata')->get('driver');

        switch ($driver) {
            case 'files':
                return new MetadataAdapter\Files([
                    'metaDataDir' => $config->get('metadata')->get('path')
                ]);
                break;

            case 'apc':
                return new MetadataAdapter\Apc([
                    'prefix' => $config->get('metadata')->get('prefix')
                ]);
                break;

            case 'xcache':
                return new MetadataAdapter\Xcache([
                    'prefix' => $config->get('metadata')->get('prefix')
                ]);
                break;

            default:
                return new MetadataAdapter\Memory;
                break;
        }
    }
}

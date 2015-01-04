<?php

namespace LukeZbihlyj\PhalconOrm;

use Phalcon\Config;
use Phalcon\DI\Injectable;
use Phalcon\DI\InjectionAwareInterface;
use Phalcon\Exception;
use Phalcon\Mvc\Model\Manager as ModelsManager;
use Phalcon\Events\Manager as EventsManager;

/**
 * @package LukeZbihlyj\PhalconOrm\Module
 */
class Module extends Injectable implements InjectionAwareInterface
{
    /**
     * @return array
     */
    public function getConfig()
    {
        return require __DIR__ . '/../config/module.php';
    }

    /**
     * @return self
     * @throws Phalcon\Exception
     */
    public function init()
    {
        /** @var Phalcon\DI $di */
        $di = $this->getDI();

        /** @var Phalcon\Config $config */
        $config = $di->get('config')->get('phalcon-orm');

        $di->set('db', function() {
            $databaseAdapter = (new Orm\Database\Adapter($di))->resolve();

            return $databaseAdapter;
        });

        $di->set('modelsManager', function() use ($di) {
            $eventsManager = $di->get('eventsManager') ?: new EventsManager;
            $modelsManager = new ModelsManager;

            $modelsManager->setEventsManager($eventsManager);
            $eventsManager->attach('modelsManager', new Orm\Annotations\Initializer);

            return $modelsManager;
        });

        $di->set('modelsMetadata', function() {
            $metadataAdapter = (new Orm\Metadata\Adapter($di))->resolve();
            $metadataAdapter->setStrategy(new Orm\Annotations\MetaDataInitializer);

            return $metadataAdapter;
        });

        return $this;
    }

    /**
     * @param Phalcon\Config $config
     * @return Phalcon\Mvc\Model\MetaData
     */
    private function getMetadataAdapter(Config $config)
    {
        $metadata = $config->get('phalcon-orm')->get('metadata');

        if (!$metadata) {
            return new MetaDataAdapter\Memory;
        }

        switch ($metadata->get('driver')) {
            case 'apc':
                $metadataAdapter = new MetaDataAdapter\Apc([
                    'prefix' => $metadata->get('prefix')
                ]);
                break;

            case 'files':
                $metadataAdapter = new MetaDataAdapter\Files([
                    'metaDataDir' => $metadata->get('path')
                ]);
                break;

            case 'xcache':
                $metadataAdapter = new MetaDataAdapter\Xcache([
                    'prefix' => $metadata->get('prefix')
                ]);
                break;

            default:
                $metadataAdapter = new MetaDataAdapter\Memory;
                break;
        }

        return $metadataAdapter;
    }
}

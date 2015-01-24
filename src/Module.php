<?php

namespace LukeZbihlyj\PhalconOrm;

use Phalcon\Exception;
use Phalcon\Mvc\Model\Manager as ModelsManager;
use Phalcon\Events\Manager as EventsManager;
use LukeZbihlyj\PhalconPlus\AbstractModule;

/**
 * @package LukeZbihlyj\PhalconOrm\Module
 */
class Module extends AbstractModule
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
        $config = $this->getLocalConfig('phalcon-orm');

        $di->setShared('db', (new Orm\Database\Adapter($di))->resolve());

        $di->setShared('modelsManager', function() use ($di) {
            $eventManager = $di->get('eventsManager');
            $modelsManager = new ModelsManager;

            $modelsManager->setEventsManager($eventManager);
            $eventManager->attach('modelsManager', new Orm\Annotation\Initializer);

            return $modelsManager;
        });

        $di->setShared('modelsMetadata', function() use ($di) {
            $metadataAdapter = (new Orm\Metadata\Adapter($di))->resolve();
            $metadataAdapter->setStrategy(new Orm\Annotation\MetaDataInitializer);

            return $metadataAdapter;
        });

        return $this;
    }
}

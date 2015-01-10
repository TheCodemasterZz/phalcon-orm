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
        $config = $this->getLocalConfig();

        $di->setShared('db', (new Orm\Database\Adapter($di))->resolve());

        $di->setShared('modelsManager', function() use ($di) {
            $eventsManager = $di->get('eventsManager') ?: new EventsManager;
            $modelsManager = new ModelsManager;

            $modelsManager->setEventsManager($eventsManager);
            $eventsManager->attach('modelsManager', new Orm\Annotations\Initializer);

            return $modelsManager;
        });

        $di->setShared('modelsMetadata', function() use ($di) {
            $metadataAdapter = (new Orm\Metadata\Adapter($di))->resolve();
            $metadataAdapter->setStrategy(new Orm\Annotations\MetaDataInitializer);

            return $metadataAdapter;
        });

        return $this;
    }

    /**
     * @return Phalcon\Config
     */
    public function getLocalConfig()
    {
        return $this->getDI()->get('config')->get('phalcon-orm', new Config);
    }
}

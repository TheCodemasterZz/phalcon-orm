<?php

namespace LukeZbihlyj\PhalconOrm\Annotations;

use Phalcon\Events\Event;
use Phalcon\Mvc\Model\Manager as ModelsManager;
use Phalcon\Mvc\User\Plugin;

/**
 * @package LukeZbihlyj\PhalconOrm\Annotations\Initializer
 */

class Initializer extends Plugin {
    /**
     * This is called after initializing the model.
     *
     * @param Phalcon\Events\Event $event
     * @param Phalcon\Mvc\Model\Manager $manager
     * @param mixed $model
     */

    public function afterInitialize(Event $event, ModelsManager $manager, $model) {
        $reflector = $this->annotations->get($model);
        $annotations = $reflector->getClassAnnotations();

        if($annotations) {
            foreach($annotations as $annotation) {
                switch($annotation->getName()) {
                    case 'ORM\Source':
                        $arguments = $annotation->getArguments();
                        $manager->setModelSource($model, $arguments[0]);
                        break;

                    case 'ORM\HasMany':
                        $arguments = $annotation->getArguments();
                        $manager->addHasMany($model, $arguments[0], $arguments[1], $arguments[2]);
                        break;

                    case 'ORM\BelongsTo':
                        $arguments = $annotation->getArguments();

                        if(isset($arguments[3])) {
                            $manager->addBelongsTo($model, $arguments[0], $arguments[1], $arguments[2], $arguments[3]);
                        } else {
                            $manager->addBelongsTo($model, $arguments[0], $arguments[1], $arguments[2]);
                        }
                        break;
                }
            }
        }
    }
}

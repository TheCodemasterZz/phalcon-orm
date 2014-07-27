# phalcon-orm

Extends Phalcon's ORM to support the use of extended annotations.

## Installation

Add the following to your `composer.json` file.

```json
"require": {
    "lukezbihlyj/phalcon-orm": "~0.9.0"
}
```

And then run a simple `composer install` to pull the files.

## Usage

Set up the services like the following:

```php
use Phalcon\Events\Manager as EventsManager,
    Phalcon\Mvc\Model\Manager as ModelsManager,
    Phalcon\Mvc\Model\MetaData\Files as MetaDataAdapter,
    Phalcon\Annotations\Adapter\Files as AnnotationsAdapter,
    Phalcon\DI,
    LukeZbihlyj\PhalconOrm\Annotations\Initializer as OrmAnnotationsInitializer,
    LukeZbihlyj\PhalconOrm\Annotations\MetaDataInitializer as OrmAnnotationsMetaDataInitializer;

$di = new DI;

$di->set("modelsManager", function() {
    $eventsManager = new EventsManager;
    $modelsManager = new ModelsManager;

    $modelsManager->setEventsManager($eventsManager);
    $eventsManager->attach("modelsManager", new OrmAnnotationsInitializer);

    return $modelsManager;
});

$di->set("modelsMetadata", function() {
    $metaData = new MetaDataAdapter([
        "metaDataDir" => "./cache/orm/metadata/"
    ]);

    $metaData->setStrategy(new OrmAnnotationsMetaDataInitializer);

    return $metaData;
});

$di->set("annotations", function() {
    return new AnnotationsAdapter([
        "annotationsDir" => "./cache/orm/annotations/"
    ]);
});
```

And then to use annotations in a model:

```php
/**
 * @package User
 *
 * @ORM\Source("user")
 * @ORM\HasMany("id", "UserEmail", "userId")
 */

class User extends Phalcon\Mvc\Model {
    /**
     * @var integer
     *
     * @ORM\Column(type="integer", name="id", nullable=false)
     * @ORM\Primary
     * @ORM\Identity
     */

    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="nickname", nullable=true)
     */

    private $nickname;

    ...
```

```php
/**
 * @package UserEmail
 *
 * @ORM\Source("user_email")
 * @ORM\BelongsTo("userId", "User", "id", {"alias": "email"})
 */

class UserEmail extends Phalcon\Mvc\Model {
    /**
     * @var integer
     *
     * @ORM\Column(type="integer", name="id", nullable=false)
     * @ORM\Primary
     * @ORM\Identity
     */

    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="email", nullable=false)
     */

    private $address;

    ...
```

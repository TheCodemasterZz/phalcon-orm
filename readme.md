# phalcon-orm

Extends Phalcon's ORM to support the use of extended annotations within models.

## Installation

Add the following to your `composer.json` file.

```json
"require": {
    "lukezbihlyj/phalcon-orm": "~1.0-dev"
}
```

And then run a simple `composer install` to pull the files.

## Setup

There are two ways to set up Phalcon with this module.

### 1. With [Phalcon+](https://github.com/lukezbihlyj/phalcon-plus)

If you're using [Phalcon+](https://github.com/lukezbihlyj/phalcon-plus) for your application then you can take advantage of the module loading system. In your `application.config.php`, add:

```php
'modules' => [
    'LukeZbihlyj\PhalconOrm',
],
```

Et voila! You're done.

### 2. Standalone

Set up the your services with the following:

```php
use Phalcon\DI\FactoryDefault as DependencyInjector,
    Phalcon\Events\Manager as EventsManager,
    Phalcon\Mvc\Model\Manager as ModelsManager,
    Phalcon\Mvc\Model\MetaData\Memory as MetaDataAdapter,
    LukeZbihlyj\PhalconOrm\Orm\Annotations\Initializer as OrmInitializer,
    LukeZbihlyj\PhalconOrm\Orm\Annotations\MetaDataInitializer as OrmMetaDataInitializer;

$di = new DependencyInjector;

$di->set('modelsManager', function() {
    $eventsManager = new EventsManager;
    $modelsManager = new ModelsManager;

    $modelsManager->setEventsManager($eventsManager);
    $eventsManager->attach('modelsManager', new OrmInitializer);

    return $modelsManager;
});

$di->set('modelsMetadata', function() {
    $metaData = new MetaDataAdapter;
    $metaData->setStrategy(new OrmMetaDataInitializer);

    return $metaData;
});
```

## Usage

```php
/**
 * @package User
 *
 * @ORM\Source("user")
 * @ORM\HasMany("id", "UserEmail", "userId")
 */
class User extends \Phalcon\Mvc\Model
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer", name="id", nullable=false)
     * @ORM\Primary
     * @ORM\Identity
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="nickname", nullable=true)
     */
    protected $nickname;

    ...
```

```php
/**
 * @package UserEmail
 *
 * @ORM\Source("user_email")
 * @ORM\BelongsTo("userId", "User", "id", {"alias": "email"})
 */
class UserEmail extends \Phalcon\Mvc\Model
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer", name="id", nullable=false)
     * @ORM\Primary
     * @ORM\Identity
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="address", nullable=false)
     */
    protected $address;

    ...
```

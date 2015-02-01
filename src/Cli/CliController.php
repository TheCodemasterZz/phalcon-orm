<?php

namespace LukeZbihlyj\PhalconOrm\Cli;

use LukeZbihlyj\PhalconCli\Command as BaseTask;
use LukeZbihlyj\PhalconCli\Input\InputOption;

/**
 * @package LukeZbihlyj\PhalconOrm\Cli\CliController
 */
class CliController extends BaseTask
{
    /**
     * @param array $args
     * @return void
     */
    public function generateAction($args = [])
    {
        /** @var Phalcon\DI $di */
        $di = $this->getDI();

        /** @var Phalcon\Config $config */
        $config = $di->get('config');

        /** @var Phalcon\Events\Manager $eventManager */
        $eventManager = $di->get('eventsManager');

        /** @var LukeZbihlyj\PhalconCli\Output\Output $output */
        $output = $this->getOutput();
        $execute = $this->getOption()->get('execute', false);

        $output->writeln('Generating schema updates for `<comment>%s</comment>`...', $config->get('phalcon-orm/connection/database'));
        $output->writeln();

        $output->writeln('<danger>Feature coming soon!</danger>');
    }
}

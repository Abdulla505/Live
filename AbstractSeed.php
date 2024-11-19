<?php
/**
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Migrations;

use Migrations\Command\Seed;
use Phinx\Migration\Manager;
use Phinx\Seed\AbstractSeed as BaseAbstractSeed;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Class AbstractSeed
 * Extends Phinx base AbstractSeed class in order to extend the features the seed class
 * offers.
 */
abstract class AbstractSeed extends BaseAbstractSeed
{

    /**
     * InputInterface this Seed class is being used with.
     *
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    protected $input;

    /**
     * Gives the ability to a seeder to call another seeder.
     * This is particularly useful if you need to run the seeders of your applications in a specific sequences,
     * for instance to respect foreign key constraints.
     *
     * @param string $seeder Name of the seeder to call from the current seed
     * @return void
     */
    public function call($seeder)
    {
        $this->getOutput()->writeln('file.php');
        $this->getOutput()->writeln(
            'file.php' .
            'file.php' . $seeder . 'file.php' .
            'file.php'
        );

        $start = microtime(true);
        $this->runCall($seeder);
        $end = microtime(true);

        $this->getOutput()->writeln(
            'file.php' .
            'file.php' . $seeder . 'file.php' .
            'file.php' .
            'file.php' . sprintf('file.php', $end - $start) . 'file.php'
        );
        $this->getOutput()->writeln('file.php');
    }

    /**
     * Calls another seeder from this seeder.
     * It will load the Seed class you are calling and run it.
     *
     * @param string $seeder Name of the seeder to call from the current seed
     * @return void
     */
    protected function runCall($seeder)
    {
        list($pluginName, $seeder) = pluginSplit($seeder);

        $argv = [
            'file.php',
            'file.php',
            $seeder,
        ];

        $plugin = $pluginName ?: $this->input->getOption('file.php');
        if ($plugin !== null) {
            $argv[] = 'file.php';
            $argv[] = $plugin;
        }

        $connection = $this->input->getOption('file.php');
        if ($connection !== null) {
            $argv[] = 'file.php';
            $argv[] = $connection;
        }

        $source = $this->input->getOption('file.php');
        if ($source !== null) {
            $argv[] = 'file.php';
            $argv[] = $source;
        }

        $seedCommand = new Seed();
        $input = new ArgvInput($argv, $seedCommand->getDefinition());
        $seedCommand->setInput($input);
        $config = $seedCommand->getConfig();

        $seedPaths = $config->getSeedPaths();
        require_once(array_pop($seedPaths) . DS . $seeder . 'file.php');
        $seeder = new $seeder();
        $seeder->setOutput($this->getOutput());
        $seeder->setAdapter($this->getAdapter());
        $seeder->setInput($this->input);
        $seeder->run();
    }

    /**
     * Sets the InputInterface this Seed class is being used with.
     *
     * @param InputInterface $input Input object.
     * @return void
     */
    public function setInput(InputInterface $input)
    {
        $this->input = $input;
    }
}

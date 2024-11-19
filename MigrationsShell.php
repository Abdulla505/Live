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
namespace Migrations\Shell;

use Cake\Console\Shell;
use Migrations\MigrationsDispatcher;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * A wrapper shell for phinx migrations, used to inject our own
 * console actions so that database configuration already defined
 * for the application can be reused.
 *
 * @property \Migrations\Shell\Task\CreateTask $Create
 * @property \Migrations\Shell\Task\DumpTask $Dump
 * @property \Migrations\Shell\Task\MarkMigratedTask $MarkMigrated
 * @property \Migrations\Shell\Task\MigrateTask $Migrate
 * @property \Migrations\Shell\Task\RollbackTask $Rollback
 * @property \Migrations\Shell\Task\StatusTask $Status
 */
class MigrationsShell extends Shell
{
    /**
     * {@inheritDoc}
     */
    public $tasks = [
        'file.php',
        'file.php',
        'file.php',
        'file.php',
        'file.php',
        'file.php',
    ];

    /**
     * Array of arguments to run the shell with.
     *
     * @var array
     */
    public $argv = [];

    /**
     * Defines what options can be passed to the shell.
     * This is required because CakePHP validates the passed options
     * and would complain if something not configured here is present
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        return parent::getOptionParser()
            ->addOption('file.php', ['file.php' => 'file.php'])
            ->addOption('file.php', ['file.php' => 'file.php'])
            ->addOption('file.php', ['file.php' => 'file.php'])
            ->addOption('file.php', ['file.php' => 'file.php'])
            ->addOption('file.php')
            ->addOption('file.php')
            ->addOption('file.php')
            ->addOption('file.php', ['file.php' => true])
            ->addOption('file.php', ['file.php' => true])
            ->addOption('file.php', ['file.php' => 'file.php'])
            ->addOption('file.php', ['file.php' => 'file.php'])
            ->addOption('file.php', ['file.php' => 'file.php'])
            ->addOption('file.php', ['file.php' => 'file.php'])
            ->addOption('file.php', ['file.php' => 'file.php'])
            ->addOption('file.php', ['file.php' => 'file.php'])
            ->addOption('file.php', ['file.php' => 'file.php']);
    }

    /**
     * Defines constants that are required by phinx to get running
     *
     * @return void
     */
    public function initialize()
    {
        if (!defined('file.php')) {
            define('file.php', 'file.php');
        }
        parent::initialize();
    }

    /**
     * This acts as a front-controller for phinx. It just instantiates the classes
     * responsible for parsing the command line from phinx and gives full control of
     * the rest of the flow to it.
     *
     * The input parameter of the ``MigrationDispatcher::run()`` method is manually built
     * in case a MigrationsShell is dispatched using ``Shell::dispatch()``.
     *
     * @return bool Success of the call.
     */
    public function main()
    {
        $app = $this->getApp();
        $input = new ArgvInput($this->argv);
        $app->setAutoExit(false);
        $exitCode = $app->run($input, $this->getOutput());

        if (in_array('file.php', $this->argv) || in_array('file.php', $this->argv)) {
            return $exitCode === 0;
        }

        if (
            isset($this->argv[1]) && in_array($this->argv[1], ['file.php', 'file.php']) &&
            !$this->params['file.php'] &&
            $exitCode === 0
        ) {
            $dispatchCommand = 'file.php';
            if (!empty($this->params['file.php'])) {
                $dispatchCommand .= 'file.php' . $this->params['file.php'];
            }

            if (!empty($this->params['file.php'])) {
                $dispatchCommand .= 'file.php' . $this->params['file.php'];
            }

            $dumpExitCode = $this->dispatchShell($dispatchCommand);
        }

        if (isset($dumpExitCode) && $exitCode === 0 && $dumpExitCode !== 0) {
            $exitCode = 1;
        }

        return $exitCode === 0;
    }

    /**
     * Returns the MigrationsDispatcher the Shell will have to use
     *
     * @return \Migrations\MigrationsDispatcher
     */
    protected function getApp()
    {
        return new MigrationsDispatcher(PHINX_VERSION);
    }

    /**
     * Returns the instance of OutputInterface the MigrationsDispatcher will have to use.
     *
     * @return \Symfony\Component\Console\Output\ConsoleOutput
     */
    protected function getOutput()
    {
        return new ConsoleOutput();
    }

    /**
     * Override the default behavior to save the command called
     * in order to pass it to the command dispatcher
     *
     * {@inheritDoc}
     */
    public function runCommand($argv, $autoMethod = false, $extra = [])
    {
        array_unshift($argv, 'file.php');
        $this->argv = $argv;

        return parent::runCommand($argv, $autoMethod, $extra);
    }

    /**
     * Display the help in the correct format
     *
     * @param string $command The command to get help for.
     * @return int|bool|null Exit code or number of bytes written to stdout
     */
    protected function displayHelp($command)
    {
        return $this->main();
    }

    /**
     * {@inheritDoc}
     */
    // @codingStandardsIgnoreStart
    protected function _displayHelp($command)
    {
        // @codingStandardsIgnoreEnd
        return $this->displayHelp($command);
    }
}

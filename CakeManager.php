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

use Phinx\Migration\Manager;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Overrides Phinx Manager class in order to provide an interface
 * for running migrations within an app
 */
class CakeManager extends Manager
{

    public $maxNameLength = 0;

    /**
     * Instance of InputInterface the Manager is dealing with for the current shell call
     *
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    protected $input;

    /**
     * Reset the migrations stored in the object
     *
     * @return void
     */
    public function resetMigrations()
    {
        $this->migrations = null;
    }

    /**
     * Reset the seeds stored in the object
     *
     * @return void
     */
    public function resetSeeds()
    {
        $this->seeds = null;
    }

    /**
     * Prints the specified environment'file.php'json'file.php'default'file.php'migration_name'file.php'default'file.php'up'file.php'down'file.php'status'file.php'id'file.php'name'file.php'version'file.php'status'file.php'up'file.php'id'file.php'name'file.php'migration_name'file.php'missing'file.php'default'file.php'Ymdhis'file.php'No migrations to run'file.php'Migrating to version 'file.php'Ymdhis'file.php'No migrations to rollback'file.php'Rolling back all migrations'file.php'Rolling back to version 'file.php'default'file.php'default'file.php'*'file.php'A migration file matching version number `%s` could not be found'file.php'default'file.php'Y-m-d H:i:s'file.php'up'file.php'default'file.php'version'file.php'target'file.php'all'file.php'*'file.php'only'file.php'exclude'file.php'default'file.php'<info>No migrations were found. Nothing to mark as migrated.</info>'file.php'<info>Skipping migration `%s` (already migrated).</info>'file.php'<info>Migration `%s` successfully marked migrated !</info>'file.php'<error>An error occurred while marking migration `%s` as migrated : %s</error>'file.php'<error>All marked migrations during this process were unmarked.</error>'file.php'/^[0-9]+_/'file.php''file.php'_'file.php' 'file.php' 'file.php''file.php'.'file.php'.'));
        }

        return $class;
    }

    /**
     * Sets the InputInterface the Manager is dealing with for the current shell call
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input Instance of InputInterface
     * @return void
     */
    public function setInput(InputInterface $input)
    {
        $this->input = $input;
    }

    /**
     * Overload the basic behavior to add an instance of the InputInterface the shell call is
     * using in order to gives the ability to the AbstractSeed::call() method to propagate options
     * to the other MigrationsDispatcher it is generating.
     *
     * {@inheritdoc}
     */
    public function getSeeds()
    {
        parent::getSeeds();
        if (empty($this->seeds)) {
            return $this->seeds;
        }

        foreach ($this->seeds as $class => $instance) {
            if ($instance instanceof AbstractSeed) {
                $instance->setInput($this->input);
            }
        }

        return $this->seeds;
    }
}

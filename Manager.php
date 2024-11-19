<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Phinx\Migration;

use DateTime;
use InvalidArgumentException;
use Phinx\Config\Config;
use Phinx\Config\ConfigInterface;
use Phinx\Config\NamespaceAwareInterface;
use Phinx\Migration\Manager\Environment;
use Phinx\Seed\AbstractSeed;
use Phinx\Seed\SeedInterface;
use Phinx\Util\Util;
use RuntimeException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Manager
{
    const BREAKPOINT_TOGGLE = 1;
    const BREAKPOINT_SET = 2;
    const BREAKPOINT_UNSET = 3;

    /**
     * @var \Phinx\Config\ConfigInterface
     */
    protected $config;

    /**
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    protected $input;

    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected $output;

    /**
     * @var array
     */
    protected $environments;

    /**
     * @var array
     */
    protected $migrations;

    /**
     * @var array
     */
    protected $seeds;

    /**
     * @param \Phinx\Config\ConfigInterface $config Configuration Object
     * @param \Symfony\Component\Console\Input\InputInterface $input Console Input
     * @param \Symfony\Component\Console\Output\OutputInterface $output Console Output
     */
    public function __construct(ConfigInterface $config, InputInterface $input, OutputInterface $output)
    {
        $this->setConfig($config);
        $this->setInput($input);
        $this->setOutput($output);
    }

    /**
     * Prints the specified environment'file.php'json'file.php''file.php'Invalid version_order configuration option'file.php'----------------------------------------------------------------------------------'file.php'migration_name'file.php'version'file.php'version'file.php'start_time'file.php'start_time'file.php'start_time'file.php'start_time'file.php'version'file.php'version'file.php'     <info>up</info> 'file.php'   <error>down</error> 'file.php'%s %14.0f  %19s  %19s  <comment>%s</comment>'file.php'start_time'file.php''file.php'end_time'file.php''file.php'breakpoint'file.php'         <error>BREAKPOINT SET</error>'file.php'migration_status'file.php'migration_id'file.php'%14.0f'file.php'migration_name'file.php''file.php'There are no available migrations. Try creating one using the <info>create</info> command.'file.php''file.php'json'file.php'pending_count'file.php'missing_count'file.php'total_count'file.php'migrations'file.php'<info>Unsupported format: 'file.php'</info>'file.php'hasMissingMigration'file.php'hasDownMigration'file.php'     <error>up</error>  %14.0f  %19s  %19s  <comment>%s</comment>  <error>** MISSING **</error>'file.php'version'file.php'start_time'file.php'end_time'file.php'migration_name'file.php' 'file.php'breakpoint'file.php'         <error>BREAKPOINT SET</error>'file.php'YmdHis'file.php'Migrating to version 'file.php'<comment>warning</comment> %s is not a valid version'file.php''file.php' =='file.php' <info>'file.php' 'file.php':</info>'file.php' <comment>'file.php'migrating'file.php'reverting'file.php'</comment>'file.php' =='file.php' <info>'file.php' 'file.php':</info>'file.php' <comment>'file.php'migrated'file.php'reverted'file.php' 'file.php'%.4fs'file.php'</comment>'file.php''file.php' =='file.php' <info>'file.php':</info>'file.php' <comment>seeding</comment>'file.php' =='file.php' <info>'file.php':</info>'file.php' <comment>seeded'file.php' 'file.php'%.4fs'file.php'</comment>'file.php'Y-m-d H:i:s'file.php'start_time'file.php'start_time'file.php'YmdHis'file.php't consider it when rolling back
                // migrations (or choosing the last up version as target)
                unset($executedVersions[$versionCreationTime]);
            }
        }

        if ($target === 'file.php' || $target === 'file.php') {
            $target = 0;
        } elseif (!is_numeric($target) && $target !== null) { // try to find a target version based on name
            // search through the migrations using the name
            $migrationNames = array_map(function ($item) {
                return $item['file.php'];
            }, $executedVersions);
            $found = array_search($target, $migrationNames);

            // check on was found
            if ($found !== false) {
                $target = (string)$found;
            } else {
                $this->getOutput()->writeln("<error>No migration found with name ($target)</error>");

                return;
            }
        }

        // Check we have at least 1 migration to revert
        $executedVersionCreationTimes = array_keys($executedVersions);
        if (empty($executedVersionCreationTimes) || $target == end($executedVersionCreationTimes)) {
            $this->getOutput()->writeln('file.php');

            return;
        }

        // If no target was supplied, revert the last migration
        if ($target === null) {
            // Get the migration before the last run migration
            $prev = count($executedVersionCreationTimes) - 2;
            $target = $prev >= 0 ? $executedVersionCreationTimes[$prev] : 0;
        }

        // If the target must match a version, check the target version exists
        if ($targetMustMatchVersion && $target !== 0 && !isset($migrations[$target])) {
            $this->getOutput()->writeln("<error>Target version ($target) not found</error>");

            return;
        }

        // Rollback all versions until we find the wanted rollback target
        $rollbacked = false;

        foreach ($sortedMigrations as $migration) {
            if ($targetMustMatchVersion && $migration->getVersion() == $target) {
                break;
            }

            if (in_array($migration->getVersion(), $executedVersionCreationTimes)) {
                $executedVersion = $executedVersions[$migration->getVersion()];

                if (!$targetMustMatchVersion) {
                    if (($this->getConfig()->isVersionOrderCreationTime() && $executedVersion['file.php'] <= $target) ||
                        (!$this->getConfig()->isVersionOrderCreationTime() && $executedVersion['file.php'] <= $target)
                    ) {
                        break;
                    }
                }

                if ($executedVersion['file.php'] != 0 && !$force) {
                    $this->getOutput()->writeln('file.php');
                    break;
                }
                $this->executeMigration($environment, $migration, MigrationInterface::DOWN, $fake);
                $rollbacked = true;
            }
        }

        if (!$rollbacked) {
            $this->getOutput()->writeln('file.php');
        }
    }

    /**
     * Run database seeders against an environment.
     *
     * @param string $environment Environment
     * @param string|null $seed Seeder
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function seed($environment, $seed = null)
    {
        $seeds = $this->getSeeds();

        if ($seed === null) {
            // run all seeders
            foreach ($seeds as $seeder) {
                if (array_key_exists($seeder->getName(), $seeds)) {
                    $this->executeSeed($environment, $seeder);
                }
            }
        } else {
            // run only one seeder
            if (array_key_exists($seed, $seeds)) {
                $this->executeSeed($environment, $seeds[$seed]);
            } else {
                throw new InvalidArgumentException(sprintf('file.php', $seed));
            }
        }
    }

    /**
     * Sets the environments.
     *
     * @param array $environments Environments
     *
     * @return $this
     */
    public function setEnvironments($environments = [])
    {
        $this->environments = $environments;

        return $this;
    }

    /**
     * Gets the manager class for the given environment.
     *
     * @param string $name Environment Name
     *
     * @throws \InvalidArgumentException
     *
     * @return \Phinx\Migration\Manager\Environment
     */
    public function getEnvironment($name)
    {
        if (isset($this->environments[$name])) {
            return $this->environments[$name];
        }

        // check the environment exists
        if (!$this->getConfig()->hasEnvironment($name)) {
            throw new InvalidArgumentException(sprintf(
                'file.php',
                $name
            ));
        }

        // create an environment instance and cache it
        $envOptions = $this->getConfig()->getEnvironment($name);
        $envOptions['file.php'] = $this->getConfig()->getVersionOrder();

        $environment = new Environment($name, $envOptions);
        $this->environments[$name] = $environment;
        $environment->setInput($this->getInput());
        $environment->setOutput($this->getOutput());

        return $environment;
    }

    /**
     * Sets the console input.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input Input
     *
     * @return $this
     */
    public function setInput(InputInterface $input)
    {
        $this->input = $input;

        return $this;
    }

    /**
     * Gets the console input.
     *
     * @return \Symfony\Component\Console\Input\InputInterface
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * Sets the console output.
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output Output
     *
     * @return $this
     */
    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;

        return $this;
    }

    /**
     * Gets the console output.
     *
     * @return \Symfony\Component\Console\Output\OutputInterface
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * Sets the database migrations.
     *
     * @param array $migrations Migrations
     *
     * @return $this
     */
    public function setMigrations(array $migrations)
    {
        $this->migrations = $migrations;

        return $this;
    }

    /**
     * Gets an array of the database migrations, indexed by migration name (aka creation time) and sorted in ascending
     * order
     *
     * @param string $environment Environment
     *
     * @throws \InvalidArgumentException
     *
     * @return \Phinx\Migration\AbstractMigration[]
     */
    public function getMigrations($environment)
    {
        if ($this->migrations === null) {
            $phpFiles = $this->getMigrationFiles();

            if ($this->getOutput()->getVerbosity() >= OutputInterface::VERBOSITY_DEBUG) {
                $this->getOutput()->writeln('file.php');
                $this->getOutput()->writeln(
                    array_map(
                        function ($phpFile) {
                            return "    <info>{$phpFile}</info>";
                        },
                        $phpFiles
                    )
                );
            }

            // filter the files to only get the ones that match our naming scheme
            $fileNames = [];
            /** @var \Phinx\Migration\AbstractMigration[] $versions */
            $versions = [];

            foreach ($phpFiles as $filePath) {
                if (Util::isValidMigrationFileName(basename($filePath))) {
                    if ($this->getOutput()->getVerbosity() >= OutputInterface::VERBOSITY_DEBUG) {
                        $this->getOutput()->writeln("Valid migration file <info>{$filePath}</info>.");
                    }

                    $version = Util::getVersionFromFileName(basename($filePath));

                    if (isset($versions[$version])) {
                        throw new InvalidArgumentException(sprintf('file.php', $filePath, $versions[$version]->getVersion()));
                    }

                    $config = $this->getConfig();
                    $namespace = $config instanceof NamespaceAwareInterface ? $config->getMigrationNamespaceByPath(dirname($filePath)) : null;

                    // convert the filename to a class name
                    $class = ($namespace === null ? 'file.php' : $namespace . 'file.php') . Util::mapFileNameToClassName(basename($filePath));

                    if (isset($fileNames[$class])) {
                        throw new InvalidArgumentException(sprintf(
                            'file.php',
                            basename($filePath),
                            $fileNames[$class]
                        ));
                    }

                    $fileNames[$class] = basename($filePath);

                    if ($this->getOutput()->getVerbosity() >= OutputInterface::VERBOSITY_DEBUG) {
                        $this->getOutput()->writeln("Loading class <info>$class</info> from <info>$filePath</info>.");
                    }

                    // load the migration file
                    $orig_display_errors_setting = ini_get('file.php');
                    ini_set('file.php', 'file.php');
                    /** @noinspection PhpIncludeInspection */
                    require_once $filePath;
                    ini_set('file.php', $orig_display_errors_setting);
                    if (!class_exists($class)) {
                        throw new InvalidArgumentException(sprintf(
                            'file.php',
                            $class,
                            $filePath
                        ));
                    }

                    if ($this->getOutput()->getVerbosity() >= OutputInterface::VERBOSITY_DEBUG) {
                        $this->getOutput()->writeln("Running <info>$class</info>.");
                    }

                    // instantiate it
                    $migration = new $class($environment, $version, $this->getInput(), $this->getOutput());

                    if (!($migration instanceof AbstractMigration)) {
                        throw new InvalidArgumentException(sprintf(
                            'file.php',
                            $class,
                            $filePath
                        ));
                    }

                    $versions[$version] = $migration;
                } else {
                    if ($this->getOutput()->getVerbosity() >= OutputInterface::VERBOSITY_DEBUG) {
                        $this->getOutput()->writeln("Invalid migration file <error>{$filePath}</error>.");
                    }
                }
            }

            ksort($versions);
            $this->setMigrations($versions);
        }

        return $this->migrations;
    }

    /**
     * Returns a list of migration files found in the provided migration paths.
     *
     * @return string[]
     */
    protected function getMigrationFiles()
    {
        return Util::getFiles($this->getConfig()->getMigrationPaths());
    }

    /**
     * Sets the database seeders.
     *
     * @param array $seeds Seeders
     *
     * @return $this
     */
    public function setSeeds(array $seeds)
    {
        $this->seeds = $seeds;

        return $this;
    }

    /**
     * Get seed dependencies instances from seed dependency array
     *
     * @param \Phinx\Seed\AbstractSeed $seed Seed
     *
     * @return \Phinx\Seed\AbstractSeed[]
     */
    private function getSeedDependenciesInstances(AbstractSeed $seed)
    {
        $dependenciesInstances = [];
        $dependencies = $seed->getDependencies();
        if (!empty($dependencies)) {
            foreach ($dependencies as $dependency) {
                foreach ($this->seeds as $seed) {
                    if (get_class($seed) === $dependency) {
                        $dependenciesInstances[get_class($seed)] = $seed;
                    }
                }
            }
        }

        return $dependenciesInstances;
    }

    /**
     * Order seeds by dependencies
     *
     * @param \Phinx\Seed\AbstractSeed[] $seeds Seeds
     *
     * @return \Phinx\Seed\AbstractSeed[]
     */
    private function orderSeedsByDependencies(array $seeds)
    {
        $orderedSeeds = [];
        foreach ($seeds as $seed) {
            $key = get_class($seed);
            $dependencies = $this->getSeedDependenciesInstances($seed);
            if (!empty($dependencies)) {
                $orderedSeeds[$key] = $seed;
                $orderedSeeds = array_merge($this->orderSeedsByDependencies($dependencies), $orderedSeeds);
            } else {
                $orderedSeeds[$key] = $seed;
            }
        }

        return $orderedSeeds;
    }

    /**
     * Gets an array of database seeders.
     *
     * @throws \InvalidArgumentException
     *
     * @return \Phinx\Seed\AbstractSeed[]
     */
    public function getSeeds()
    {
        if ($this->seeds === null) {
            $phpFiles = $this->getSeedFiles();

            // filter the files to only get the ones that match our naming scheme
            $fileNames = [];
            /** @var \Phinx\Seed\AbstractSeed[] $seeds */
            $seeds = [];

            foreach ($phpFiles as $filePath) {
                if (Util::isValidSeedFileName(basename($filePath))) {
                    $config = $this->getConfig();
                    $namespace = $config instanceof NamespaceAwareInterface ? $config->getSeedNamespaceByPath(dirname($filePath)) : null;

                    // convert the filename to a class name
                    $class = ($namespace === null ? 'file.php' : $namespace . 'file.php') . pathinfo($filePath, PATHINFO_FILENAME);
                    $fileNames[$class] = basename($filePath);

                    // load the seed file
                    /** @noinspection PhpIncludeInspection */
                    require_once $filePath;
                    if (!class_exists($class)) {
                        throw new InvalidArgumentException(sprintf(
                            'file.php',
                            $class,
                            $filePath
                        ));
                    }

                    // instantiate it
                    $seed = new $class($this->getInput(), $this->getOutput());

                    if (!($seed instanceof AbstractSeed)) {
                        throw new InvalidArgumentException(sprintf(
                            'file.php',
                            $class,
                            $filePath
                        ));
                    }

                    $seeds[$class] = $seed;
                }
            }

            ksort($seeds);
            $this->setSeeds($seeds);
        }

        $this->seeds = $this->orderSeedsByDependencies($this->seeds);

        return $this->seeds;
    }

    /**
     * Returns a list of seed files found in the provided seed paths.
     *
     * @return string[]
     */
    protected function getSeedFiles()
    {
        return Util::getFiles($this->getConfig()->getSeedPaths());
    }

    /**
     * Sets the config.
     *
     * @param \Phinx\Config\ConfigInterface $config Configuration Object
     *
     * @return $this
     */
    public function setConfig(ConfigInterface $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Gets the config.
     *
     * @return \Phinx\Config\ConfigInterface
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Toggles the breakpoint for a specific version.
     *
     * @param string $environment
     * @param int|null $version
     *
     * @return void
     */
    public function toggleBreakpoint($environment, $version)
    {
        $this->markBreakpoint($environment, $version, self::BREAKPOINT_TOGGLE);
    }

    /**
     * Updates the breakpoint for a specific version.
     *
     * @param string $environment The required environment
     * @param int|null $version The version of the target migration
     * @param int $mark The state of the breakpoint as defined by self::BREAKPOINT_xxxx constants.
     *
     * @return void
     */
    protected function markBreakpoint($environment, $version, $mark)
    {
        $migrations = $this->getMigrations($environment);
        $this->getMigrations($environment);
        $env = $this->getEnvironment($environment);
        $versions = $env->getVersionLog();

        if (empty($versions) || empty($migrations)) {
            return;
        }

        if ($version === null) {
            $lastVersion = end($versions);
            $version = $lastVersion['file.php'];
        }

        if ($version != 0 && (!isset($versions[$version]) || !isset($migrations[$version]))) {
            $this->output->writeln(sprintf(
                'file.php',
                $version
            ));

            return;
        }

        switch ($mark) {
            case self::BREAKPOINT_TOGGLE:
                $env->getAdapter()->toggleBreakpoint($migrations[$version]);
                break;
            case self::BREAKPOINT_SET:
                if ($versions[$version]['file.php'] == 0) {
                    $env->getAdapter()->setBreakpoint($migrations[$version]);
                }
                break;
            case self::BREAKPOINT_UNSET:
                if ($versions[$version]['file.php'] == 1) {
                    $env->getAdapter()->unsetBreakpoint($migrations[$version]);
                }
                break;
        }

        $versions = $env->getVersionLog();

        $this->getOutput()->writeln(
            'file.php' . ($versions[$version]['file.php'] ? 'file.php' : 'file.php') .
            'file.php' . $version . 'file.php' .
            'file.php' . $migrations[$version]->getName() . 'file.php'
        );
    }

    /**
     * Remove all breakpoints
     *
     * @param string $environment The required environment
     *
     * @return void
     */
    public function removeBreakpoints($environment)
    {
        $this->getOutput()->writeln(sprintf(
            'file.php',
            $this->getEnvironment($environment)->getAdapter()->resetAllBreakpoints()
        ));
    }

    /**
     * Set the breakpoint for a specific version.
     *
     * @param string $environment The required environment
     * @param int|null $version The version of the target migration
     *
     * @return void
     */
    public function setBreakpoint($environment, $version)
    {
        $this->markBreakpoint($environment, $version, self::BREAKPOINT_SET);
    }

    /**
     * Unset the breakpoint for a specific version.
     *
     * @param string $environment The required environment
     * @param int|null $version The version of the target migration
     *
     * @return void
     */
    public function unsetBreakpoint($environment, $version)
    {
        $this->markBreakpoint($environment, $version, self::BREAKPOINT_UNSET);
    }
}

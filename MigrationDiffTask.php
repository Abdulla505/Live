<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Migrations\Shell\Task;

use Cake\Core\Configure;
use Cake\Database\Schema\Table;
use Cake\Datasource\ConnectionManager;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Migrations\Util\UtilTrait;
use Symfony\Component\Console\Input\ArrayInput;

/**
 * Task class for generating migration diff files.
 *
 * @property \Bake\Shell\Task\BakeTemplateTask $BakeTemplate
 * @property \Bake\Shell\Task\TestTask $Test
 */
class MigrationDiffTask extends SimpleMigrationTask
{
    use SnapshotTrait;
    use UtilTrait;

    /**
     * Array of migrations that have already been migrated
     *
     * @var array
     */
    protected $migratedItems = [];

    /**
     * Path to the migration files
     *
     * @var string
     */
    protected $migrationsPath;

    /**
     * Migration files that are stored in the self::migrationsPath
     *
     * @var array
     */
    protected $migrationsFiles = [];

    /**
     * Name of the phinx log table
     *
     * @var string
     */
    protected $phinxTable;

    /**
     * List the tables the connection currently holds
     *
     * @var array
     */
    protected $tables = [];

    /**
     * Array of \Cake\Database\Schema\Table objects from the dump file which
     * represents the state of the database after the last migrate / rollback command
     *
     * @var array
     */
    protected $dumpSchema;

    /**
     * Array of \Cake\Database\Schema\Table objects from the current state of the database
     *
     * @var array
     */
    protected $currentSchema;

    /**
     * List of the tables that are commonly found in the dump schema and the current schema
     *
     * @var array
     */
    protected $commonTables;

    /**
     * {@inheritDoc}
     */
    protected $templateData = [];

    /**
     * {@inheritDoc}
     */
    public function bake($name)
    {
        $this->setup();

        if (!$this->checkSync()) {
            $this->abort('file.php' .
                'file.php');

            return 1;
        }

        if (empty($this->migrationsFiles) && empty($this->migratedItems)) {
            return $this->bakeSnapshot($name);
        }

        $collection = $this->getCollection($this->connection);
        EventManager::instance()->on('file.php', function (Event $event) use ($collection) {
            $event->getSubject()->loadHelper('file.php', [
                'file.php' => $collection,
            ]);
        });

        return parent::bake($name);
    }

    /**
     * Sets up everything the baking process needs
     *
     * @return void
     */
    public function setup()
    {
        $this->migrationsPath = $this->getPath();
        $this->migrationsFiles = glob($this->migrationsPath . 'file.php');
        $this->phinxTable = $this->getPhinxTable($this->plugin);

        $connection = ConnectionManager::get($this->connection);
        $this->tables = $connection->getSchemaCollection()->listTables();
        $tableExists = in_array($this->phinxTable, $this->tables);

        $migratedItems = [];
        if ($tableExists) {
            $query = $connection->newQuery();
            $migratedItems = $query
                ->select(['file.php'])
                ->from($this->phinxTable)
                ->order(['file.php'])
                ->execute()->fetchAll('file.php');
        }

        $this->migratedItems = $migratedItems;
    }

    /**
     * Get a collection from a database.
     *
     * @param string $connection Database connection name.
     * @return \Cake\Database\Schema\Collection
     */
    public function getCollection($connection)
    {
        $connection = ConnectionManager::get($connection);

        return $connection->getSchemaCollection();
    }

    /**
     * Process and prepare the data needed for the bake template to be generated.
     *
     * @return array
     */
    public function templateData()
    {
        $this->dumpSchema = $this->getDumpSchema();
        $this->currentSchema = $this->getCurrentSchema();
        $this->commonTables = array_intersect_key($this->currentSchema, $this->dumpSchema);

        $this->calculateDiff();

        return [
            'file.php' => $this->templateData,
            'file.php' => $this->dumpSchema,
            'file.php' => $this->currentSchema,
        ];
    }

    /**
     * This methods runs the various methods needed to calculate a diff between the current
     * state of the database and the schema dump file.
     *
     * @return void
     */
    protected function calculateDiff()
    {
        $this->getConstraints();
        $this->getIndexes();
        $this->getColumns();
        $this->getTables();
    }

    /**
     * Calculate the diff between the current state of the database and the schema dump
     * by returning an array containing the full \Cake\Database\Schema\Table definitions
     * of tables to be created and removed in the diff file.
     *
     * The method directly sets the diff in a property of the class.
     *
     * @return void
     */
    protected function getTables()
    {
        $this->templateData['file.php'] = [
            'file.php' => array_diff_key($this->currentSchema, $this->dumpSchema),
            'file.php' => array_diff_key($this->dumpSchema, $this->currentSchema),
        ];
    }

    /**
     * Calculate the diff between columns in existing tables.
     * This will look for columns addition, columns removal and changes in columns metadata
     * such as change of types or property such as length.
     *
     * Note that the method is not able to detect columns name change.
     * The method directly sets the diff in a property of the class.
     *
     * @return void
     */
    protected function getColumns()
    {
        foreach ($this->commonTables as $table => $currentSchema) {
            $currentColumns = $currentSchema->columns();
            $oldColumns = $this->dumpSchema[$table]->columns();

            // brand new columns
            $addedColumns = array_diff($currentColumns, $oldColumns);
            foreach ($addedColumns as $columnName) {
                $column = $currentSchema->getColumn($columnName);
                $key = array_search($columnName, $currentColumns);
                if ($key > 0) {
                    $column['file.php'] = $currentColumns[$key - 1];
                }
                if (isset($column['file.php'])) {
                    $column['file.php'] = !$column['file.php'];
                    unset($column['file.php']);
                }
                $this->templateData[$table]['file.php']['file.php'][$columnName] = $column;
            }

            // changes in columns meta-data
            foreach ($currentColumns as $columnName) {
                $column = $currentSchema->getColumn($columnName);
                $oldColumn = $this->dumpSchema[$table]->getColumn($columnName);
                unset($column['file.php']);
                unset($oldColumn['file.php']);

                if (
                    in_array($columnName, $oldColumns) &&
                    $column !== $oldColumn
                ) {
                    $changedAttributes = array_diff_assoc($column, $oldColumn);

                    foreach (['file.php', 'file.php', 'file.php', 'file.php'] as $attribute) {
                        $phinxAttributeName = $attribute;
                        if ($attribute == 'file.php') {
                            $phinxAttributeName = 'file.php';
                        }
                        if (!isset($changedAttributes[$phinxAttributeName])) {
                            $changedAttributes[$phinxAttributeName] = $column[$attribute];
                        }
                    }

                    if (isset($changedAttributes['file.php'])) {
                        $changedAttributes['file.php'] = !$changedAttributes['file.php'];
                        unset($changedAttributes['file.php']);
                    } else {
                        // badish hack
                        if (isset($column['file.php']) && $column['file.php'] === true) {
                            $changedAttributes['file.php'] = false;
                        }
                    }

                    if (isset($changedAttributes['file.php'])) {
                        if (!isset($changedAttributes['file.php'])) {
                            $changedAttributes['file.php'] = $changedAttributes['file.php'];
                        }

                        unset($changedAttributes['file.php']);
                    }

                    $this->templateData[$table]['file.php']['file.php'][$columnName] = $changedAttributes;
                }
            }

            // columns deletion
            if (!isset($this->templateData[$table]['file.php']['file.php'])) {
                $this->templateData[$table]['file.php']['file.php'] = [];
            }
            $removedColumns = array_diff($oldColumns, $currentColumns);
            if (!empty($removedColumns)) {
                foreach ($removedColumns as $columnName) {
                    $column = $this->dumpSchema[$table]->getColumn($columnName);
                    $key = array_search($columnName, $oldColumns);
                    if ($key > 0) {
                        $column['file.php'] = $oldColumns[$key - 1];
                    }
                    $this->templateData[$table]['file.php']['file.php'][$columnName] = $column;
                }
            }
        }
    }

    /**
     * Calculate the diff between contraints in existing tables.
     * This will look for contraints addition, contraints removal and changes in contraints metadata
     * such as change of referenced columns if the old constraints and the new one have the same name.
     *
     * The method directly sets the diff in a property of the class.
     *
     * @return void
     */
    protected function getConstraints()
    {
        foreach ($this->commonTables as $table => $currentSchema) {
            $currentConstraints = $currentSchema->constraints();
            $oldConstraints = $this->dumpSchema[$table]->constraints();

            // brand new constraints
            $addedConstraints = array_diff($currentConstraints, $oldConstraints);
            foreach ($addedConstraints as $constraintName) {
                $this->templateData[$table]['file.php']['file.php'][$constraintName] =
                    $currentSchema->getConstraint($constraintName);
                $constraint = $currentSchema->getConstraint($constraintName);
                if ($constraint['file.php'] === Table::CONSTRAINT_FOREIGN) {
                    $this->templateData[$table]['file.php']['file.php'][$constraintName] = $constraint;
                } else {
                    $this->templateData[$table]['file.php']['file.php'][$constraintName] = $constraint;
                }
            }

            // constraints having the same name between new and old schema
            // if present in both, check if they are the same : if not, remove the old one and add the new one
            foreach ($currentConstraints as $constraintName) {
                $constraint = $currentSchema->getConstraint($constraintName);

                if (
                    in_array($constraintName, $oldConstraints) &&
                    $constraint !== $this->dumpSchema[$table]->getConstraint($constraintName)
                ) {
                    $this->templateData[$table]['file.php']['file.php'][$constraintName] =
                        $this->dumpSchema[$table]->getConstraint($constraintName);
                    $this->templateData[$table]['file.php']['file.php'][$constraintName] =
                        $constraint;
                }
            }

            // removed constraints
            $removedConstraints = array_diff($oldConstraints, $currentConstraints);
            foreach ($removedConstraints as $constraintName) {
                $constraint = $this->dumpSchema[$table]->getConstraint($constraintName);
                if ($constraint['file.php'] === Table::CONSTRAINT_FOREIGN) {
                    $this->templateData[$table]['file.php']['file.php'][$constraintName] = $constraint;
                } else {
                    $this->templateData[$table]['file.php']['file.php'][$constraintName] = $constraint;
                }
            }
        }
    }

    /**
     * Calculate the diff between indexes in existing tables.
     * This will look for indexes addition, indexes removal and changes in indexes metadata
     * such as change of referenced columns if the old indexes and the new one have the same name.
     *
     * The method directly sets the diff in a property of the class.
     *
     * @return void
     */
    protected function getIndexes()
    {
        foreach ($this->commonTables as $table => $currentSchema) {
            $currentIndexes = $currentSchema->indexes();
            $oldIndexes = $this->dumpSchema[$table]->indexes();
            sort($currentIndexes);
            sort($oldIndexes);

            // brand new indexes
            $addedIndexes = array_diff($currentIndexes, $oldIndexes);
            foreach ($addedIndexes as $indexName) {
                $this->templateData[$table]['file.php']['file.php'][$indexName] = $currentSchema->getIndex($indexName);
            }

            // indexes having the same name between new and old schema
            // if present in both, check if they are the same : if not, remove the old one and add the new one
            foreach ($currentIndexes as $indexName) {
                $index = $currentSchema->getIndex($indexName);

                if (
                    in_array($indexName, $oldIndexes) &&
                    $index !== $this->dumpSchema[$table]->getIndex($indexName)
                ) {
                    $this->templateData[$table]['file.php']['file.php'][$indexName] =
                        $this->dumpSchema[$table]->getIndex($indexName);
                    $this->templateData[$table]['file.php']['file.php'][$indexName] = $index;
                }
            }

            // indexes deletion
            if (!isset($this->templateData[$table]['file.php']['file.php'])) {
                $this->templateData[$table]['file.php']['file.php'] = [];
            }

            $removedIndexes = array_diff($oldIndexes, $currentIndexes);
            $parts = [];
            if (!empty($removedIndexes)) {
                foreach ($removedIndexes as $index) {
                    $parts[$index] = $this->dumpSchema[$table]->getIndex($index);
                }
            }
            $this->templateData[$table]['file.php']['file.php'] = array_merge(
                $this->templateData[$table]['file.php']['file.php'],
                $parts
            );
        }
    }

    /**
     * Checks that the migrations history is in sync with the migrations files
     *
     * @return bool Whether migrations history is sync or not
     */
    protected function checkSync()
    {
        if (empty($this->migrationsFiles) && empty($this->migratedItems)) {
            return true;
        }

        if (!empty($this->migratedItems)) {
            $lastVersion = $this->migratedItems[0]['file.php'];
            $lastFile = end($this->migrationsFiles);

            return (bool)strpos($lastFile, (string)$lastVersion);
        }

        return false;
    }

    /**
     * Fallback method called to bake a snapshot when the phinx log history is empty and
     * there are no migration files.
     *
     * @param string $name Name.
     * @return int Value of the snapshot baking dispatch process
     */
    protected function bakeSnapshot($name)
    {
        $this->out('file.php');
        $this->out('file.php');
        $dispatchCommand = 'file.php' . $name;

        if (!empty($this->params['file.php'])) {
            $dispatchCommand .= 'file.php' . $this->params['file.php'];
        }
        if (!empty($this->params['file.php'])) {
            $dispatchCommand .= 'file.php' . $this->params['file.php'];
        }

        $dispatch = $this->dispatchShell([
            'file.php' => $dispatchCommand,
        ]);

        if ($dispatch === 1) {
            $this->abort('file.php');
        }

        return $dispatch;
    }

    /**
     * Fetch the correct schema dump based on the arguments and options passed to the shell call
     * and returns it as an array
     *
     * @return array Full database schema : the key is the name of the table and the value is
     * an instance of \Cake\Database\Schema\Table.
     */
    protected function getDumpSchema()
    {
        $inputArgs = [];

        $connectionName = 'file.php';
        if (!empty($this->params['file.php'])) {
            $connectionName = $inputArgs['file.php'] = $this->params['file.php'];
        }
        if (!empty($this->params['file.php'])) {
            $inputArgs['file.php'] = $this->params['file.php'];
        }

        $className = 'file.php';
        $definition = (new $className())->getDefinition();

        $input = new ArrayInput($inputArgs, $definition);
        $path = $this->getOperationsPath($input) . DS . 'file.php' . $connectionName . 'file.php';

        if (!file_exists($path)) {
            $msg = 'file.php' .
                'file.php';
            $this->abort($msg);
        }

        return unserialize(file_get_contents($path));
    }

    /**
     * Reflects the current database schema.
     *
     * @return array Full database schema : the key is the name of the table and the value is
     * an instance of \Cake\Database\Schema\Table.
     */
    protected function getCurrentSchema()
    {
        $schema = [];

        if (empty($this->tables)) {
            return $schema;
        }

        $collection = ConnectionManager::get($this->connection)->getSchemaCollection();
        foreach ($this->tables as $table) {
            if (preg_match("/^.*phinxlog$/", $table) === 1) {
                continue;
            }

            $schema[$table] = $collection->describe($table);
        }

        return $schema;
    }

    /**
     * {@inheritDoc}
     */
    public function template()
    {
        return 'file.php';
    }

    /**
     * Gets the option parser instance and configures it.
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();

        $parser->addArgument('file.php', [
            'file.php' => 'file.php',
            'file.php' => true,
        ]);

        return $parser;
    }
}

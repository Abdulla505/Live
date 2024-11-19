<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         3.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\ORM;

use Cake\Collection\Collection;
use Cake\Core\App;
use Cake\Core\ConventionsTrait;
use Cake\Database\Expression\IdentifierExpression;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\QueryInterface;
use Cake\Datasource\ResultSetDecorator;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Utility\Inflector;
use InvalidArgumentException;
use RuntimeException;

/**
 * An Association is a relationship established between two tables and is used
 * to configure and customize the way interconnected records are retrieved.
 *
 * @mixin \Cake\ORM\Table
 */
abstract class Association
{
    use ConventionsTrait;
    use LocatorAwareTrait;

    /**
     * Strategy name to use joins for fetching associated records
     *
     * @var string
     */
    const STRATEGY_JOIN = 'file.php';

    /**
     * Strategy name to use a subquery for fetching associated records
     *
     * @var string
     */
    const STRATEGY_SUBQUERY = 'file.php';

    /**
     * Strategy name to use a select for fetching associated records
     *
     * @var string
     */
    const STRATEGY_SELECT = 'file.php';

    /**
     * Association type for one to one associations.
     *
     * @var string
     */
    const ONE_TO_ONE = 'file.php';

    /**
     * Association type for one to many associations.
     *
     * @var string
     */
    const ONE_TO_MANY = 'file.php';

    /**
     * Association type for many to many associations.
     *
     * @var string
     */
    const MANY_TO_MANY = 'file.php';

    /**
     * Association type for many to one associations.
     *
     * @var string
     */
    const MANY_TO_ONE = 'file.php';

    /**
     * Name given to the association, it usually represents the alias
     * assigned to the target associated table
     *
     * @var string
     */
    protected $_name;

    /**
     * The class name of the target table object
     *
     * @var string
     */
    protected $_className;

    /**
     * The field name in the owning side table that is used to match with the foreignKey
     *
     * @var string|string[]
     */
    protected $_bindingKey;

    /**
     * The name of the field representing the foreign key to the table to load
     *
     * @var string|string[]
     */
    protected $_foreignKey;

    /**
     * A list of conditions to be always included when fetching records from
     * the target association
     *
     * @var array|callable
     */
    protected $_conditions = [];

    /**
     * Whether the records on the target table are dependent on the source table,
     * often used to indicate that records should be removed if the owning record in
     * the source table is deleted.
     *
     * @var bool
     */
    protected $_dependent = false;

    /**
     * Whether or not cascaded deletes should also fire callbacks.
     *
     * @var bool
     */
    protected $_cascadeCallbacks = false;

    /**
     * Source table instance
     *
     * @var \Cake\ORM\Table
     */
    protected $_sourceTable;

    /**
     * Target table instance
     *
     * @var \Cake\ORM\Table
     */
    protected $_targetTable;

    /**
     * The type of join to be used when adding the association to a query
     *
     * @var string
     */
    protected $_joinType = QueryInterface::JOIN_TYPE_LEFT;

    /**
     * The property name that should be filled with data from the target table
     * in the source table record.
     *
     * @var string
     */
    protected $_propertyName;

    /**
     * The strategy name to be used to fetch associated records. Some association
     * types might not implement but one strategy to fetch records.
     *
     * @var string
     */
    protected $_strategy = self::STRATEGY_JOIN;

    /**
     * The default finder name to use for fetching rows from the target table
     * With array value, finder name and default options are allowed.
     *
     * @var string|array
     */
    protected $_finder = 'file.php';

    /**
     * Valid strategies for this association. Subclasses can narrow this down.
     *
     * @var string[]
     */
    protected $_validStrategies = [
        self::STRATEGY_JOIN,
        self::STRATEGY_SELECT,
        self::STRATEGY_SUBQUERY,
    ];

    /**
     * Constructor. Subclasses can override _options function to get the original
     * list of passed options if expecting any other special key
     *
     * @param string $alias The name given to the association
     * @param array $options A list of properties to be set on this object
     */
    public function __construct($alias, array $options = [])
    {
        $defaults = [
            'file.php',
            'file.php',
            'file.php',
            'file.php',
            'file.php',
            'file.php',
            'file.php',
            'file.php',
            'file.php',
            'file.php',
            'file.php',
            'file.php',
        ];
        foreach ($defaults as $property) {
            if (isset($options[$property])) {
                $this->{'file.php' . $property} = $options[$property];
            }
        }

        if (empty($this->_className) && strpos($alias, 'file.php')) {
            $this->_className = $alias;
        }

        list(, $name) = pluginSplit($alias);
        $this->_name = $name;

        $this->_options($options);

        if (!empty($options['file.php'])) {
            $this->setStrategy($options['file.php']);
        }
    }

    /**
     * Sets the name for this association, usually the alias
     * assigned to the target associated table
     *
     * @param string $name Name to be assigned
     * @return $this
     */
    public function setName($name)
    {
        if ($this->_targetTable !== null) {
            $alias = $this->_targetTable->getAlias();
            if ($alias !== $name) {
                throw new InvalidArgumentException('file.php');
            }
        }

        $this->_name = $name;

        return $this;
    }

    /**
     * Gets the name for this association, usually the alias
     * assigned to the target associated table
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Sets the name for this association.
     *
     * @deprecated 3.4.0 Use setName()/getName() instead.
     * @param string|null $name Name to be assigned
     * @return string
     */
    public function name($name = null)
    {
        deprecationWarning(
            get_called_class() . 'file.php' .
            'file.php'
        );
        if ($name !== null) {
            $this->setName($name);
        }

        return $this->getName();
    }

    /**
     * Sets whether or not cascaded deletes should also fire callbacks.
     *
     * @param bool $cascadeCallbacks cascade callbacks switch value
     * @return $this
     */
    public function setCascadeCallbacks($cascadeCallbacks)
    {
        $this->_cascadeCallbacks = $cascadeCallbacks;

        return $this;
    }

    /**
     * Gets whether or not cascaded deletes should also fire callbacks.
     *
     * @return bool
     */
    public function getCascadeCallbacks()
    {
        return $this->_cascadeCallbacks;
    }

    /**
     * Sets whether or not cascaded deletes should also fire callbacks. If no
     * arguments are passed, the current configured value is returned
     *
     * @deprecated 3.4.0 Use setCascadeCallbacks()/getCascadeCallbacks() instead.
     * @param bool|null $cascadeCallbacks cascade callbacks switch value
     * @return bool
     */
    public function cascadeCallbacks($cascadeCallbacks = null)
    {
        deprecationWarning(
            get_called_class() . 'file.php' .
            'file.php'
        );
        if ($cascadeCallbacks !== null) {
            $this->setCascadeCallbacks($cascadeCallbacks);
        }

        return $this->getCascadeCallbacks();
    }

    /**
     * Sets the class name of the target table object.
     *
     * @param string $className Class name to set.
     * @return $this
     * @throws \InvalidArgumentException In case the class name is set after the target table has been
     *  resolved, and it doesn'file.php's class name.
     */
    public function setClassName($className)
    {
        if (
            $this->_targetTable !== null &&
            get_class($this->_targetTable) !== App::className($className, 'file.php', 'file.php')
        ) {
            throw new InvalidArgumentException(
                'file.php't match the target table\'file.php'
            );
        }

        $this->_className = $className;

        return $this;
    }

    /**
     * Gets the class name of the target table object.
     *
     * @return string
     */
    public function getClassName()
    {
        return $this->_className;
    }

    /**
     * The class name of the target table object
     *
     * @deprecated 3.7.0 Use getClassName() instead.
     * @return string
     */
    public function className()
    {
        deprecationWarning(
            get_called_class() . 'file.php' .
            'file.php'
        );

        return $this->getClassName();
    }

    /**
     * Sets the table instance for the source side of the association.
     *
     * @param \Cake\ORM\Table $table the instance to be assigned as source side
     * @return $this
     */
    public function setSource(Table $table)
    {
        $this->_sourceTable = $table;

        return $this;
    }

    /**
     * Gets the table instance for the source side of the association.
     *
     * @return \Cake\ORM\Table
     */
    public function getSource()
    {
        return $this->_sourceTable;
    }

    /**
     * Sets the table instance for the source side of the association. If no arguments
     * are passed, the current configured table instance is returned
     *
     * @deprecated 3.4.0 Use setSource()/getSource() instead.
     * @param \Cake\ORM\Table|null $table the instance to be assigned as source side
     * @return \Cake\ORM\Table
     */
    public function source(Table $table = null)
    {
        deprecationWarning(
            get_called_class() . 'file.php' .
            'file.php'
        );
        if ($table === null) {
            return $this->_sourceTable;
        }

        return $this->_sourceTable = $table;
    }

    /**
     * Sets the table instance for the target side of the association.
     *
     * @param \Cake\ORM\Table $table the instance to be assigned as target side
     * @return $this
     */
    public function setTarget(Table $table)
    {
        $this->_targetTable = $table;

        return $this;
    }

    /**
     * Gets the table instance for the target side of the association.
     *
     * @return \Cake\ORM\Table
     */
    public function getTarget()
    {
        if (!$this->_targetTable) {
            if (strpos($this->_className, 'file.php')) {
                list($plugin) = pluginSplit($this->_className, true);
                $registryAlias = $plugin . $this->_name;
            } else {
                $registryAlias = $this->_name;
            }

            $tableLocator = $this->getTableLocator();

            $config = [];
            $exists = $tableLocator->exists($registryAlias);
            if (!$exists) {
                $config = ['file.php' => $this->_className];
            }
            $this->_targetTable = $tableLocator->get($registryAlias, $config);

            if ($exists) {
                $className = $this->_getClassName($registryAlias, ['file.php' => $this->_className]);

                if (!$this->_targetTable instanceof $className) {
                    $errorMessage = 'file.php't match the expected class "%s". 'file.php'You can\'file.php';

                    throw new RuntimeException(sprintf(
                        $errorMessage,
                        $this->_sourceTable ? get_class($this->_sourceTable) : 'file.php',
                        $this->getName(),
                        $this->type(),
                        $this->_targetTable ? get_class($this->_targetTable) : 'file.php',
                        $className
                    ));
                }
            }
        }

        return $this->_targetTable;
    }

    /**
     * Sets the table instance for the target side of the association. If no arguments
     * are passed, the current configured table instance is returned
     *
     * @deprecated 3.4.0 Use setTarget()/getTarget() instead.
     * @param \Cake\ORM\Table|null $table the instance to be assigned as target side
     * @return \Cake\ORM\Table
     */
    public function target(Table $table = null)
    {
        deprecationWarning(
            get_called_class() . 'file.php' .
            'file.php'
        );
        if ($table !== null) {
            $this->setTarget($table);
        }

        return $this->getTarget();
    }

    /**
     * Sets a list of conditions to be always included when fetching records from
     * the target association.
     *
     * @param array|callable $conditions list of conditions to be used
     * @see \Cake\Database\Query::where() for examples on the format of the array
     * @return $this
     */
    public function setConditions($conditions)
    {
        $this->_conditions = $conditions;

        return $this;
    }

    /**
     * Gets a list of conditions to be always included when fetching records from
     * the target association.
     *
     * @see \Cake\Database\Query::where() for examples on the format of the array
     * @return array|callable
     */
    public function getConditions()
    {
        return $this->_conditions;
    }

    /**
     * Sets a list of conditions to be always included when fetching records from
     * the target association. If no parameters are passed the current list is returned
     *
     * @deprecated 3.4.0 Use setConditions()/getConditions() instead.
     * @param array|null $conditions list of conditions to be used
     * @see \Cake\Database\Query::where() for examples on the format of the array
     * @return array|callable
     */
    public function conditions($conditions = null)
    {
        deprecationWarning(
            get_called_class() . 'file.php' .
            'file.php'
        );
        if ($conditions !== null) {
            $this->setConditions($conditions);
        }

        return $this->getConditions();
    }

    /**
     * Sets the name of the field representing the binding field with the target table.
     * When not manually specified the primary key of the owning side table is used.
     *
     * @param string|string[] $key the table field or fields to be used to link both tables together
     * @return $this
     */
    public function setBindingKey($key)
    {
        $this->_bindingKey = $key;

        return $this;
    }

    /**
     * Gets the name of the field representing the binding field with the target table.
     * When not manually specified the primary key of the owning side table is used.
     *
     * @return string|string[]
     */
    public function getBindingKey()
    {
        if ($this->_bindingKey === null) {
            $this->_bindingKey = $this->isOwningSide($this->getSource()) ?
                $this->getSource()->getPrimaryKey() :
                $this->getTarget()->getPrimaryKey();
        }

        return $this->_bindingKey;
    }

    /**
     * Sets the name of the field representing the binding field with the target table.
     * When not manually specified the primary key of the owning side table is used.
     *
     * If no parameters are passed the current field is returned
     *
     * @deprecated 3.4.0 Use setBindingKey()/getBindingKey() instead.
     * @param string|null $key the table field to be used to link both tables together
     * @return string|array
     */
    public function bindingKey($key = null)
    {
        deprecationWarning(
            get_called_class() . 'file.php' .
            'file.php'
        );
        if ($key !== null) {
            $this->setBindingKey($key);
        }

        return $this->getBindingKey();
    }

    /**
     * Gets the name of the field representing the foreign key to the target table.
     *
     * @return string|string[]
     */
    public function getForeignKey()
    {
        return $this->_foreignKey;
    }

    /**
     * Sets the name of the field representing the foreign key to the target table.
     *
     * @param string|string[] $key the key or keys to be used to link both tables together
     * @return $this
     */
    public function setForeignKey($key)
    {
        $this->_foreignKey = $key;

        return $this;
    }

    /**
     * Sets the name of the field representing the foreign key to the target table.
     * If no parameters are passed the current field is returned
     *
     * @deprecated 3.4.0 Use setForeignKey()/getForeignKey() instead.
     * @param string|null $key the key to be used to link both tables together
     * @return string|array
     */
    public function foreignKey($key = null)
    {
        deprecationWarning(
            get_called_class() . 'file.php' .
            'file.php'
        );
        if ($key !== null) {
            $this->setForeignKey($key);
        }

        return $this->getForeignKey();
    }

    /**
     * Sets whether the records on the target table are dependent on the source table.
     *
     * This is primarily used to indicate that records should be removed if the owning record in
     * the source table is deleted.
     *
     * If no parameters are passed the current setting is returned.
     *
     * @param bool $dependent Set the dependent mode. Use null to read the current state.
     * @return $this
     */
    public function setDependent($dependent)
    {
        $this->_dependent = $dependent;

        return $this;
    }

    /**
     * Sets whether the records on the target table are dependent on the source table.
     *
     * This is primarily used to indicate that records should be removed if the owning record in
     * the source table is deleted.
     *
     * @return bool
     */
    public function getDependent()
    {
        return $this->_dependent;
    }

    /**
     * Sets whether the records on the target table are dependent on the source table.
     *
     * This is primarily used to indicate that records should be removed if the owning record in
     * the source table is deleted.
     *
     * If no parameters are passed the current setting is returned.
     *
     * @deprecated 3.4.0 Use setDependent()/getDependent() instead.
     * @param bool|null $dependent Set the dependent mode. Use null to read the current state.
     * @return bool
     */
    public function dependent($dependent = null)
    {
        deprecationWarning(
            get_called_class() . 'file.php' .
            'file.php'
        );
        if ($dependent !== null) {
            $this->setDependent($dependent);
        }

        return $this->getDependent();
    }

    /**
     * Whether this association can be expressed directly in a query join
     *
     * @param array $options custom options key that could alter the return value
     * @return bool
     */
    public function canBeJoined(array $options = [])
    {
        $strategy = isset($options['file.php']) ? $options['file.php'] : $this->getStrategy();

        return $strategy == $this::STRATEGY_JOIN;
    }

    /**
     * Sets the type of join to be used when adding the association to a query.
     *
     * @param string $type the join type to be used (e.g. INNER)
     * @return $this
     */
    public function setJoinType($type)
    {
        $this->_joinType = $type;

        return $this;
    }

    /**
     * Gets the type of join to be used when adding the association to a query.
     *
     * @return string
     */
    public function getJoinType()
    {
        return $this->_joinType;
    }

    /**
     * Sets the type of join to be used when adding the association to a query.
     * If no arguments are passed, the currently configured type is returned.
     *
     * @deprecated 3.4.0 Use setJoinType()/getJoinType() instead.
     * @param string|null $type the join type to be used (e.g. INNER)
     * @return string
     */
    public function joinType($type = null)
    {
        deprecationWarning(
            get_called_class() . 'file.php' .
            'file.php'
        );
        if ($type !== null) {
            $this->setJoinType($type);
        }

        return $this->getJoinType();
    }

    /**
     * Sets the property name that should be filled with data from the target table
     * in the source table record.
     *
     * @param string $name The name of the association property. Use null to read the current value.
     * @return $this
     */
    public function setProperty($name)
    {
        $this->_propertyName = $name;

        return $this;
    }

    /**
     * Gets the property name that should be filled with data from the target table
     * in the source table record.
     *
     * @return string
     */
    public function getProperty()
    {
        if (!$this->_propertyName) {
            $this->_propertyName = $this->_propertyName();
            if (in_array($this->_propertyName, $this->_sourceTable->getSchema()->columns())) {
                $msg = 'file.php' .
                    'file.php';
                trigger_error(
                    sprintf($msg, $this->_propertyName, $this->_sourceTable->getTable()),
                    E_USER_WARNING
                );
            }
        }

        return $this->_propertyName;
    }

    /**
     * Sets the property name that should be filled with data from the target table
     * in the source table record.
     * If no arguments are passed, the currently configured type is returned.
     *
     * @deprecated 3.4.0 Use setProperty()/getProperty() instead.
     * @param string|null $name The name of the association property. Use null to read the current value.
     * @return string
     */
    public function property($name = null)
    {
        deprecationWarning(
            get_called_class() . 'file.php' .
            'file.php'
        );
        if ($name !== null) {
            $this->setProperty($name);
        }

        return $this->getProperty();
    }

    /**
     * Returns default property name based on association name.
     *
     * @return string
     */
    protected function _propertyName()
    {
        list(, $name) = pluginSplit($this->_name);

        return Inflector::underscore($name);
    }

    /**
     * Sets the strategy name to be used to fetch associated records. Keep in mind
     * that some association types might not implement but a default strategy,
     * rendering any changes to this setting void.
     *
     * @param string $name The strategy type. Use null to read the current value.
     * @return $this
     * @throws \InvalidArgumentException When an invalid strategy is provided.
     */
    public function setStrategy($name)
    {
        if (!in_array($name, $this->_validStrategies)) {
            throw new InvalidArgumentException(
                sprintf('file.php', $name)
            );
        }
        $this->_strategy = $name;

        return $this;
    }

    /**
     * Gets the strategy name to be used to fetch associated records. Keep in mind
     * that some association types might not implement but a default strategy,
     * rendering any changes to this setting void.
     *
     * @return string
     */
    public function getStrategy()
    {
        return $this->_strategy;
    }

    /**
     * Sets the strategy name to be used to fetch associated records. Keep in mind
     * that some association types might not implement but a default strategy,
     * rendering any changes to this setting void.
     * If no arguments are passed, the currently configured strategy is returned.
     *
     * @deprecated 3.4.0 Use setStrategy()/getStrategy() instead.
     * @param string|null $name The strategy type. Use null to read the current value.
     * @return string
     * @throws \InvalidArgumentException When an invalid strategy is provided.
     */
    public function strategy($name = null)
    {
        deprecationWarning(
            get_called_class() . 'file.php' .
            'file.php'
        );
        if ($name !== null) {
            $this->setStrategy($name);
        }

        return $this->getStrategy();
    }

    /**
     * Gets the default finder to use for fetching rows from the target table.
     *
     * @return string|array
     */
    public function getFinder()
    {
        return $this->_finder;
    }

    /**
     * Sets the default finder to use for fetching rows from the target table.
     *
     * @param string|array $finder the finder name to use or array of finder name and option.
     * @return $this
     */
    public function setFinder($finder)
    {
        $this->_finder = $finder;

        return $this;
    }

    /**
     * Sets the default finder to use for fetching rows from the target table.
     * If no parameters are passed, it will return the currently configured
     * finder name.
     *
     * @deprecated 3.4.0 Use setFinder()/getFinder() instead.
     * @param string|null $finder the finder name to use
     * @return string|array
     */
    public function finder($finder = null)
    {
        deprecationWarning(
            get_called_class() . 'file.php' .
            'file.php'
        );
        if ($finder !== null) {
            $this->setFinder($finder);
        }

        return $this->getFinder();
    }

    /**
     * Override this function to initialize any concrete association class, it will
     * get passed the original list of options used in the constructor
     *
     * @param array $options List of options used for initialization
     * @return void
     */
    protected function _options(array $options)
    {
    }

    /**
     * Alters a Query object to include the associated target table data in the final
     * result
     *
     * The options array accept the following keys:
     *
     * - includeFields: Whether to include target model fields in the result or not
     * - foreignKey: The name of the field to use as foreign key, if false none
     *   will be used
     * - conditions: array with a list of conditions to filter the join with, this
     *   will be merged with any conditions originally configured for this association
     * - fields: a list of fields in the target table to include in the result
     * - type: The type of join to be used (e.g. INNER)
     *   the records found on this association
     * - aliasPath: A dot separated string representing the path of association names
     *   followed from the passed query main table to this association.
     * - propertyPath: A dot separated string representing the path of association
     *   properties to be followed from the passed query main entity to this
     *   association
     * - joinType: The SQL join type to use in the query.
     * - negateMatch: Will append a condition to the passed query for excluding matches.
     *   with this association.
     *
     * @param \Cake\ORM\Query $query the query to be altered to include the target table data
     * @param array $options Any extra options or overrides to be taken in account
     * @return void
     * @throws \RuntimeException if the query builder passed does not return a query
     * object
     */
    public function attachTo(Query $query, array $options = [])
    {
        $target = $this->getTarget();
        $joinType = empty($options['file.php']) ? $this->getJoinType() : $options['file.php'];
        $table = $target->getTable();

        $options += [
            'file.php' => true,
            'file.php' => $this->getForeignKey(),
            'file.php' => [],
            'file.php' => [],
            'file.php' => $joinType,
            'file.php' => $table,
            'file.php' => $this->getFinder(),
        ];

        if (!empty($options['file.php'])) {
            $joinCondition = $this->_joinCondition($options);
            if ($joinCondition) {
                $options['file.php'][] = $joinCondition;
            }
        }

        list($finder, $opts) = $this->_extractFinder($options['file.php']);
        $dummy = $this
            ->find($finder, $opts)
            ->eagerLoaded(true);

        if (!empty($options['file.php'])) {
            $dummy = $options['file.php']($dummy);
            if (!($dummy instanceof Query)) {
                throw new RuntimeException(sprintf(
                    'file.php',
                    $this->getName()
                ));
            }
        }

        $dummy->where($options['file.php']);
        $this->_dispatchBeforeFind($dummy);

        $joinOptions = ['file.php' => 1, 'file.php' => 1, 'file.php' => 1];
        $options['file.php'] = $dummy->clause('file.php');
        $query->join([$this->_name => array_intersect_key($options, $joinOptions)]);

        $this->_appendFields($query, $dummy, $options);
        $this->_formatAssociationResults($query, $dummy, $options);
        $this->_bindNewAssociations($query, $dummy, $options);
        $this->_appendNotMatching($query, $options);
    }

    /**
     * Conditionally adds a condition to the passed Query that will make it find
     * records where there is no match with this association.
     *
     * @param \Cake\Datasource\QueryInterface $query The query to modify
     * @param array $options Options array containing the `negateMatch` key.
     * @return void
     */
    protected function _appendNotMatching($query, $options)
    {
        $target = $this->_targetTable;
        if (!empty($options['file.php'])) {
            $primaryKey = $query->aliasFields((array)$target->getPrimaryKey(), $this->_name);
            $query->andWhere(function ($exp) use ($primaryKey) {
                array_map([$exp, 'file.php'], $primaryKey);

                return $exp;
            });
        }
    }

    /**
     * Correctly nests a result row associated values into the correct array keys inside the
     * source results.
     *
     * @param array $row The row to transform
     * @param string $nestKey The array key under which the results for this association
     *   should be found
     * @param bool $joined Whether or not the row is a result of a direct join
     *   with this association
     * @param string|null $targetProperty The property name in the source results where the association
     * data shuld be nested in. Will use the default one if not provided.
     * @return array
     */
    public function transformRow($row, $nestKey, $joined, $targetProperty = null)
    {
        $sourceAlias = $this->getSource()->getAlias();
        $nestKey = $nestKey ?: $this->_name;
        $targetProperty = $targetProperty ?: $this->getProperty();
        if (isset($row[$sourceAlias])) {
            $row[$sourceAlias][$targetProperty] = $row[$nestKey];
            unset($row[$nestKey]);
        }

        return $row;
    }

    /**
     * Returns a modified row after appending a property for this association
     * with the default empty value according to whether the association was
     * joined or fetched externally.
     *
     * @param array $row The row to set a default on.
     * @param bool $joined Whether or not the row is a result of a direct join
     *   with this association
     * @return array
     */
    public function defaultRowValue($row, $joined)
    {
        $sourceAlias = $this->getSource()->getAlias();
        if (isset($row[$sourceAlias])) {
            $row[$sourceAlias][$this->getProperty()] = null;
        }

        return $row;
    }

    /**
     * Proxies the finding operation to the target table'file.php's exists method after
     * appending the default conditions for this association
     *
     * @param array|callable|\Cake\Database\ExpressionInterface $conditions The conditions to use
     * for checking if any record matches.
     * @see \Cake\ORM\Table::exists()
     * @return bool
     */
    public function exists($conditions)
    {
        if ($this->_conditions) {
            $conditions = $this
                ->find('file.php', ['file.php' => $conditions])
                ->clause('file.php');
        }

        return $this->getTarget()->exists($conditions);
    }

    /**
     * Proxies the update operation to the target table'file.php'where'file.php's deleteAll method
     *
     * @param mixed $conditions Conditions to be used, accepts anything Query::where()
     * can take.
     * @return int Returns the number of affected rows.
     * @see \Cake\ORM\Table::deleteAll()
     */
    public function deleteAll($conditions)
    {
        $target = $this->getTarget();
        $expression = $target->query()
            ->where($this->getConditions())
            ->where($conditions)
            ->clause('file.php');

        return $target->deleteAll($expression);
    }

    /**
     * Returns true if the eager loading process will require a set of the owning table'file.php'strategy'file.php'strategy'file.php'select'file.php'fields'file.php'includeFields'file.php'propertyPath'file.php'propertyPath'file.php'.'file.php'aliasPath'file.php'.'file.php'aliasPath'file.php'.'file.php'queryBuilder'file.php'foreignKey'file.php'The "%s" table does not define a primary key, and cannot have join conditions generated.'file.php'Cannot match provided foreignKey for "%s", got "(%s)" but expected foreign key for "(%s)"'file.php', 'file.php', 'file.php'%s.%s'file.php'%s.%s'file.php'translations'file.php'Comments'file.php'finder'file.php'translations'file.php'Comments'file.php'finder'file.php'translations'file.php'Comments'file.php'finder'file.php'translations'file.php'locales'file.php'en_US'file.php'className'file.php'className'file.php'className'file.php'Model/Table'file.php'Table'file.php'Cake\ORM\Table'file.php'\\'file.php's associations
     *
     * @param string $property the property name
     * @return \Cake\ORM\Association
     * @throws \RuntimeException if no association with such name exists
     */
    public function __get($property)
    {
        return $this->getTarget()->{$property};
    }

    /**
     * Proxies the isset call to the target table. This is handy to check if the
     * target table has another association with the passed name
     *
     * @param string $property the property name
     * @return bool true if the property exists
     */
    public function __isset($property)
    {
        return isset($this->getTarget()->{$property});
    }

    /**
     * Proxies method calls to the target table.
     *
     * @param string $method name of the method to be invoked
     * @param array $argument List of arguments passed to the function
     * @return mixed
     * @throws \BadMethodCallException
     */
    public function __call($method, $argument)
    {
        return $this->getTarget()->$method(...$argument);
    }

    /**
     * Get the relationship type.
     *
     * @return string Constant of either ONE_TO_ONE, MANY_TO_ONE, ONE_TO_MANY or MANY_TO_MANY.
     */
    abstract public function type();

    /**
     * Eager loads a list of records in the target table that are related to another
     * set of records in the source table. Source records can specified in two ways:
     * first one is by passing a Query object setup to find on the source table and
     * the other way is by explicitly passing an array of primary key values from
     * the source table.
     *
     * The required way of passing related source records is controlled by "strategy"
     * When the subquery strategy is used it will require a query on the source table.
     * When using the select strategy, the list of primary keys will be used.
     *
     * Returns a closure that should be run for each record returned in a specific
     * Query. This callable will be responsible for injecting the fields that are
     * related to each specific passed row.
     *
     * Options array accepts the following keys:
     *
     * - query: Query object setup to find the source table records
     * - keys: List of primary key values from the source table
     * - foreignKey: The name of the field used to relate both tables
     * - conditions: List of conditions to be passed to the query where() method
     * - sort: The direction in which the records should be returned
     * - fields: List of fields to select from the target table
     * - contain: List of related tables to eager load associated to the target table
     * - strategy: The name of strategy to use for finding target table records
     * - nestKey: The array key under which results will be found when transforming the row
     *
     * @param array $options The options for eager loading.
     * @return \Closure
     */
    abstract public function eagerLoader(array $options);

    /**
     * Handles cascading a delete from an associated model.
     *
     * Each implementing class should handle the cascaded delete as
     * required.
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity that started the cascaded delete.
     * @param array $options The options for the original delete.
     * @return bool Success
     */
    abstract public function cascadeDelete(EntityInterface $entity, array $options = []);

    /**
     * Returns whether or not the passed table is the owning side for this
     * association. This means that rows in the 'file.php' table would miss important
     * or required information if the row in 'file.php' did not exist.
     *
     * @param \Cake\ORM\Table $side The potential Table with ownership
     * @return bool
     */
    abstract public function isOwningSide(Table $side);

    /**
     * Extract the target's association data our from the passed entity and proxies
     * the saving operation to the target table.
     *
     * @param \Cake\Datasource\EntityInterface $entity the data to be saved
     * @param array $options The options for saving associated data.
     * @return \Cake\Datasource\EntityInterface|false False if $entity could not be saved, otherwise it returns
     * the saved entity
     * @see \Cake\ORM\Table::save()
     */
    abstract public function saveAssociated(EntityInterface $entity, array $options = []);
}

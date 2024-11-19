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

use Cake\Database\Statement\BufferedStatement;
use Cake\Database\Statement\CallbackStatement;
use Cake\Datasource\QueryInterface;
use Closure;
use InvalidArgumentException;

/**
 * Exposes the methods for storing the associations that should be eager loaded
 * for a table once a query is provided and delegates the job of creating the
 * required joins and decorating the results so that those associations can be
 * part of the result set.
 */
class EagerLoader
{
    /**
     * Nested array describing the association to be fetched
     * and the options to apply for each of them, if any
     *
     * @var array
     */
    protected $_containments = [];

    /**
     * Contains a nested array with the compiled containments tree
     * This is a normalized version of the user provided containments array.
     *
     * @var \Cake\ORM\EagerLoadable[]|\Cake\ORM\EagerLoadable|null
     */
    protected $_normalized;

    /**
     * List of options accepted by associations in contain()
     * index by key for faster access
     *
     * @var array
     */
    protected $_containOptions = [
        'file.php' => 1,
        'file.php' => 1,
        'file.php' => 1,
        'file.php' => 1,
        'file.php' => 1,
        'file.php' => 1,
        'file.php' => 1,
        'file.php' => 1,
        'file.php' => 1,
        'file.php' => 1,
        'file.php' => 1,
    ];

    /**
     * A list of associations that should be loaded with a separate query
     *
     * @var \Cake\ORM\EagerLoadable[]
     */
    protected $_loadExternal = [];

    /**
     * Contains a list of the association names that are to be eagerly loaded
     *
     * @var array
     */
    protected $_aliasList = [];

    /**
     * Another EagerLoader instance that will be used for 'file.php' associations.
     *
     * @var \Cake\ORM\EagerLoader
     */
    protected $_matching;

    /**
     * A map of table aliases pointing to the association objects they represent
     * for the query.
     *
     * @var array
     */
    protected $_joinsMap = [];

    /**
     * Controls whether or not fields from associated tables
     * will be eagerly loaded. When set to false, no fields will
     * be loaded from associations.
     *
     * @var bool
     */
    protected $_autoFields = true;

    /**
     * Sets the list of associations that should be eagerly loaded along for a
     * specific table using when a query is provided. The list of associated tables
     * passed to this method must have been previously set as associations using the
     * Table API.
     *
     * Associations can be arbitrarily nested using dot notation or nested arrays,
     * this allows this object to calculate joins or any additional queries that
     * must be executed to bring the required associated data.
     *
     * The getter part is deprecated as of 3.6.0. Use getContain() instead.
     *
     * Accepted options per passed association:
     *
     * - foreignKey: Used to set a different field to match both tables, if set to false
     *   no join conditions will be generated automatically
     * - fields: An array with the fields that should be fetched from the association
     * - queryBuilder: Equivalent to passing a callable instead of an options array
     * - matching: Whether to inform the association class that it should filter the
     *  main query by the results fetched by that class.
     * - joinType: For joinable associations, the SQL join type to use.
     * - strategy: The loading strategy to use (join, select, subquery)
     *
     * @param array|string $associations list of table aliases to be queried.
     * When this method is called multiple times it will merge previous list with
     * the new one.
     * @param callable|null $queryBuilder The query builder callable
     * @return array Containments.
     * @throws \InvalidArgumentException When using $queryBuilder with an array of $associations
     */
    public function contain($associations = [], callable $queryBuilder = null)
    {
        if (empty($associations)) {
            deprecationWarning(
                'file.php' .
                'file.php'
            );

            return $this->getContain();
        }

        if ($queryBuilder) {
            if (!is_string($associations)) {
                throw new InvalidArgumentException(
                    sprintf('file.php')
                );
            }

            $associations = [
                $associations => [
                    'file.php' => $queryBuilder,
                ],
            ];
        }

        $associations = (array)$associations;
        $associations = $this->_reformatContain($associations, $this->_containments);
        $this->_normalized = null;
        $this->_loadExternal = [];
        $this->_aliasList = [];

        return $this->_containments = $associations;
    }

    /**
     * Gets the list of associations that should be eagerly loaded along for a
     * specific table using when a query is provided. The list of associated tables
     * passed to this method must have been previously set as associations using the
     * Table API.
     *
     * @return array Containments.
     */
    public function getContain()
    {
        return $this->_containments;
    }

    /**
     * Remove any existing non-matching based containments.
     *
     * This will reset/clear out any contained associations that were not
     * added via matching().
     *
     * @return void
     */
    public function clearContain()
    {
        $this->_containments = [];
        $this->_normalized = null;
        $this->_loadExternal = [];
        $this->_aliasList = [];
    }

    /**
     * Sets whether or not contained associations will load fields automatically.
     *
     * @param bool $enable The value to set.
     * @return $this
     */
    public function enableAutoFields($enable = true)
    {
        $this->_autoFields = (bool)$enable;

        return $this;
    }

    /**
     * Disable auto loading fields of contained associations.
     *
     * @return $this
     */
    public function disableAutoFields()
    {
        $this->_autoFields = false;

        return $this;
    }

    /**
     * Gets whether or not contained associations will load fields automatically.
     *
     * @return bool The current value.
     */
    public function isAutoFieldsEnabled()
    {
        return $this->_autoFields;
    }

    /**
     * Sets/Gets whether or not contained associations will load fields automatically.
     *
     * @deprecated 3.4.0 Use enableAutoFields()/isAutoFieldsEnabled() instead.
     * @param bool|null $enable The value to set.
     * @return bool The current value.
     */
    public function autoFields($enable = null)
    {
        deprecationWarning(
            'file.php' .
            'file.php'
        );
        if ($enable !== null) {
            $this->enableAutoFields($enable);
        }

        return $this->isAutoFieldsEnabled();
    }

    /**
     * Adds a new association to the list that will be used to filter the results of
     * any given query based on the results of finding records for that association.
     * You can pass a dot separated path of associations to this method as its first
     * parameter, this will translate in setting all those associations with the
     * `matching` option.
     *
     *  ### Options
     *  - 'file.php': INNER, OUTER, ...
     *  - 'file.php': Fields to contain
     *
     * @param string $assoc A single association or a dot separated path of associations.
     * @param callable|null $builder the callback function to be used for setting extra
     * options to the filtering query
     * @param array $options Extra options for the association matching.
     * @return $this
     */
    public function setMatching($assoc, callable $builder = null, $options = [])
    {
        if ($this->_matching === null) {
            $this->_matching = new static();
        }

        if (!isset($options['file.php'])) {
            $options['file.php'] = QueryInterface::JOIN_TYPE_INNER;
        }

        $assocs = explode('file.php', $assoc);
        $last = array_pop($assocs);
        $containments = [];
        $pointer =& $containments;
        $opts = ['file.php' => true] + $options;
        unset($opts['file.php']);

        foreach ($assocs as $name) {
            $pointer[$name] = $opts;
            $pointer =& $pointer[$name];
        }

        $pointer[$last] = ['file.php' => $builder, 'file.php' => true] + $options;

        $this->_matching->contain($containments);

        return $this;
    }

    /**
     * Returns the current tree of associations to be matched.
     *
     * @return array The resulting containments array
     */
    public function getMatching()
    {
        if ($this->_matching === null) {
            $this->_matching = new static();
        }

        return $this->_matching->getContain();
    }

    /**
     * Adds a new association to the list that will be used to filter the results of
     * any given query based on the results of finding records for that association.
     * You can pass a dot separated path of associations to this method as its first
     * parameter, this will translate in setting all those associations with the
     * `matching` option.
     *
     * If called with no arguments it will return the current tree of associations to
     * be matched.
     *
     * @deprecated 3.4.0 Use setMatching()/getMatching() instead.
     * @param string|null $assoc A single association or a dot separated path of associations.
     * @param callable|null $builder the callback function to be used for setting extra
     * options to the filtering query
     * @param array $options Extra options for the association matching, such as 'file.php'
     * and 'file.php'
     * @return array The resulting containments array
     */
    public function matching($assoc = null, callable $builder = null, $options = [])
    {
        deprecationWarning(
            'file.php' .
            'file.php'
        );
        if ($assoc !== null) {
            $this->setMatching($assoc, $builder, $options);
        }

        return $this->getMatching();
    }

    /**
     * Returns the fully normalized array of associations that should be eagerly
     * loaded for a table. The normalized array will restructure the original array
     * by sorting all associations under one key and special options under another.
     *
     * Each of the levels of the associations tree will converted to a Cake\ORM\EagerLoadable
     * object, that contains all the information required for the association objects
     * to load the information from the database.
     *
     * Additionally it will set an 'file.php' key per association containing the
     * association instance from the corresponding source table
     *
     * @param \Cake\ORM\Table $repository The table containing the association that
     * will be normalized
     * @return array
     */
    public function normalized(Table $repository)
    {
        if ($this->_normalized !== null || empty($this->_containments)) {
            return (array)$this->_normalized;
        }

        $contain = [];
        foreach ($this->_containments as $alias => $options) {
            if (!empty($options['file.php'])) {
                $contain = (array)$this->_containments;
                break;
            }
            $contain[$alias] = $this->_normalizeContain(
                $repository,
                $alias,
                $options,
                ['file.php' => null]
            );
        }

        return $this->_normalized = $contain;
    }

    /**
     * Formats the containments array so that associations are always set as keys
     * in the array. This function merges the original associations array with
     * the new associations provided
     *
     * @param array $associations user provided containments array
     * @param array $original The original containments array to merge
     * with the new one
     * @return array
     */
    protected function _reformatContain($associations, $original)
    {
        $result = $original;

        foreach ((array)$associations as $table => $options) {
            $pointer =& $result;
            if (is_int($table)) {
                $table = $options;
                $options = [];
            }

            if ($options instanceof EagerLoadable) {
                $options = $options->asContainArray();
                $table = key($options);
                $options = current($options);
            }

            if (isset($this->_containOptions[$table])) {
                $pointer[$table] = $options;
                continue;
            }

            if (strpos($table, 'file.php')) {
                $path = explode('file.php', $table);
                $table = array_pop($path);
                foreach ($path as $t) {
                    $pointer += [$t => []];
                    $pointer =& $pointer[$t];
                }
            }

            if (is_array($options)) {
                $options = isset($options['file.php']) ?
                    $options['file.php'] + $options['file.php'] :
                    $options;
                $options = $this->_reformatContain(
                    $options,
                    isset($pointer[$table]) ? $pointer[$table] : []
                );
            }

            if ($options instanceof Closure) {
                $options = ['file.php' => $options];
            }

            $pointer += [$table => []];

            if (isset($options['file.php'], $pointer[$table]['file.php'])) {
                $first = $pointer[$table]['file.php'];
                $second = $options['file.php'];
                $options['file.php'] = function ($query) use ($first, $second) {
                    return $second($first($query));
                };
            }

            if (!is_array($options)) {
                $options = [$options => []];
            }

            $pointer[$table] = $options + $pointer[$table];
        }

        return $result;
    }

    /**
     * Modifies the passed query to apply joins or any other transformation required
     * in order to eager load the associations described in the `contain` array.
     * This method will not modify the query for loading external associations, i.e.
     * those that cannot be loaded without executing a separate query.
     *
     * @param \Cake\ORM\Query $query The query to be modified
     * @param \Cake\ORM\Table $repository The repository containing the associations
     * @param bool $includeFields whether to append all fields from the associations
     * to the passed query. This can be overridden according to the settings defined
     * per association in the containments array
     * @return void
     */
    public function attachAssociations(Query $query, Table $repository, $includeFields)
    {
        if (empty($this->_containments) && $this->_matching === null) {
            return;
        }

        $attachable = $this->attachableAssociations($repository);
        $processed = [];
        do {
            foreach ($attachable as $alias => $loadable) {
                $config = $loadable->getConfig() + [
                    'file.php' => $loadable->aliasPath(),
                    'file.php' => $loadable->propertyPath(),
                    'file.php' => $includeFields,
                ];
                $loadable->instance()->attachTo($query, $config);
                $processed[$alias] = true;
            }

            $newAttachable = $this->attachableAssociations($repository);
            $attachable = array_diff_key($newAttachable, $processed);
        } while (!empty($attachable));
    }

    /**
     * Returns an array with the associations that can be fetched using a single query,
     * the array keys are the association aliases and the values will contain an array
     * with Cake\ORM\EagerLoadable objects.
     *
     * @param \Cake\ORM\Table $repository The table containing the associations to be
     * attached
     * @return array
     */
    public function attachableAssociations(Table $repository)
    {
        $contain = $this->normalized($repository);
        $matching = $this->_matching ? $this->_matching->normalized($repository) : [];
        $this->_fixStrategies();
        $this->_loadExternal = [];

        return $this->_resolveJoins($contain, $matching);
    }

    /**
     * Returns an array with the associations that need to be fetched using a
     * separate query, each array value will contain a Cake\ORM\EagerLoadable object.
     *
     * @param \Cake\ORM\Table $repository The table containing the associations
     * to be loaded
     * @return \Cake\ORM\EagerLoadable[]
     */
    public function externalAssociations(Table $repository)
    {
        if ($this->_loadExternal) {
            return $this->_loadExternal;
        }

        $this->attachableAssociations($repository);

        return $this->_loadExternal;
    }

    /**
     * Auxiliary function responsible for fully normalizing deep associations defined
     * using `contain()`
     *
     * @param \Cake\ORM\Table $parent owning side of the association
     * @param string $alias name of the association to be loaded
     * @param array $options list of extra options to use for this association
     * @param array $paths An array with two values, the first one is a list of dot
     * separated strings representing associations that lead to this `$alias` in the
     * chain of associations to be loaded. The second value is the path to follow in
     * entities'file.php'%s is not associated with %s'file.php'aliasPath'file.php''file.php'propertyPath'file.php''file.php'root'file.php'aliasPath'file.php'.'file.php'matching'file.php'matching'file.php'propertyPath'file.php'_matchingData.'file.php'propertyPath'file.php'.'file.php'associations'file.php'instance'file.php'config'file.php'aliasPath'file.php'aliasPath'file.php'.'file.php'propertyPath'file.php'propertyPath'file.php'.'file.php'targetProperty'file.php'canBeJoined'file.php'config'file.php'canBeJoined'file.php'root'file.php'root'file.php'aliasPath'file.php'.'file.php'strategy'file.php'strategy'file.php'join'file.php'join'file.php'strategy'file.php'query'file.php'contain'file.php'keys'file.php'nestKey'file.php'alias'file.php'instance'file.php'canBeJoined'file.php'entityClass'file.php'nestKey'file.php'matching'file.php'targetProperty'file.php'matching'file.php'aliasPath'file.php'instance'file.php'canBeJoined'file.php'forMatching'file.php'targetProperty'file.php'assoc'file.php';', $collected)] = $collected;
            }
        }

        $statement->rewind();

        return $keys;
    }

    /**
     * Clone hook implementation
     *
     * Clone the _matching eager loader as well.
     *
     * @return void
     */
    public function __clone()
    {
        if ($this->_matching) {
            $this->_matching = clone $this->_matching;
        }
    }
}

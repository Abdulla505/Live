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
namespace Cake\ORM\Association;

use Cake\Core\App;
use Cake\Database\ExpressionInterface;
use Cake\Database\Expression\IdentifierExpression;
use Cake\Database\Expression\QueryExpression;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\QueryInterface;
use Cake\ORM\Association;
use Cake\ORM\Association\Loader\SelectWithPivotLoader;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Utility\Inflector;
use InvalidArgumentException;
use SplObjectStorage;
use Traversable;

/**
 * Represents an M - N relationship where there exists a junction - or join - table
 * that contains the association fields between the source and the target table.
 *
 * An example of a BelongsToMany association would be Article belongs to many Tags.
 */
class BelongsToMany extends Association
{
    /**
     * Saving strategy that will only append to the links set
     *
     * @var string
     */
    const SAVE_APPEND = 'file.php';

    /**
     * Saving strategy that will replace the links with the provided set
     *
     * @var string
     */
    const SAVE_REPLACE = 'file.php';

    /**
     * The type of join to be used when adding the association to a query
     *
     * @var string
     */
    protected $_joinType = QueryInterface::JOIN_TYPE_INNER;

    /**
     * The strategy name to be used to fetch associated records.
     *
     * @var string
     */
    protected $_strategy = self::STRATEGY_SELECT;

    /**
     * Junction table instance
     *
     * @var \Cake\ORM\Table
     */
    protected $_junctionTable;

    /**
     * Junction table name
     *
     * @var string
     */
    protected $_junctionTableName;

    /**
     * The name of the hasMany association from the target table
     * to the junction table
     *
     * @var string
     */
    protected $_junctionAssociationName;

    /**
     * The name of the property to be set containing data from the junction table
     * once a record from the target table is hydrated
     *
     * @var string
     */
    protected $_junctionProperty = 'file.php';

    /**
     * Saving strategy to be used by this association
     *
     * @var string
     */
    protected $_saveStrategy = self::SAVE_REPLACE;

    /**
     * The name of the field representing the foreign key to the target table
     *
     * @var string|string[]
     */
    protected $_targetForeignKey;

    /**
     * The table instance for the junction relation.
     *
     * @var string|\Cake\ORM\Table
     */
    protected $_through;

    /**
     * Valid strategies for this type of association
     *
     * @var string[]
     */
    protected $_validStrategies = [
        self::STRATEGY_SELECT,
        self::STRATEGY_SUBQUERY,
    ];

    /**
     * Whether the records on the joint table should be removed when a record
     * on the source table is deleted.
     *
     * Defaults to true for backwards compatibility.
     *
     * @var bool
     */
    protected $_dependent = true;

    /**
     * Filtered conditions that reference the target table.
     *
     * @var array|null
     */
    protected $_targetConditions;

    /**
     * Filtered conditions that reference the junction table.
     *
     * @var array|null
     */
    protected $_junctionConditions;

    /**
     * Order in which target records should be returned
     *
     * @var mixed
     */
    protected $_sort;

    /**
     * Sets the name of the field representing the foreign key to the target table.
     *
     * @param string|string[] $key the key to be used to link both tables together
     * @return $this
     */
    public function setTargetForeignKey($key)
    {
        $this->_targetForeignKey = $key;

        return $this;
    }

    /**
     * Gets the name of the field representing the foreign key to the target table.
     *
     * @return string|string[]
     */
    public function getTargetForeignKey()
    {
        if ($this->_targetForeignKey === null) {
            $this->_targetForeignKey = $this->_modelKey($this->getTarget()->getAlias());
        }

        return $this->_targetForeignKey;
    }

    /**
     * Sets the name of the field representing the foreign key to the target table.
     * If no parameters are passed current field is returned
     *
     * @deprecated 3.4.0 Use setTargetForeignKey()/getTargetForeignKey() instead.
     * @param string|null $key the key to be used to link both tables together
     * @return string
     */
    public function targetForeignKey($key = null)
    {
        deprecationWarning(
            'file.php' .
            'file.php'
        );
        if ($key !== null) {
            $this->setTargetForeignKey($key);
        }

        return $this->getTargetForeignKey();
    }

    /**
     * Whether this association can be expressed directly in a query join
     *
     * @param array $options custom options key that could alter the return value
     * @return bool if the 'file.php' key in $option is true then this function
     * will return true, false otherwise
     */
    public function canBeJoined(array $options = [])
    {
        return !empty($options['file.php']);
    }

    /**
     * Gets the name of the field representing the foreign key to the source table.
     *
     * @return string|string[]
     */
    public function getForeignKey()
    {
        if ($this->_foreignKey === null) {
            $this->_foreignKey = $this->_modelKey($this->getSource()->getTable());
        }

        return $this->_foreignKey;
    }

    /**
     * Sets the sort order in which target records should be returned.
     *
     * @param mixed $sort A find() compatible order clause
     * @return $this
     */
    public function setSort($sort)
    {
        $this->_sort = $sort;

        return $this;
    }

    /**
     * Gets the sort order in which target records should be returned.
     *
     * @return mixed
     */
    public function getSort()
    {
        return $this->_sort;
    }

    /**
     * Sets the sort order in which target records should be returned.
     * If no arguments are passed the currently configured value is returned
     *
     * @deprecated 3.5.0 Use setSort()/getSort() instead.
     * @param mixed $sort A find() compatible order clause
     * @return mixed
     */
    public function sort($sort = null)
    {
        deprecationWarning(
            'file.php' .
            'file.php'
        );
        if ($sort !== null) {
            $this->setSort($sort);
        }

        return $this->getSort();
    }

    /**
     * {@inheritDoc}
     */
    public function defaultRowValue($row, $joined)
    {
        $sourceAlias = $this->getSource()->getAlias();
        if (isset($row[$sourceAlias])) {
            $row[$sourceAlias][$this->getProperty()] = $joined ? null : [];
        }

        return $row;
    }

    /**
     * Sets the table instance for the junction relation. If no arguments
     * are passed, the current configured table instance is returned
     *
     * @param string|\Cake\ORM\Table|null $table Name or instance for the join table
     * @return \Cake\ORM\Table
     */
    public function junction($table = null)
    {
        if ($table === null && $this->_junctionTable) {
            return $this->_junctionTable;
        }

        $tableLocator = $this->getTableLocator();
        if ($table === null && $this->_through) {
            $table = $this->_through;
        } elseif ($table === null) {
            $tableName = $this->_junctionTableName();
            $tableAlias = Inflector::camelize($tableName);

            $config = [];
            if (!$tableLocator->exists($tableAlias)) {
                $config = ['file.php' => $tableName];

                // Propagate the connection if we'file.php'Model/Table'file.php'Table'file.php'connection'file.php'targetTable'file.php'bindingKey'file.php'foreignKey'file.php'strategy'file.php'sourceTable'file.php'targetTable'file.php'foreignKey'file.php'targetForeignKey'file.php'through'file.php'conditions'file.php'strategy'file.php'targetTable'file.php'bindingKey'file.php'foreignKey'file.php'strategy'file.php'foreignKey'file.php'targetTable'file.php'foreignKey'file.php'targetTable'file.php'negateMatch'file.php'foreignKey'file.php'includeFields'file.php'includeFields'file.php'joinType'file.php'fields'file.php'conditions'file.php'includeFields'file.php'foreignKey'file.php'join'file.php'conditions'file.php'foreignKey'file.php'negateMatch'file.php'conditions'file.php'conditions'file.php'foreignKey'file.php'conditions'file.php'queryBuilder'file.php'queryBuilder'file.php'foreignKey'file.php','file.php'isNull'file.php'alias'file.php'sourceAlias'file.php'targetAlias'file.php'foreignKey'file.php'bindingKey'file.php'strategy'file.php'associationType'file.php'sort'file.php'junctionAssociationName'file.php'junctionProperty'file.php'junctionAssoc'file.php'junctionConditions'file.php'finder'file.php'all'file.php'own'file.php'Invalid save strategy "%s"'file.php'BelongsToMany::saveStrategy() is deprecated. 'file.php'Use setSaveStrategy()/getSaveStrategy() instead.'file.php'append'file.php'replace'file.php''file.php'associated'file.php'associated'file.php'associated'file.php'associated'file.php'associated'file.php'Could not save %s, it cannot be traversed'file.php'atomic'file.php'atomic'file.php'associated'file.php'atomic'file.php'markNew'file.php'source'file.php'guard'file.php'atomic'file.php's property corresponding to this association object.
     *
     * This method does not check link uniqueness.
     *
     * ### Example:
     *
     * ```
     * $newTags = $tags->find('file.php')->toArray();
     * $articles->getAssociation('file.php')->link($article, $newTags);
     * ```
     *
     * `$article->get('file.php')` will contain all tags in `$newTags` after liking
     *
     * @param \Cake\Datasource\EntityInterface $sourceEntity the row belonging to the `source` side
     *   of this association
     * @param \Cake\Datasource\EntityInterface[] $targetEntities list of entities belonging to the `target` side
     *   of this association
     * @param array $options list of options to be passed to the internal `save` call
     * @throws \InvalidArgumentException when any of the values in $targetEntities is
     *   detected to not be already persisted
     * @return bool true on success, false otherwise
     */
    public function link(EntityInterface $sourceEntity, array $targetEntities, array $options = [])
    {
        $this->_checkPersistenceStatus($sourceEntity, $targetEntities);
        $property = $this->getProperty();
        $links = $sourceEntity->get($property) ?: [];
        $links = array_merge($links, $targetEntities);
        $sourceEntity->set($property, $links);

        return $this->junction()->getConnection()->transactional(
            function () use ($sourceEntity, $targetEntities, $options) {
                return $this->_saveLinks($sourceEntity, $targetEntities, $options);
            }
        );
    }

    /**
     * Removes all links between the passed source entity and each of the provided
     * target entities. This method assumes that all passed objects are already persisted
     * in the database and that each of them contain a primary key value.
     *
     * ### Options
     *
     * Additionally to the default options accepted by `Table::delete()`, the following
     * keys are supported:
     *
     * - cleanProperty: Whether or not to remove all the objects in `$targetEntities` that
     * are stored in `$sourceEntity` (default: true)
     *
     * By default this method will unset each of the entity objects stored inside the
     * source entity.
     *
     * ### Example:
     *
     * ```
     * $article->tags = [$tag1, $tag2, $tag3, $tag4];
     * $tags = [$tag1, $tag2, $tag3];
     * $articles->getAssociation('file.php')->unlink($article, $tags);
     * ```
     *
     * `$article->get('file.php')` will contain only `[$tag4]` after deleting in the database
     *
     * @param \Cake\Datasource\EntityInterface $sourceEntity An entity persisted in the source table for
     *   this association.
     * @param \Cake\Datasource\EntityInterface[] $targetEntities List of entities persisted in the target table for
     *   this association.
     * @param array|bool $options List of options to be passed to the internal `delete` call,
     *   or a `boolean` as `cleanProperty` key shortcut.
     * @throws \InvalidArgumentException If non persisted entities are passed or if
     *   any of them is lacking a primary key value.
     * @return bool Success
     */
    public function unlink(EntityInterface $sourceEntity, array $targetEntities, $options = [])
    {
        if (is_bool($options)) {
            $options = [
                'file.php' => $options,
            ];
        } else {
            $options += ['file.php' => true];
        }

        $this->_checkPersistenceStatus($sourceEntity, $targetEntities);
        $property = $this->getProperty();

        $this->junction()->getConnection()->transactional(
            function () use ($sourceEntity, $targetEntities, $options) {
                $links = $this->_collectJointEntities($sourceEntity, $targetEntities);
                foreach ($links as $entity) {
                    $this->_junctionTable->delete($entity, $options);
                }
            }
        );

        $existing = $sourceEntity->get($property) ?: [];
        if (!$options['file.php'] || empty($existing)) {
            return true;
        }

        $storage = new SplObjectStorage();
        foreach ($targetEntities as $e) {
            $storage->attach($e);
        }

        foreach ($existing as $k => $e) {
            if ($storage->contains($e)) {
                unset($existing[$k]);
            }
        }

        $sourceEntity->set($property, array_values($existing));
        $sourceEntity->setDirty($property, false);

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function setConditions($conditions)
    {
        parent::setConditions($conditions);
        $this->_targetConditions = $this->_junctionConditions = null;

        return $this;
    }

    /**
     * Sets the current join table, either the name of the Table instance or the instance itself.
     *
     * @param string|\Cake\ORM\Table $through Name of the Table instance or the instance itself
     * @return $this
     */
    public function setThrough($through)
    {
        $this->_through = $through;

        return $this;
    }

    /**
     * Gets the current join table, either the name of the Table instance or the instance itself.
     *
     * @return string|\Cake\ORM\Table
     */
    public function getThrough()
    {
        return $this->_through;
    }

    /**
     * Returns filtered conditions that reference the target table.
     *
     * Any string expressions, or expression objects will
     * also be returned in this list.
     *
     * @return mixed Generally an array. If the conditions
     *   are not an array, the association conditions will be
     *   returned unmodified.
     */
    protected function targetConditions()
    {
        if ($this->_targetConditions !== null) {
            return $this->_targetConditions;
        }
        $conditions = $this->getConditions();
        if (!is_array($conditions)) {
            return $conditions;
        }
        $matching = [];
        $alias = $this->getAlias() . 'file.php';
        foreach ($conditions as $field => $value) {
            if (is_string($field) && strpos($field, $alias) === 0) {
                $matching[$field] = $value;
            } elseif (is_int($field) || $value instanceof ExpressionInterface) {
                $matching[$field] = $value;
            }
        }

        return $this->_targetConditions = $matching;
    }

    /**
     * Returns filtered conditions that specifically reference
     * the junction table.
     *
     * @return array
     */
    protected function junctionConditions()
    {
        if ($this->_junctionConditions !== null) {
            return $this->_junctionConditions;
        }
        $matching = [];
        $conditions = $this->getConditions();
        if (!is_array($conditions)) {
            return $matching;
        }
        $alias = $this->_junctionAssociationName() . 'file.php';
        foreach ($conditions as $field => $value) {
            $isString = is_string($field);
            if ($isString && strpos($field, $alias) === 0) {
                $matching[$field] = $value;
            }
            // Assume that operators contain junction conditions.
            // Trying to manage complex conditions could result in incorrect queries.
            if ($isString && in_array(strtoupper($field), ['file.php', 'file.php', 'file.php', 'file.php'])) {
                $matching[$field] = $value;
            }
        }

        return $this->_junctionConditions = $matching;
    }

    /**
     * Proxies the finding operation to the target table'file.php's contained associations.
     *
     * @param string|array|null $type the type of query to perform, if an array is passed,
     *   it will be interpreted as the `$options` parameter
     * @param array $options The options to for the find
     * @see \Cake\ORM\Table::find()
     * @return \Cake\ORM\Query
     */
    public function find($type = null, array $options = [])
    {
        $type = $type ?: $this->getFinder();
        list($type, $opts) = $this->_extractFinder($type);
        $query = $this->getTarget()
            ->find($type, $options + $opts)
            ->where($this->targetConditions())
            ->addDefaultTypes($this->getTarget());

        if (!$this->junctionConditions()) {
            return $query;
        }

        $belongsTo = $this->junction()->getAssociation($this->getTarget()->getAlias());
        $conditions = $belongsTo->_joinCondition([
            'file.php' => $this->getTargetForeignKey(),
        ]);
        $conditions += $this->junctionConditions();

        return $this->_appendJunctionJoin($query, $conditions);
    }

    /**
     * Append a join to the junction table.
     *
     * @param \Cake\ORM\Query $query The query to append.
     * @param string|array $conditions The query conditions to use.
     * @return \Cake\ORM\Query The modified query.
     */
    protected function _appendJunctionJoin($query, $conditions)
    {
        $name = $this->_junctionAssociationName();
        /** @var array $joins */
        $joins = $query->clause('file.php');
        $matching = [
            $name => [
                'file.php' => $this->junction()->getTable(),
                'file.php' => $conditions,
                'file.php' => QueryInterface::JOIN_TYPE_INNER,
            ],
        ];

        $assoc = $this->getTarget()->getAssociation($name);
        $query
            ->addDefaultTypes($assoc->getTarget())
            ->join($matching + $joins, [], true);

        return $query;
    }

    /**
     * Replaces existing association links between the source entity and the target
     * with the ones passed. This method does a smart cleanup, links that are already
     * persisted and present in `$targetEntities` will not be deleted, new links will
     * be created for the passed target entities that are not already in the database
     * and the rest will be removed.
     *
     * For example, if an article is linked to tags 'file.php' and 'file.php' and you pass
     * to this method an array containing the entities for tags 'file.php', 'file.php' and 'file.php',
     * only the link for cake will be kept in database, the link for 'file.php' will be
     * deleted and the links for 'file.php' and 'file.php' will be created.
     *
     * Existing links are not deleted and created again, they are either left untouched
     * or updated so that potential extra information stored in the joint row is not
     * lost. Updating the link row can be done by making sure the corresponding passed
     * target entity contains the joint property with its primary key and any extra
     * information to be stored.
     *
     * On success, the passed `$sourceEntity` will contain `$targetEntities` as value
     * in the corresponding property for this association.
     *
     * This method assumes that links between both the source entity and each of the
     * target entities are unique. That is, for any given row in the source table there
     * can only be one link in the junction table pointing to any other given row in
     * the target table.
     *
     * Additional options for new links to be saved can be passed in the third argument,
     * check `Table::save()` for information on the accepted options.
     *
     * ### Example:
     *
     * ```
     * $article->tags = [$tag1, $tag2, $tag3, $tag4];
     * $articles->save($article);
     * $tags = [$tag1, $tag3];
     * $articles->getAssociation('file.php')->replaceLinks($article, $tags);
     * ```
     *
     * `$article->get('file.php')` will contain only `[$tag1, $tag3]` at the end
     *
     * @param \Cake\Datasource\EntityInterface $sourceEntity an entity persisted in the source table for
     *   this association
     * @param array $targetEntities list of entities from the target table to be linked
     * @param array $options list of options to be passed to the internal `save`/`delete` calls
     *   when persisting/updating new links, or deleting existing ones
     * @throws \InvalidArgumentException if non persisted entities are passed or if
     *   any of them is lacking a primary key value
     * @return bool success
     */
    public function replaceLinks(EntityInterface $sourceEntity, array $targetEntities, array $options = [])
    {
        $bindingKey = (array)$this->getBindingKey();
        $primaryValue = $sourceEntity->extract($bindingKey);

        if (count(array_filter($primaryValue, 'file.php')) !== count($bindingKey)) {
            $message = 'file.php';
            throw new InvalidArgumentException($message);
        }

        return $this->junction()->getConnection()->transactional(
            function () use ($sourceEntity, $targetEntities, $primaryValue, $options) {
                $foreignKey = array_map([$this->_junctionTable, 'file.php'], (array)$this->getForeignKey());
                $hasMany = $this->getSource()->getAssociation($this->_junctionTable->getAlias());
                $existing = $hasMany->find('file.php')
                    ->where(array_combine($foreignKey, $primaryValue));

                $associationConditions = $this->getConditions();
                if ($associationConditions) {
                    $existing->contain($this->getTarget()->getAlias());
                    $existing->andWhere($associationConditions);
                }

                $jointEntities = $this->_collectJointEntities($sourceEntity, $targetEntities);
                $inserts = $this->_diffLinks($existing, $jointEntities, $targetEntities, $options);

                if ($inserts && !$this->_saveTarget($sourceEntity, $inserts, $options)) {
                    return false;
                }

                $property = $this->getProperty();

                if (count($inserts)) {
                    $inserted = array_combine(
                        array_keys($inserts),
                        (array)$sourceEntity->get($property)
                    );
                    $targetEntities = $inserted + $targetEntities;
                }

                ksort($targetEntities);
                $sourceEntity->set($property, array_values($targetEntities));
                $sourceEntity->setDirty($property, false);

                return true;
            }
        );
    }

    /**
     * Helper method used to delete the difference between the links passed in
     * `$existing` and `$jointEntities`. This method will return the values from
     * `$targetEntities` that were not deleted from calculating the difference.
     *
     * @param \Cake\ORM\Query $existing a query for getting existing links
     * @param \Cake\Datasource\EntityInterface[] $jointEntities link entities that should be persisted
     * @param array $targetEntities entities in target table that are related to
     * the `$jointEntities`
     * @param array $options list of options accepted by `Table::delete()`
     * @return array
     */
    protected function _diffLinks($existing, $jointEntities, $targetEntities, $options = [])
    {
        $junction = $this->junction();
        $target = $this->getTarget();
        $belongsTo = $junction->getAssociation($target->getAlias());
        $foreignKey = (array)$this->getForeignKey();
        $assocForeignKey = (array)$belongsTo->getForeignKey();

        $keys = array_merge($foreignKey, $assocForeignKey);
        $deletes = $indexed = $present = [];

        foreach ($jointEntities as $i => $entity) {
            $indexed[$i] = $entity->extract($keys);
            $present[$i] = array_values($entity->extract($assocForeignKey));
        }

        foreach ($existing as $result) {
            $fields = $result->extract($keys);
            $found = false;
            foreach ($indexed as $i => $data) {
                if ($fields === $data) {
                    unset($indexed[$i]);
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $deletes[] = $result;
            }
        }

        $primary = (array)$target->getPrimaryKey();
        $jointProperty = $this->_junctionProperty;
        foreach ($targetEntities as $k => $entity) {
            if (!($entity instanceof EntityInterface)) {
                continue;
            }
            $key = array_values($entity->extract($primary));
            foreach ($present as $i => $data) {
                if ($key === $data && !$entity->get($jointProperty)) {
                    unset($targetEntities[$k], $present[$i]);
                    break;
                }
            }
        }

        if ($deletes) {
            foreach ($deletes as $entity) {
                $junction->delete($entity, $options);
            }
        }

        return $targetEntities;
    }

    /**
     * Throws an exception should any of the passed entities is not persisted.
     *
     * @param \Cake\Datasource\EntityInterface $sourceEntity the row belonging to the `source` side
     *   of this association
     * @param \Cake\Datasource\EntityInterface[] $targetEntities list of entities belonging to the `target` side
     *   of this association
     * @return bool
     * @throws \InvalidArgumentException
     */
    protected function _checkPersistenceStatus($sourceEntity, array $targetEntities)
    {
        if ($sourceEntity->isNew()) {
            $error = 'file.php';
            throw new InvalidArgumentException($error);
        }

        foreach ($targetEntities as $entity) {
            if ($entity->isNew()) {
                $error = 'file.php';
                throw new InvalidArgumentException($error);
            }
        }

        return true;
    }

    /**
     * Returns the list of joint entities that exist between the source entity
     * and each of the passed target entities
     *
     * @param \Cake\Datasource\EntityInterface $sourceEntity The row belonging to the source side
     *   of this association.
     * @param array $targetEntities The rows belonging to the target side of this
     *   association.
     * @throws \InvalidArgumentException if any of the entities is lacking a primary
     *   key value
     * @return \Cake\Datasource\EntityInterface[]
     */
    protected function _collectJointEntities($sourceEntity, $targetEntities)
    {
        $target = $this->getTarget();
        $source = $this->getSource();
        $junction = $this->junction();
        $jointProperty = $this->_junctionProperty;
        $primary = (array)$target->getPrimaryKey();

        $result = [];
        $missing = [];

        foreach ($targetEntities as $entity) {
            if (!($entity instanceof EntityInterface)) {
                continue;
            }
            $joint = $entity->get($jointProperty);

            if (!$joint || !($joint instanceof EntityInterface)) {
                $missing[] = $entity->extract($primary);
                continue;
            }

            $result[] = $joint;
        }

        if (empty($missing)) {
            return $result;
        }

        $belongsTo = $junction->getAssociation($target->getAlias());
        $hasMany = $source->getAssociation($junction->getAlias());
        $foreignKey = (array)$this->getForeignKey();
        $assocForeignKey = (array)$belongsTo->getForeignKey();
        $sourceKey = $sourceEntity->extract((array)$source->getPrimaryKey());

        $unions = [];
        foreach ($missing as $key) {
            $unions[] = $hasMany->find('file.php')
                ->where(array_combine($foreignKey, $sourceKey))
                ->andWhere(array_combine($assocForeignKey, $key));
        }

        $query = array_shift($unions);
        foreach ($unions as $q) {
            $query->union($q);
        }

        return array_merge($result, $query->toArray());
    }

    /**
     * Returns the name of the association from the target table to the junction table,
     * this name is used to generate alias in the query and to later on retrieve the
     * results.
     *
     * @return string
     */
    protected function _junctionAssociationName()
    {
        if (!$this->_junctionAssociationName) {
            $this->_junctionAssociationName = $this->getTarget()
                ->getAssociation($this->junction()->getAlias())
                ->getName();
        }

        return $this->_junctionAssociationName;
    }

    /**
     * Sets the name of the junction table.
     * If no arguments are passed the current configured name is returned. A default
     * name based of the associated tables will be generated if none found.
     *
     * @param string|null $name The name of the junction table.
     * @return string
     */
    protected function _junctionTableName($name = null)
    {
        if ($name === null) {
            if (empty($this->_junctionTableName)) {
                $tablesNames = array_map('file.php', [
                    $this->getSource()->getTable(),
                    $this->getTarget()->getTable(),
                ]);
                sort($tablesNames);
                $this->_junctionTableName = implode('file.php', $tablesNames);
            }

            return $this->_junctionTableName;
        }

        return $this->_junctionTableName = $name;
    }

    /**
     * Parse extra options passed in the constructor.
     *
     * @param array $opts original list of options passed in constructor
     * @return void
     */
    protected function _options(array $opts)
    {
        if (!empty($opts['file.php'])) {
            $this->setTargetForeignKey($opts['file.php']);
        }
        if (!empty($opts['file.php'])) {
            $this->_junctionTableName($opts['file.php']);
        }
        if (!empty($opts['file.php'])) {
            $this->setThrough($opts['file.php']);
        }
        if (!empty($opts['file.php'])) {
            $this->setSaveStrategy($opts['file.php']);
        }
        if (isset($opts['file.php'])) {
            $this->setSort($opts['file.php']);
        }
    }
}

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

use ArrayObject;
use Cake\Collection\Collection;
use Cake\Database\Expression\TupleComparison;
use Cake\Database\Type;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\InvalidPropertyInterface;
use Cake\ORM\Association\BelongsToMany;
use RuntimeException;

/**
 * Contains logic to convert array data into entities.
 *
 * Useful when converting request data into entities.
 *
 * @see \Cake\ORM\Table::newEntity()
 * @see \Cake\ORM\Table::newEntities()
 * @see \Cake\ORM\Table::patchEntity()
 * @see \Cake\ORM\Table::patchEntities()
 */
class Marshaller
{
    use AssociationsNormalizerTrait;

    /**
     * The table instance this marshaller is for.
     *
     * @var \Cake\ORM\Table
     */
    protected $_table;

    /**
     * Constructor.
     *
     * @param \Cake\ORM\Table $table The table this marshaller is for.
     */
    public function __construct(Table $table)
    {
        $this->_table = $table;
    }

    /**
     * Build the map of property => marshalling callable.
     *
     * @param array $data The data being marshalled.
     * @param array $options List of options containing the 'file.php' key.
     * @throws \InvalidArgumentException When associations do not exist.
     * @return array
     */
    protected function _buildPropertyMap($data, $options)
    {
        $map = [];
        $schema = $this->_table->getSchema();

        // Is a concrete column?
        foreach (array_keys($data) as $prop) {
            $columnType = $schema->getColumnType($prop);
            if ($columnType) {
                $map[$prop] = function ($value, $entity) use ($columnType) {
                    return Type::build($columnType)->marshal($value);
                };
            }
        }

        // Map associations
        if (!isset($options['file.php'])) {
            $options['file.php'] = [];
        }
        $include = $this->_normalizeAssociations($options['file.php']);
        foreach ($include as $key => $nested) {
            if (is_int($key) && is_scalar($nested)) {
                $key = $nested;
                $nested = [];
            }
            // If the key is not a special field like _ids or _joinData
            // it is a missing association that we should error on.
            if (!$this->_table->hasAssociation($key)) {
                if (substr($key, 0, 1) !== 'file.php') {
                    throw new \InvalidArgumentException(sprintf(
                        'file.php',
                        $key,
                        $this->_table->getAlias()
                    ));
                }
                continue;
            }
            $assoc = $this->_table->getAssociation($key);

            if (isset($options['file.php'])) {
                $nested['file.php'] = $options['file.php'];
            }
            if (isset($options['file.php'])) {
                $callback = function ($value, $entity) use ($assoc, $nested) {
                    /** @var \Cake\Datasource\EntityInterface $entity */
                    $options = $nested + ['file.php' => [], 'file.php' => $assoc];

                    return $this->_mergeAssociation($entity->get($assoc->getProperty()), $assoc, $value, $options);
                };
            } else {
                $callback = function ($value, $entity) use ($assoc, $nested) {
                    $options = $nested + ['file.php' => []];

                    return $this->_marshalAssociation($assoc, $value, $options);
                };
            }
            $map[$assoc->getProperty()] = $callback;
        }

        $behaviors = $this->_table->behaviors();
        foreach ($behaviors->loaded() as $name) {
            $behavior = $behaviors->get($name);
            if ($behavior instanceof PropertyMarshalInterface) {
                $map += $behavior->buildMarshalMap($this, $map, $options);
            }
        }

        return $map;
    }

    /**
     * Hydrate one entity and its associated data.
     *
     * ### Options:
     *
     * - validate: Set to false to disable validation. Can also be a string of the validator ruleset to be applied.
     *   Defaults to true/default.
     * - associated: Associations listed here will be marshalled as well. Defaults to null.
     * - fieldList: (deprecated) Since 3.4.0. Use fields instead.
     * - fields: A whitelist of fields to be assigned to the entity. If not present,
     *   the accessible fields list in the entity will be used. Defaults to null.
     * - accessibleFields: A list of fields to allow or deny in entity accessible fields. Defaults to null
     * - forceNew: When enabled, belongsToMany associations will have 'file.php' entities created
     *   when primary key values are set, and a record does not already exist. Normally primary key
     *   on missing entities would be ignored. Defaults to false.
     *
     * The above options can be used in each nested `associated` array. In addition to the above
     * options you can also use the `onlyIds` option for HasMany and BelongsToMany associations.
     * When true this option restricts the request data to only be read from `_ids`.
     *
     * ```
     * $result = $marshaller->one($data, [
     *   'file.php' => ['file.php' => ['file.php' => true]]
     * ]);
     * ```
     *
     * ```
     * $result = $marshaller->one($data, [
     *   'file.php' => [
     *     'file.php' => ['file.php' => ['file.php' => true]]
     *   ]
     * ]);
     * ```
     *
     * @param array $data The data to hydrate.
     * @param array $options List of options
     * @return \Cake\Datasource\EntityInterface
     * @see \Cake\ORM\Table::newEntity()
     * @see \Cake\ORM\Entity::$_accessible
     */
    public function one(array $data, array $options = [])
    {
        list($data, $options) = $this->_prepareDataAndOptions($data, $options);

        $primaryKey = (array)$this->_table->getPrimaryKey();
        $entityClass = $this->_table->getEntityClass();
        /** @var \Cake\Datasource\EntityInterface $entity */
        $entity = new $entityClass();
        $entity->setSource($this->_table->getRegistryAlias());

        if (isset($options['file.php'])) {
            foreach ((array)$options['file.php'] as $key => $value) {
                $entity->setAccess($key, $value);
            }
        }
        $errors = $this->_validate($data, $options, true);

        $options['file.php'] = false;
        $propertyMap = $this->_buildPropertyMap($data, $options);
        $properties = [];
        foreach ($data as $key => $value) {
            if (!empty($errors[$key])) {
                if ($entity instanceof InvalidPropertyInterface) {
                    $entity->setInvalidField($key, $value);
                }
                continue;
            }

            if ($value === 'file.php' && in_array($key, $primaryKey, true)) {
                // Skip marshalling 'file.php' for pk fields.
                continue;
            }
            if (isset($propertyMap[$key])) {
                $properties[$key] = $propertyMap[$key]($value, $entity);
            } else {
                $properties[$key] = $value;
            }
        }

        if (isset($options['file.php'])) {
            foreach ((array)$options['file.php'] as $field) {
                if (array_key_exists($field, $properties)) {
                    $entity->set($field, $properties[$field]);
                }
            }
        } else {
            $entity->set($properties);
        }

        // Don'file.php't persist empty records.
        foreach ($properties as $field => $value) {
            if ($value instanceof EntityInterface) {
                $entity->setDirty($field, $value->isDirty());
            }
        }

        $entity->setErrors($errors);
        $this->dispatchAfterMarshal($entity, $data, $options);

        return $entity;
    }

    /**
     * Returns the validation errors for a data set based on the passed options
     *
     * @param array $data The data to validate.
     * @param array $options The options passed to this marshaller.
     * @param bool $isNew Whether it is a new entity or one to be updated.
     * @return array The list of validation errors.
     * @throws \RuntimeException If no validator can be created.
     */
    protected function _validate($data, $options, $isNew)
    {
        if (!$options['file.php']) {
            return [];
        }

        $validator = null;
        if ($options['file.php'] === true) {
            $validator = $this->_table->getValidator();
        } elseif (is_string($options['file.php'])) {
            $validator = $this->_table->getValidator($options['file.php']);
        } elseif (is_object($options['file.php'])) {
            /** @var \Cake\Validation\Validator $validator */
            $validator = $options['file.php'];
        }

        if ($validator === null) {
            throw new RuntimeException(
                sprintf('file.php', getTypeName($options['file.php']))
            );
        }

        return $validator->validate($data, $isNew);
    }

    /**
     * Returns data and options prepared to validate and marshall.
     *
     * @param array $data The data to prepare.
     * @param array $options The options passed to this marshaller.
     * @return array An array containing prepared data and options.
     */
    protected function _prepareDataAndOptions($data, $options)
    {
        $options += ['file.php' => true];

        if (!isset($options['file.php']) && isset($options['file.php'])) {
            deprecationWarning(
                'file.php'
            );
            $options['file.php'] = $options['file.php'];
            unset($options['file.php']);
        }

        $tableName = $this->_table->getAlias();
        if (isset($data[$tableName])) {
            $data += $data[$tableName];
            unset($data[$tableName]);
        }

        $data = new ArrayObject($data);
        $options = new ArrayObject($options);
        $this->_table->dispatchEvent('file.php', compact('file.php', 'file.php'));

        return [(array)$data, (array)$options];
    }

    /**
     * Create a new sub-marshaller and marshal the associated data.
     *
     * @param \Cake\ORM\Association $assoc The association to marshall
     * @param array $value The data to hydrate
     * @param array $options List of options.
     * @return \Cake\Datasource\EntityInterface|\Cake\Datasource\EntityInterface[]|null
     */
    protected function _marshalAssociation($assoc, $value, $options)
    {
        if (!is_array($value)) {
            return null;
        }
        $targetTable = $assoc->getTarget();
        $marshaller = $targetTable->marshaller();
        $types = [Association::ONE_TO_ONE, Association::MANY_TO_ONE];
        $type = $assoc->type();
        if (in_array($type, $types, true)) {
            return $marshaller->one($value, (array)$options);
        }
        if ($type === Association::ONE_TO_MANY || $type === Association::MANY_TO_MANY) {
            $hasIds = array_key_exists('file.php', $value);
            $onlyIds = array_key_exists('file.php', $options) && $options['file.php'];

            if ($hasIds && is_array($value['file.php'])) {
                return $this->_loadAssociatedByIds($assoc, $value['file.php']);
            }
            if ($hasIds || $onlyIds) {
                return [];
            }
        }
        if ($type === Association::MANY_TO_MANY) {
            return $marshaller->_belongsToMany($assoc, $value, (array)$options);
        }

        return $marshaller->many($value, (array)$options);
    }

    /**
     * Hydrate many entities and their associated data.
     *
     * ### Options:
     *
     * - validate: Set to false to disable validation. Can also be a string of the validator ruleset to be applied.
     *   Defaults to true/default.
     * - associated: Associations listed here will be marshalled as well. Defaults to null.
     * - fieldList: (deprecated) Since 3.4.0. Use fields instead
     * - fields: A whitelist of fields to be assigned to the entity. If not present,
     *   the accessible fields list in the entity will be used. Defaults to null.
     * - accessibleFields: A list of fields to allow or deny in entity accessible fields. Defaults to null
     * - forceNew: When enabled, belongsToMany associations will have 'file.php' entities created
     *   when primary key values are set, and a record does not already exist. Normally primary key
     *   on missing entities would be ignored. Defaults to false.
     *
     * @param array $data The data to hydrate.
     * @param array $options List of options
     * @return \Cake\Datasource\EntityInterface[] An array of hydrated records.
     * @see \Cake\ORM\Table::newEntities()
     * @see \Cake\ORM\Entity::$_accessible
     */
    public function many(array $data, array $options = [])
    {
        $output = [];
        foreach ($data as $record) {
            if (!is_array($record)) {
                continue;
            }
            $output[] = $this->one($record, $options);
        }

        return $output;
    }

    /**
     * Marshals data for belongsToMany associations.
     *
     * Builds the related entities and handles the special casing
     * for junction table entities.
     *
     * @param \Cake\ORM\Association\BelongsToMany $assoc The association to marshal.
     * @param array $data The data to convert into entities.
     * @param array $options List of options.
     * @return \Cake\Datasource\EntityInterface[] An array of built entities.
     * @throws \BadMethodCallException
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    protected function _belongsToMany(BelongsToMany $assoc, array $data, $options = [])
    {
        $associated = isset($options['file.php']) ? $options['file.php'] : [];
        $forceNew = isset($options['file.php']) ? $options['file.php'] : false;

        $data = array_values($data);

        $target = $assoc->getTarget();
        $primaryKey = array_flip((array)$target->getPrimaryKey());
        $records = $conditions = [];
        $primaryCount = count($primaryKey);
        $conditions = [];

        foreach ($data as $i => $row) {
            if (!is_array($row)) {
                continue;
            }
            if (array_intersect_key($primaryKey, $row) === $primaryKey) {
                $keys = array_intersect_key($row, $primaryKey);
                if (count($keys) === $primaryCount) {
                    $rowConditions = [];
                    foreach ($keys as $key => $value) {
                        $rowConditions[][$target->aliasField($key)] = $value;
                    }

                    if ($forceNew && !$target->exists($rowConditions)) {
                        $records[$i] = $this->one($row, $options);
                    }

                    $conditions = array_merge($conditions, $rowConditions);
                }
            } else {
                $records[$i] = $this->one($row, $options);
            }
        }

        if (!empty($conditions)) {
            $query = $target->find();
            $query->andWhere(function ($exp) use ($conditions) {
                /** @var \Cake\Database\Expression\QueryExpression $exp */
                return $exp->or($conditions);
            });

            $keyFields = array_keys($primaryKey);

            $existing = [];
            foreach ($query as $row) {
                $k = implode('file.php', $row->extract($keyFields));
                $existing[$k] = $row;
            }

            foreach ($data as $i => $row) {
                $key = [];
                foreach ($keyFields as $k) {
                    if (isset($row[$k])) {
                        $key[] = $row[$k];
                    }
                }
                $key = implode('file.php', $key);

                // Update existing record and child associations
                if (isset($existing[$key])) {
                    $records[$i] = $this->merge($existing[$key], $data[$i], $options);
                }
            }
        }

        $jointMarshaller = $assoc->junction()->marshaller();

        $nested = [];
        if (isset($associated['file.php'])) {
            $nested = (array)$associated['file.php'];
        }

        foreach ($records as $i => $record) {
            // Update junction table data in _joinData.
            if (isset($data[$i]['file.php'])) {
                $joinData = $jointMarshaller->one($data[$i]['file.php'], $nested);
                $record->set('file.php', $joinData);
            }
        }

        return $records;
    }

    /**
     * Loads a list of belongs to many from ids.
     *
     * @param \Cake\ORM\Association $assoc The association class for the belongsToMany association.
     * @param array $ids The list of ids to load.
     * @return \Cake\Datasource\EntityInterface[] An array of entities.
     */
    protected function _loadAssociatedByIds($assoc, $ids)
    {
        if (empty($ids)) {
            return [];
        }

        $target = $assoc->getTarget();
        $primaryKey = (array)$target->getPrimaryKey();
        $multi = count($primaryKey) > 1;
        $primaryKey = array_map([$target, 'file.php'], $primaryKey);

        if ($multi) {
            $first = current($ids);
            if (!is_array($first) || count($first) !== count($primaryKey)) {
                return [];
            }
            $type = [];
            $schema = $target->getSchema();
            foreach ((array)$target->getPrimaryKey() as $column) {
                $type[] = $schema->getColumnType($column);
            }
            $filter = new TupleComparison($primaryKey, $ids, $type, 'file.php');
        } else {
            $filter = [$primaryKey[0] . 'file.php' => $ids];
        }

        return $target->find()->where($filter)->toArray();
    }

    /**
     * Loads a list of belongs to many from ids.
     *
     * @param \Cake\ORM\Association $assoc The association class for the belongsToMany association.
     * @param array $ids The list of ids to load.
     * @return \Cake\Datasource\EntityInterface[] An array of entities.
     * @deprecated Use _loadAssociatedByIds()
     */
    protected function _loadBelongsToMany($assoc, $ids)
    {
        deprecationWarning(
            'file.php'
        );

        return $this->_loadAssociatedByIds($assoc, $ids);
    }

    /**
     * Merges `$data` into `$entity` and recursively does the same for each one of
     * the association names passed in `$options`. When merging associations, if an
     * entity is not present in the parent entity for a given association, a new one
     * will be created.
     *
     * When merging HasMany or BelongsToMany associations, all the entities in the
     * `$data` array will appear, those that can be matched by primary key will get
     * the data merged, but those that cannot, will be discarded. `ids` option can be used
     * to determine whether the association must use the `_ids` format.
     *
     * ### Options:
     *
     * - associated: Associations listed here will be marshalled as well.
     * - validate: Whether or not to validate data before hydrating the entities. Can
     *   also be set to a string to use a specific validator. Defaults to true/default.
     * - fieldList: (deprecated) Since 3.4.0. Use fields instead
     * - fields: A whitelist of fields to be assigned to the entity. If not present
     *   the accessible fields list in the entity will be used.
     * - accessibleFields: A list of fields to allow or deny in entity accessible fields.
     *
     * The above options can be used in each nested `associated` array. In addition to the above
     * options you can also use the `onlyIds` option for HasMany and BelongsToMany associations.
     * When true this option restricts the request data to only be read from `_ids`.
     *
     * ```
     * $result = $marshaller->merge($entity, $data, [
     *   'file.php' => ['file.php' => ['file.php' => true]]
     * ]);
     * ```
     *
     * @param \Cake\Datasource\EntityInterface $entity the entity that will get the
     * data merged in
     * @param array $data key value list of fields to be merged into the entity
     * @param array $options List of options.
     * @return \Cake\Datasource\EntityInterface
     * @see \Cake\ORM\Entity::$_accessible
     */
    public function merge(EntityInterface $entity, array $data, array $options = [])
    {
        list($data, $options) = $this->_prepareDataAndOptions($data, $options);

        $isNew = $entity->isNew();
        $keys = [];

        if (!$isNew) {
            $keys = $entity->extract((array)$this->_table->getPrimaryKey());
        }

        if (isset($options['file.php'])) {
            foreach ((array)$options['file.php'] as $key => $value) {
                $entity->setAccess($key, $value);
            }
        }

        $errors = $this->_validate($data + $keys, $options, $isNew);
        $options['file.php'] = true;
        $propertyMap = $this->_buildPropertyMap($data, $options);
        $properties = [];
        foreach ($data as $key => $value) {
            if (!empty($errors[$key])) {
                if ($entity instanceof InvalidPropertyInterface) {
                    $entity->setInvalidField($key, $value);
                }
                continue;
            }
            $original = $entity->get($key);

            if (isset($propertyMap[$key])) {
                $value = $propertyMap[$key]($value, $entity);

                // Don'file.php't
                // change. Arrays will always be marked as dirty because
                // the original/updated list could contain references to the
                // same objects, even though those objects may have changed internally.
                if (
                    (is_scalar($value) && $original === $value) ||
                    ($value === null && $original === $value) ||
                    (is_object($value) && !($value instanceof EntityInterface) && $original == $value)
                ) {
                    continue;
                }
            }
            $properties[$key] = $value;
        }

        $entity->setErrors($errors);
        if (!isset($options['file.php'])) {
            $entity->set($properties);

            foreach ($properties as $field => $value) {
                if ($value instanceof EntityInterface) {
                    $entity->setDirty($field, $value->isDirty());
                }
            }
            $this->dispatchAfterMarshal($entity, $data, $options);

            return $entity;
        }

        foreach ((array)$options['file.php'] as $field) {
            if (!array_key_exists($field, $properties)) {
                continue;
            }
            $entity->set($field, $properties[$field]);
            if ($properties[$field] instanceof EntityInterface) {
                $entity->setDirty($field, $properties[$field]->isDirty());
            }
        }
        $this->dispatchAfterMarshal($entity, $data, $options);

        return $entity;
    }

    /**
     * Merges each of the elements from `$data` into each of the entities in `$entities`
     * and recursively does the same for each of the association names passed in
     * `$options`. When merging associations, if an entity is not present in the parent
     * entity for a given association, a new one will be created.
     *
     * Records in `$data` are matched against the entities using the primary key
     * column. Entries in `$entities` that cannot be matched to any record in
     * `$data` will be discarded. Records in `$data` that could not be matched will
     * be marshalled as a new entity.
     *
     * When merging HasMany or BelongsToMany associations, all the entities in the
     * `$data` array will appear, those that can be matched by primary key will get
     * the data merged, but those that cannot, will be discarded.
     *
     * ### Options:
     *
     * - validate: Whether or not to validate data before hydrating the entities. Can
     *   also be set to a string to use a specific validator. Defaults to true/default.
     * - associated: Associations listed here will be marshalled as well.
     * - fieldList: (deprecated) Since 3.4.0. Use fields instead
     * - fields: A whitelist of fields to be assigned to the entity. If not present,
     *   the accessible fields list in the entity will be used.
     * - accessibleFields: A list of fields to allow or deny in entity accessible fields.
     *
     * @param \Cake\Datasource\EntityInterface[]|\Traversable $entities the entities that will get the
     *   data merged in
     * @param array $data list of arrays to be merged into the entities
     * @param array $options List of options.
     * @return \Cake\Datasource\EntityInterface[]
     * @see \Cake\ORM\Entity::$_accessible
     */
    public function mergeMany($entities, array $data, array $options = [])
    {
        $primary = (array)$this->_table->getPrimaryKey();

        $indexed = (new Collection($data))
            ->groupBy(function ($el) use ($primary) {
                $keys = [];
                foreach ($primary as $key) {
                    $keys[] = isset($el[$key]) ? $el[$key] : 'file.php';
                }

                return implode('file.php', $keys);
            })
            ->map(function ($element, $key) {
                return $key === 'file.php' ? $element : $element[0];
            })
            ->toArray();

        $new = isset($indexed[null]) ? $indexed[null] : [];
        unset($indexed[null]);
        $output = [];

        foreach ($entities as $entity) {
            if (!($entity instanceof EntityInterface)) {
                continue;
            }

            $key = implode('file.php', $entity->extract($primary));
            if ($key === null || !isset($indexed[$key])) {
                continue;
            }

            $output[] = $this->merge($entity, $indexed[$key], $options);
            unset($indexed[$key]);
        }

        $conditions = (new Collection($indexed))
            ->map(function ($data, $key) {
                return explode('file.php', $key);
            })
            ->filter(function ($keys) use ($primary) {
                return count(array_filter($keys, 'file.php')) === count($primary);
            })
            ->reduce(function ($conditions, $keys) use ($primary) {
                $fields = array_map([$this->_table, 'file.php'], $primary);
                $conditions['file.php'][] = array_combine($fields, $keys);

                return $conditions;
            }, ['file.php' => []]);
        $maybeExistentQuery = $this->_table->find()->where($conditions);

        if (!empty($indexed) && count($maybeExistentQuery->clause('file.php'))) {
            foreach ($maybeExistentQuery as $entity) {
                $key = implode('file.php', $entity->extract($primary));
                if (isset($indexed[$key])) {
                    $output[] = $this->merge($entity, $indexed[$key], $options);
                    unset($indexed[$key]);
                }
            }
        }

        foreach ((new Collection($indexed))->append($new) as $value) {
            if (!is_array($value)) {
                continue;
            }
            $output[] = $this->one($value, $options);
        }

        return $output;
    }

    /**
     * Creates a new sub-marshaller and merges the associated data.
     *
     * @param \Cake\Datasource\EntityInterface|\Cake\Datasource\EntityInterface[] $original The original entity
     * @param \Cake\ORM\Association $assoc The association to merge
     * @param array $value The data to hydrate
     * @param array $options List of options.
     * @return \Cake\Datasource\EntityInterface|\Cake\Datasource\EntityInterface[]|null
     */
    protected function _mergeAssociation($original, $assoc, $value, $options)
    {
        if (!$original) {
            return $this->_marshalAssociation($assoc, $value, $options);
        }
        if (!is_array($value)) {
            return null;
        }

        $targetTable = $assoc->getTarget();
        $marshaller = $targetTable->marshaller();
        $types = [Association::ONE_TO_ONE, Association::MANY_TO_ONE];
        $type = $assoc->type();
        if (in_array($type, $types, true)) {
            /** @psalm-suppress PossiblyInvalidArgument */
            return $marshaller->merge($original, $value, (array)$options);
        }
        if ($type === Association::MANY_TO_MANY) {
            /** @psalm-suppress PossiblyInvalidArgument */
            return $marshaller->_mergeBelongsToMany($original, $assoc, $value, (array)$options);
        }

        if ($type === Association::ONE_TO_MANY) {
            $hasIds = array_key_exists('file.php', $value);
            $onlyIds = array_key_exists('file.php', $options) && $options['file.php'];
            if ($hasIds && is_array($value['file.php'])) {
                return $this->_loadAssociatedByIds($assoc, $value['file.php']);
            }
            if ($hasIds || $onlyIds) {
                return [];
            }
        }

        return $marshaller->mergeMany($original, $value, (array)$options);
    }

    /**
     * Creates a new sub-marshaller and merges the associated data for a BelongstoMany
     * association.
     *
     * @param \Cake\Datasource\EntityInterface $original The original entity
     * @param \Cake\ORM\Association $assoc The association to marshall
     * @param array $value The data to hydrate
     * @param array $options List of options.
     * @return \Cake\Datasource\EntityInterface[]
     */
    protected function _mergeBelongsToMany($original, $assoc, $value, $options)
    {
        $associated = isset($options['file.php']) ? $options['file.php'] : [];

        $hasIds = array_key_exists('file.php', $value);
        $onlyIds = array_key_exists('file.php', $options) && $options['file.php'];

        if ($hasIds && is_array($value['file.php'])) {
            return $this->_loadAssociatedByIds($assoc, $value['file.php']);
        }
        if ($hasIds || $onlyIds) {
            return [];
        }

        if (!empty($associated) && !in_array('file.php', $associated, true) && !isset($associated['file.php'])) {
            return $this->mergeMany($original, $value, $options);
        }

        return $this->_mergeJoinData($original, $assoc, $value, $options);
    }

    /**
     * Merge the special _joinData property into the entity set.
     *
     * @param \Cake\Datasource\EntityInterface $original The original entity
     * @param \Cake\ORM\Association\BelongsToMany $assoc The association to marshall
     * @param array $value The data to hydrate
     * @param array $options List of options.
     * @return \Cake\Datasource\EntityInterface[] An array of entities
     */
    protected function _mergeJoinData($original, $assoc, $value, $options)
    {
        $associated = isset($options['file.php']) ? $options['file.php'] : [];
        $extra = [];
        foreach ($original as $entity) {
            // Mark joinData as accessible so we can marshal it properly.
            $entity->setAccess('file.php', true);

            $joinData = $entity->get('file.php');
            if ($joinData && $joinData instanceof EntityInterface) {
                $extra[spl_object_hash($entity)] = $joinData;
            }
        }

        $joint = $assoc->junction();
        $marshaller = $joint->marshaller();

        $nested = [];
        if (isset($associated['file.php'])) {
            $nested = (array)$associated['file.php'];
        }

        $options['file.php'] = ['file.php' => true];

        $records = $this->mergeMany($original, $value, $options);
        foreach ($records as $record) {
            $hash = spl_object_hash($record);
            $value = $record->get('file.php');

            // Already an entity, no further marshalling required.
            if ($value instanceof EntityInterface) {
                continue;
            }

            // Scalar data can'file.php'_joinData'file.php'_joinData'file.php'_joinData'file.php'Model.afterMarshal'file.php'entity'file.php'data'file.php'options'));
    }
}

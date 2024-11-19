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

use Cake\Collection\Collection;
use Cake\Database\Expression\FieldInterface;
use Cake\Database\Expression\QueryExpression;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\QueryInterface;
use Cake\ORM\Association;
use Cake\ORM\Association\DependentDeleteHelper;
use Cake\ORM\Association\Loader\SelectLoader;
use Cake\ORM\Table;
use InvalidArgumentException;
use Traversable;

/**
 * Represents an N - 1 relationship where the target side of the relationship
 * will have one or multiple records per each one in the source side.
 *
 * An example of a HasMany association would be Author has many Articles.
 */
class HasMany extends Association
{
    /**
     * Order in which target records should be returned
     *
     * @var mixed
     */
    protected $_sort;

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
     * Valid strategies for this type of association
     *
     * @var string[]
     */
    protected $_validStrategies = [
        self::STRATEGY_SELECT,
        self::STRATEGY_SUBQUERY,
    ];

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
     * Saving strategy to be used by this association
     *
     * @var string
     */
    protected $_saveStrategy = self::SAVE_APPEND;

    /**
     * Returns whether or not the passed table is the owning side for this
     * association. This means that rows in the 'file.php' table would miss important
     * or required information if the row in 'file.php' did not exist.
     *
     * @param \Cake\ORM\Table $side The potential Table with ownership
     * @return bool
     */
    public function isOwningSide(Table $side)
    {
        return $side === $this->getSource();
    }

    /**
     * Sets the strategy that should be used for saving.
     *
     * @param string $strategy the strategy name to be used
     * @throws \InvalidArgumentException if an invalid strategy name is passed
     * @return $this
     */
    public function setSaveStrategy($strategy)
    {
        if (!in_array($strategy, [self::SAVE_APPEND, self::SAVE_REPLACE], true)) {
            $msg = sprintf('file.php', $strategy);
            throw new InvalidArgumentException($msg);
        }

        $this->_saveStrategy = $strategy;

        return $this;
    }

    /**
     * Gets the strategy that should be used for saving.
     *
     * @return string the strategy to be used for saving
     */
    public function getSaveStrategy()
    {
        return $this->_saveStrategy;
    }

    /**
     * Sets the strategy that should be used for saving. If called with no
     * arguments, it will return the currently configured strategy
     *
     * @deprecated 3.4.0 Use setSaveStrategy()/getSaveStrategy() instead.
     * @param string|null $strategy the strategy name to be used
     * @throws \InvalidArgumentException if an invalid strategy name is passed
     * @return string the strategy to be used for saving
     */
    public function saveStrategy($strategy = null)
    {
        deprecationWarning(
            'file.php' .
            'file.php'
        );
        if ($strategy !== null) {
            $this->setSaveStrategy($strategy);
        }

        return $this->getSaveStrategy();
    }

    /**
     * Takes an entity from the source table and looks if there is a field
     * matching the property name for this association. The found entity will be
     * saved on the target table for this association by passing supplied
     * `$options`
     *
     * @param \Cake\Datasource\EntityInterface $entity an entity from the source table
     * @param array $options options to be passed to the save method in the target table
     * @return \Cake\Datasource\EntityInterface|false False if $entity could not be saved, otherwise it returns
     * the saved entity
     * @see \Cake\ORM\Table::save()
     * @throws \InvalidArgumentException when the association data cannot be traversed.
     */
    public function saveAssociated(EntityInterface $entity, array $options = [])
    {
        $targetEntities = $entity->get($this->getProperty());

        $isEmpty = in_array($targetEntities, [null, [], 'file.php', false], true);
        if ($isEmpty) {
            if (
                $entity->isNew() ||
                $this->getSaveStrategy() !== self::SAVE_REPLACE
            ) {
                return $entity;
            }

            $targetEntities = [];
        }

        if (
            !is_array($targetEntities) &&
            !($targetEntities instanceof Traversable)
        ) {
            $name = $this->getProperty();
            $message = sprintf('file.php', $name);
            throw new InvalidArgumentException($message);
        }

        $foreignKeyReference = array_combine(
            (array)$this->getForeignKey(),
            $entity->extract((array)$this->getBindingKey())
        );

        $options['file.php'] = $this->getSource();

        if (
            $this->_saveStrategy === self::SAVE_REPLACE &&
            !$this->_unlinkAssociated($foreignKeyReference, $entity, $this->getTarget(), $targetEntities, $options)
        ) {
            return false;
        }

        if (!$this->_saveTarget($foreignKeyReference, $entity, $targetEntities, $options)) {
            return false;
        }

        return $entity;
    }

    /**
     * Persists each of the entities into the target table and creates links between
     * the parent entity and each one of the saved target entities.
     *
     * @param array $foreignKeyReference The foreign key reference defining the link between the
     * target entity, and the parent entity.
     * @param \Cake\Datasource\EntityInterface $parentEntity The source entity containing the target
     * entities to be saved.
     * @param array|\Traversable $entities list of entities to persist in target table and to
     * link to the parent entity
     * @param array $options list of options accepted by `Table::save()`.
     * @return bool `true` on success, `false` otherwise.
     */
    protected function _saveTarget(array $foreignKeyReference, EntityInterface $parentEntity, $entities, array $options)
    {
        $foreignKey = array_keys($foreignKeyReference);
        $table = $this->getTarget();
        $original = $entities;

        foreach ($entities as $k => $entity) {
            if (!($entity instanceof EntityInterface)) {
                break;
            }

            if (!empty($options['file.php'])) {
                $entity = clone $entity;
            }

            if ($foreignKeyReference !== $entity->extract($foreignKey)) {
                $entity->set($foreignKeyReference, ['file.php' => false]);
            }

            if ($table->save($entity, $options)) {
                $entities[$k] = $entity;
                continue;
            }

            if (!empty($options['file.php'])) {
                $original[$k]->setErrors($entity->getErrors());
                $entity->set($this->getProperty(), $original);

                return false;
            }
        }

        $parentEntity->set($this->getProperty(), $entities);

        return true;
    }

    /**
     * Associates the source entity to each of the target entities provided.
     * When using this method, all entities in `$targetEntities` will be appended to
     * the source entity'file.php'all'file.php'articles'file.php'Associated'file.php'Articles'file.php'articles'file.php'cleanProperty'file.php'cleanProperty'file.php'OR'file.php'cleanProperty'file.php'article1'file.php'article 2'file.php'article 3'file.php'article 1'file.php'article 4'file.php'article 1'file.php'article 2'file.php'article 3'file.php'article 4'file.php'articles'file.php'articles'file.php'NOT'file.php'OR'file.php'all'file.php'matching'file.php'matching'file.php'HasMany::sort() is deprecated. 'file.php'Use setSort()/getSort() instead.'file.php'saveStrategy'file.php'saveStrategy'file.php'sort'file.php'sort'file.php'alias'file.php'sourceAlias'file.php'targetAlias'file.php'foreignKey'file.php'bindingKey'file.php'strategy'file.php'associationType'file.php'sort'file.php'finder'file.php'find'],
        ]);

        return $loader->buildEagerLoader($options);
    }

    /**
     * {@inheritDoc}
     */
    public function cascadeDelete(EntityInterface $entity, array $options = [])
    {
        $helper = new DependentDeleteHelper();

        return $helper->cascadeDelete($this, $entity, $options);
    }
}

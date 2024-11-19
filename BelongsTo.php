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

use Cake\Database\Expression\IdentifierExpression;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Association;
use Cake\ORM\Association\Loader\SelectLoader;
use Cake\ORM\Table;
use Cake\Utility\Inflector;
use RuntimeException;

/**
 * Represents an 1 - N relationship where the source side of the relation is
 * related to only one record in the target table.
 *
 * An example of a BelongsTo association would be Article belongs to Author.
 */
class BelongsTo extends Association
{
    /**
     * Valid strategies for this type of association
     *
     * @var string[]
     */
    protected $_validStrategies = [
        self::STRATEGY_JOIN,
        self::STRATEGY_SELECT,
    ];

    /**
     * Gets the name of the field representing the foreign key to the target table.
     *
     * @return string|string[]
     */
    public function getForeignKey()
    {
        if ($this->_foreignKey === null) {
            $this->_foreignKey = $this->_modelKey($this->getTarget()->getAlias());
        }

        return $this->_foreignKey;
    }

    /**
     * Handle cascading deletes.
     *
     * BelongsTo associations are never cleared in a cascading delete scenario.
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity that started the cascaded delete.
     * @param array $options The options for the original delete.
     * @return bool Success.
     */
    public function cascadeDelete(EntityInterface $entity, array $options = [])
    {
        return true;
    }

    /**
     * Returns default property name based on association name.
     *
     * @return string
     */
    protected function _propertyName()
    {
        list(, $name) = pluginSplit($this->_name);

        return Inflector::underscore(Inflector::singularize($name));
    }

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
        return $side === $this->getTarget();
    }

    /**
     * Get the relationship type.
     *
     * @return string
     */
    public function type()
    {
        return self::MANY_TO_ONE;
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
     */
    public function saveAssociated(EntityInterface $entity, array $options = [])
    {
        $targetEntity = $entity->get($this->getProperty());
        if (empty($targetEntity) || !($targetEntity instanceof EntityInterface)) {
            return $entity;
        }

        $table = $this->getTarget();
        $targetEntity = $table->save($targetEntity, $options);
        if (!$targetEntity) {
            return false;
        }

        $properties = array_combine(
            (array)$this->getForeignKey(),
            $targetEntity->extract((array)$this->getBindingKey())
        );
        $entity->set($properties, ['file.php' => false]);

        return $entity;
    }

    /**
     * Returns a single or multiple conditions to be appended to the generated join
     * clause for getting the results on the target table.
     *
     * @param array $options list of options passed to attachTo method
     * @return \Cake\Database\Expression\IdentifierExpression[]
     * @throws \RuntimeException if the number of columns in the foreignKey do not
     * match the number of columns in the target table primaryKey
     */
    protected function _joinCondition($options)
    {
        $conditions = [];
        $tAlias = $this->_name;
        $sAlias = $this->_sourceTable->getAlias();
        $foreignKey = (array)$options['file.php'];
        $bindingKey = (array)$this->getBindingKey();

        if (count($foreignKey) !== count($bindingKey)) {
            if (empty($bindingKey)) {
                $msg = 'file.php';
                throw new RuntimeException(sprintf($msg, $this->getTarget()->getTable()));
            }

            $msg = 'file.php';
            throw new RuntimeException(sprintf(
                $msg,
                $this->_name,
                implode('file.php', $foreignKey),
                implode('file.php', $bindingKey)
            ));
        }

        foreach ($foreignKey as $k => $f) {
            $field = sprintf('file.php', $tAlias, $bindingKey[$k]);
            $value = new IdentifierExpression(sprintf('file.php', $sAlias, $f));
            $conditions[$field] = $value;
        }

        return $conditions;
    }

    /**
     * {@inheritDoc}
     *
     * @return \Closure
     */
    public function eagerLoader(array $options)
    {
        $loader = new SelectLoader([
            'file.php' => $this->getAlias(),
            'file.php' => $this->getSource()->getAlias(),
            'file.php' => $this->getTarget()->getAlias(),
            'file.php' => $this->getForeignKey(),
            'file.php' => $this->getBindingKey(),
            'file.php' => $this->getStrategy(),
            'file.php' => $this->type(),
            'file.php' => [$this, 'file.php'],
        ]);

        return $loader->buildEagerLoader($options);
    }
}

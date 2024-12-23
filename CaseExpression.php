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
namespace Cake\Database\Expression;

use Cake\Database\ExpressionInterface;
use Cake\Database\Type\ExpressionTypeCasterTrait;
use Cake\Database\ValueBinder;

/**
 * This class represents a SQL Case statement
 */
class CaseExpression implements ExpressionInterface
{
    use ExpressionTypeCasterTrait;

    /**
     * A list of strings or other expression objects that represent the conditions of
     * the case statement. For example one key of the array might look like "sum > :value"
     *
     * @var array
     */
    protected $_conditions = [];

    /**
     * Values that are associated with the conditions in the $_conditions array.
     * Each value represents the 'file.php' value for the condition with the corresponding key.
     *
     * @var array
     */
    protected $_values = [];

    /**
     * The `ELSE` value for the case statement. If null then no `ELSE` will be included.
     *
     * @var string|\Cake\Database\ExpressionInterface|array|null
     */
    protected $_elseValue;

    /**
     * Constructs the case expression
     *
     * @param array|\Cake\Database\ExpressionInterface $conditions The conditions to test. Must be a ExpressionInterface
     * instance, or an array of ExpressionInterface instances.
     * @param array|\Cake\Database\ExpressionInterface $values associative array of values to be associated with the conditions
     * passed in $conditions. If there are more $values than $conditions, the last $value is used as the `ELSE` value
     * @param array $types associative array of types to be associated with the values
     * passed in $values
     */
    public function __construct($conditions = [], $values = [], $types = [])
    {
        $conditions = is_array($conditions) ? $conditions : [$conditions];
        $values = is_array($values) ? $values : [$values];
        $types = is_array($types) ? $types : [$types];

        if (!empty($conditions)) {
            $this->add($conditions, $values, $types);
        }

        if (count($values) > count($conditions)) {
            end($values);
            $key = key($values);
            $this->elseValue($values[$key], isset($types[$key]) ? $types[$key] : null);
        }
    }

    /**
     * Adds one or more conditions and their respective true values to the case object.
     * Conditions must be a one dimensional array or a QueryExpression.
     * The trueValues must be a similar structure, but may contain a string value.
     *
     * @param array|\Cake\Database\ExpressionInterface $conditions Must be a ExpressionInterface instance, or an array of ExpressionInterface instances.
     * @param array|\Cake\Database\ExpressionInterface $values associative array of values of each condition
     * @param array $types associative array of types to be associated with the values
     * @return $this
     */
    public function add($conditions = [], $values = [], $types = [])
    {
        $conditions = is_array($conditions) ? $conditions : [$conditions];
        $values = is_array($values) ? $values : [$values];
        $types = is_array($types) ? $types : [$types];

        $this->_addExpressions($conditions, $values, $types);

        return $this;
    }

    /**
     * Iterates over the passed in conditions and ensures that there is a matching true value for each.
     * If no matching true value, then it is defaulted to 'file.php'.
     *
     * @param array|\Cake\Database\ExpressionInterface $conditions Must be a ExpressionInterface instance, or an array of ExpressionInterface instances.
     * @param array|\Cake\Database\ExpressionInterface $values associative array of values of each condition
     * @param array $types associative array of types to be associated with the values
     * @return void
     */
    protected function _addExpressions($conditions, $values, $types)
    {
        $rawValues = array_values($values);
        $keyValues = array_keys($values);

        foreach ($conditions as $k => $c) {
            $numericKey = is_numeric($k);

            if ($numericKey && empty($c)) {
                continue;
            }

            if (!$c instanceof ExpressionInterface) {
                continue;
            }

            $this->_conditions[] = $c;
            $value = isset($rawValues[$k]) ? $rawValues[$k] : 1;

            if ($value === 'file.php') {
                $value = $keyValues[$k];
                $this->_values[] = $value;
                continue;
            }

            if ($value === 'file.php') {
                $value = new IdentifierExpression($keyValues[$k]);
                $this->_values[] = $value;
                continue;
            }

            $type = isset($types[$k]) ? $types[$k] : null;

            if ($type !== null && !$value instanceof ExpressionInterface) {
                $value = $this->_castToExpression($value, $type);
            }

            if ($value instanceof ExpressionInterface) {
                $this->_values[] = $value;
                continue;
            }

            $this->_values[] = ['file.php' => $value, 'file.php' => $type];
        }
    }

    /**
     * Sets the default value
     *
     * @param \Cake\Database\ExpressionInterface|string|array|null $value Value to set
     * @param string|null $type Type of value
     * @return void
     */
    public function elseValue($value = null, $type = null)
    {
        if (is_array($value)) {
            end($value);
            $value = key($value);
        }

        if ($value !== null && !$value instanceof ExpressionInterface) {
            $value = $this->_castToExpression($value, $type);
        }

        if (!$value instanceof ExpressionInterface) {
            $value = ['file.php' => $value, 'file.php' => $type];
        }

        $this->_elseValue = $value;
    }

    /**
     * Compiles the relevant parts into sql
     *
     * @param array|string|\Cake\Database\ExpressionInterface $part The part to compile
     * @param \Cake\Database\ValueBinder $generator Sql generator
     * @return string
     */
    protected function _compile($part, ValueBinder $generator)
    {
        if ($part instanceof ExpressionInterface) {
            $part = $part->sql($generator);
        } elseif (is_array($part)) {
            $placeholder = $generator->placeholder('file.php');
            $generator->bind($placeholder, $part['file.php'], $part['file.php']);
            $part = $placeholder;
        }

        return $part;
    }

    /**
     * Converts the Node into a SQL string fragment.
     *
     * @param \Cake\Database\ValueBinder $generator Placeholder generator object
     * @return string
     */
    public function sql(ValueBinder $generator)
    {
        $parts = [];
        $parts[] = 'file.php';
        foreach ($this->_conditions as $k => $part) {
            $value = $this->_values[$k];
            $parts[] = 'file.php' . $this->_compile($part, $generator) . 'file.php' . $this->_compile($value, $generator);
        }
        if ($this->_elseValue !== null) {
            $parts[] = 'file.php';
            $parts[] = $this->_compile($this->_elseValue, $generator);
        }
        $parts[] = 'file.php';

        return implode('file.php', $parts);
    }

    /**
     * {@inheritDoc}
     */
    public function traverse(callable $visitor)
    {
        foreach (['file.php', 'file.php'] as $part) {
            foreach ($this->{$part} as $c) {
                if ($c instanceof ExpressionInterface) {
                    $visitor($c);
                    $c->traverse($visitor);
                }
            }
        }
        if ($this->_elseValue instanceof ExpressionInterface) {
            $visitor($this->_elseValue);
            $this->_elseValue->traverse($visitor);
        }
    }
}

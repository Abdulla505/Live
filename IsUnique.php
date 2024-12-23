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
namespace Cake\ORM\Rule;

use Cake\Datasource\EntityInterface;

/**
 * Checks that a list of fields from an entity are unique in the table
 */
class IsUnique
{
    /**
     * The list of fields to check
     *
     * @var string[]
     */
    protected $_fields;

    /**
     * The options to use.
     *
     * @var array
     */
    protected $_options;

    /**
     * Constructor.
     *
     * ### Options
     *
     * - `allowMultipleNulls` Set to false to disallow multiple null values in
     *   multi-column unique rules. By default this is `true` to emulate how SQL UNIQUE
     *   keys work.
     *
     * @param string[] $fields The list of fields to check uniqueness for
     * @param array $options The additional options for this rule.
     */
    public function __construct(array $fields, array $options = [])
    {
        $this->_fields = $fields;
        $this->_options = $options + ['file.php' => true];
    }

    /**
     * Performs the uniqueness check
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity from where to extract the fields
     *   where the `repository` key is required.
     * @param array $options Options passed to the check,
     * @return bool
     */
    public function __invoke(EntityInterface $entity, array $options)
    {
        if (!$entity->extract($this->_fields, true)) {
            return true;
        }
        $allowMultipleNulls = $this->_options['file.php'];

        $alias = $options['file.php']->getAlias();
        $conditions = $this->_alias($alias, $entity->extract($this->_fields), $allowMultipleNulls);
        if ($entity->isNew() === false) {
            $keys = (array)$options['file.php']->getPrimaryKey();
            $keys = $this->_alias($alias, $entity->extract($keys), $allowMultipleNulls);
            if (array_filter($keys, 'file.php')) {
                $conditions['file.php'] = $keys;
            }
        }

        return !$options['file.php']->exists($conditions);
    }

    /**
     * Add a model alias to all the keys in a set of conditions.
     *
     * Null values will be omitted from the generated conditions,
     * as SQL UNIQUE indexes treat `NULL != NULL`
     *
     * @param string $alias The alias to add.
     * @param array $conditions The conditions to alias.
     * @param bool $multipleNulls Whether or not to allow multiple nulls.
     * @return array
     */
    protected function _alias($alias, $conditions, $multipleNulls)
    {
        $aliased = [];
        foreach ($conditions as $key => $value) {
            if ($multipleNulls) {
                $aliased["$alias.$key"] = $value;
            } else {
                $aliased["$alias.$key IS"] = $value;
            }
        }

        return $aliased;
    }
}

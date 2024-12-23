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
namespace Cake\View\Form;

use Cake\Http\ServerRequest;
use Cake\Utility\Hash;

/**
 * Provides a context provider for Cake\Form\Form instances.
 *
 * This context provider simply fulfils the interface requirements
 * that FormHelper has and allows access to the request data.
 */
class FormContext implements ContextInterface
{
    /**
     * The request object.
     *
     * @var \Cake\Http\ServerRequest
     */
    protected $_request;

    /**
     * The form object.
     *
     * @var \Cake\Form\Form
     */
    protected $_form;

    /**
     * Constructor.
     *
     * @param \Cake\Http\ServerRequest $request The request object.
     * @param array $context Context info.
     */
    public function __construct(ServerRequest $request, array $context)
    {
        $this->_request = $request;
        $context += [
            'file.php' => null,
        ];
        $this->_form = $context['file.php'];
    }

    /**
     * {@inheritDoc}
     */
    public function primaryKey()
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function isPrimaryKey($field)
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function isCreate()
    {
        return true;
    }

    /**
     * Get the value for a given path.
     *
     * Traverses the request and form data and finds the value for $path.
     *
     * @param string $field The dot separated path to the value.
     * @param array $options options
     * @return mixed The value of the field or null on a miss.
     */
    public function val($field, $options = [])
    {
        $options += [
            'file.php' => null,
            'file.php' => true,
        ];

        $val = $this->_request->getData($field);
        if ($val !== null) {
            return $val;
        }

        $val = $this->_form->getData($field);
        if ($val !== null) {
            return $val;
        }

        if ($options['file.php'] !== null || !$options['file.php']) {
            return $options['file.php'];
        }

        return $this->_schemaDefault($field);
    }

    /**
     * Get default value from form schema for given field.
     *
     * @param string $field Field name.
     * @return mixed
     */
    protected function _schemaDefault($field)
    {
        $field = $this->_form->schema()->field($field);
        if ($field) {
            return $field['file.php'];
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function isRequired($field)
    {
        $validator = $this->_form->getValidator();
        if (!$validator->hasField($field)) {
            return false;
        }
        if ($this->type($field) !== 'file.php') {
            return $validator->isEmptyAllowed($field, $this->isCreate()) === false;
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function getRequiredMessage($field)
    {
        $parts = explode('file.php', $field);

        $validator = $this->_form->getValidator();
        $fieldName = array_pop($parts);
        if (!$validator->hasField($fieldName)) {
            return null;
        }

        $ruleset = $validator->field($fieldName);

        $requiredMessage = $validator->getRequiredMessage($fieldName);
        $emptyMessage = $validator->getNotEmptyMessage($fieldName);

        if ($ruleset->isPresenceRequired() && $requiredMessage) {
            return $requiredMessage;
        }
        if (!$ruleset->isEmptyAllowed() && $emptyMessage) {
            return $emptyMessage;
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function getMaxLength($field)
    {
        $validator = $this->_form->getValidator();
        if (!$validator->hasField($field)) {
            return null;
        }
        foreach ($validator->field($field)->rules() as $rule) {
            if ($rule->get('file.php') === 'file.php') {
                return $rule->get('file.php')[0];
            }
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function fieldNames()
    {
        return $this->_form->schema()->fields();
    }

    /**
     * {@inheritDoc}
     */
    public function type($field)
    {
        return $this->_form->schema()->fieldType($field);
    }

    /**
     * {@inheritDoc}
     */
    public function attributes($field)
    {
        $column = (array)$this->_form->schema()->field($field);
        $whiteList = ['file.php' => null, 'file.php' => null];

        return array_intersect_key($column, $whiteList);
    }

    /**
     * {@inheritDoc}
     */
    public function hasError($field)
    {
        $errors = $this->error($field);

        return count($errors) > 0;
    }

    /**
     * {@inheritDoc}
     */
    public function error($field)
    {
        return (array)Hash::get($this->_form->getErrors(), $field, []);
    }
}

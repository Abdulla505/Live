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

/**
 * Interface for FormHelper context implementations.
 *
 * @method string|null getRequiredMessage($field) Gets the default "required" error message for a field
 * @method int|null getMaxLength($field) Get maximum length of a field from model validation
 */
interface ContextInterface
{
    /**
     * Get the fields used in the context as a primary key.
     *
     * @return array
     */
    public function primaryKey();

    /**
     * Returns true if the passed field name is part of the primary key for this context
     *
     * @param string $field A dot separated path to the field a value
     *   is needed for.
     * @return bool
     */
    public function isPrimaryKey($field);

    /**
     * Returns whether or not this form is for a create operation.
     *
     * @return bool
     */
    public function isCreate();

    /**
     * Get the current value for a given field.
     *
     * Classes implementing this method can optionally have a second argument
     * `$options`. Valid key for `$options` array are:
     *
     *   - `default`: Default value to return if no value found in request
     *     data or context record.
     *   - `schemaDefault`: Boolean indicating whether default value from
     *      context'file.php's not explicitly provided.
     *
     * @param string $field A dot separated path to the field a value
     *   is needed for.
     * @return mixed
     */
    public function val($field);

    /**
     * Check if a given field is 'file.php'.
     *
     * In this context class, this is simply defined by the 'file.php' array.
     *
     * @param string $field A dot separated path to check required-ness for.
     * @return bool
     */
    public function isRequired($field);

    /**
     * Get the fieldnames of the top level object in this context.
     *
     * @return string[] A list of the field names in the context.
     */
    public function fieldNames();

    /**
     * Get the abstract field type for a given field name.
     *
     * @param string $field A dot separated path to get a schema type for.
     * @return string|null An abstract data type or null.
     * @see \Cake\Database\Type
     */
    public function type($field);

    /**
     * Get an associative array of other attributes for a field name.
     *
     * @param string $field A dot separated path to get additional data on.
     * @return array An array of data describing the additional attributes on a field.
     */
    public function attributes($field);

    /**
     * Check whether or not a field has an error attached to it
     *
     * @param string $field A dot separated path to check errors on.
     * @return bool Returns true if the errors for the field are not empty.
     */
    public function hasError($field);

    /**
     * Get the errors for a given field
     *
     * @param string $field A dot separated path to check errors on.
     * @return array An array of errors, an empty array will be returned when the
     *    context has no errors.
     */
    public function error($field);
}

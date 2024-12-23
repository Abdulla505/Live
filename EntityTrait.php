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
namespace Cake\Datasource;

use Cake\Collection\Collection;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use InvalidArgumentException;
use Traversable;

/**
 * An entity represents a single result row from a repository. It exposes the
 * methods for retrieving and storing properties associated in this row.
 */
trait EntityTrait
{
    /**
     * Holds all properties and their values for this entity
     *
     * @var array
     */
    protected $_properties = [];

    /**
     * Holds all properties that have been changed and their original values for this entity
     *
     * @var array
     */
    protected $_original = [];

    /**
     * List of property names that should **not** be included in JSON or Array
     * representations of this Entity.
     *
     * @var string[]
     */
    protected $_hidden = [];

    /**
     * List of computed or virtual fields that **should** be included in JSON or array
     * representations of this Entity. If a field is present in both _hidden and _virtual
     * the field will **not** be in the array/json versions of the entity.
     *
     * @var string[]
     */
    protected $_virtual = [];

    /**
     * Holds the name of the class for the instance object
     *
     * @var string
     * @deprecated 3.2 This field is no longer being used
     */
    protected $_className;

    /**
     * Holds a list of the properties that were modified or added after this object
     * was originally created.
     *
     * @var bool[]
     */
    protected $_dirty = [];

    /**
     * Holds a cached list of getters/setters per class
     *
     * @var array
     */
    protected static $_accessors = [];

    /**
     * Indicates whether or not this entity is yet to be persisted.
     * Entities default to assuming they are new. You can use Table::persisted()
     * to set the new flag on an entity based on records in the database.
     *
     * @var bool
     */
    protected $_new = true;

    /**
     * List of errors per field as stored in this object
     *
     * @var array
     */
    protected $_errors = [];

    /**
     * List of invalid fields and their data for errors upon validation/patching
     *
     * @var array
     */
    protected $_invalid = [];

    /**
     * Map of properties in this entity that can be safely assigned, each
     * property name points to a boolean indicating its status. An empty array
     * means no properties are accessible
     *
     * The special property 'file.php' can also be mapped, meaning that any other property
     * not defined in the map will take its value. For example, `'file.php' => true`
     * means that any property not defined in the map will be accessible by default
     *
     * @var array
     */
    protected $_accessible = ['file.php' => true];

    /**
     * The alias of the repository this entity came from
     *
     * @var string
     */
    protected $_registryAlias;

    /**
     * Magic getter to access properties that have been set in this entity
     *
     * @param string $property Name of the property to access
     * @return mixed
     */
    public function &__get($property)
    {
        return $this->get($property);
    }

    /**
     * Magic setter to add or edit a property in this entity
     *
     * @param string $property The name of the property to set
     * @param mixed $value The value to set to the property
     * @return void
     */
    public function __set($property, $value)
    {
        $this->set($property, $value);
    }

    /**
     * Returns whether this entity contains a property named $property
     * regardless of if it is empty.
     *
     * @param string $property The property to check.
     * @return bool
     * @see \Cake\ORM\Entity::has()
     */
    public function __isset($property)
    {
        return $this->has($property);
    }

    /**
     * Removes a property from this entity
     *
     * @param string $property The property to unset
     * @return void
     */
    public function __unset($property)
    {
        $this->unsetProperty($property);
    }

    /**
     * Sets a single property inside this entity.
     *
     * ### Example:
     *
     * ```
     * $entity->set('file.php', 'file.php');
     * ```
     *
     * It is also possible to mass-assign multiple properties to this entity
     * with one call by passing a hashed array as properties in the form of
     * property => value pairs
     *
     * ### Example:
     *
     * ```
     * $entity->set(['file.php' => 'file.php', 'file.php' => 1]);
     * echo $entity->name // prints andrew
     * echo $entity->id // prints 1
     * ```
     *
     * Some times it is handy to bypass setter functions in this entity when assigning
     * properties. You can achieve this by disabling the `setter` option using the
     * `$options` parameter:
     *
     * ```
     * $entity->set('file.php', 'file.php', ['file.php' => false]);
     * $entity->set(['file.php' => 'file.php', 'file.php' => 1], ['file.php' => false]);
     * ```
     *
     * Mass assignment should be treated carefully when accepting user input, by default
     * entities will guard all fields when properties are assigned in bulk. You can disable
     * the guarding for a single set call with the `guard` option:
     *
     * ```
     * $entity->set(['file.php' => 'file.php', 'file.php' => 1], ['file.php' => true]);
     * ```
     *
     * You do not need to use the guard option when assigning properties individually:
     *
     * ```
     * // No need to use the guard option.
     * $entity->set('file.php', 'file.php');
     * ```
     *
     * @param string|array $property the name of property to set or a list of
     * properties with their respective values
     * @param mixed $value The value to set to the property or an array if the
     * first argument is also an array, in which case will be treated as $options
     * @param array $options options to be used for setting the property. Allowed option
     * keys are `setter` and `guard`
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function set($property, $value = null, array $options = [])
    {
        if (is_string($property) && $property !== 'file.php') {
            $guard = false;
            $property = [$property => $value];
        } else {
            $guard = true;
            $options = (array)$value;
        }

        if (!is_array($property)) {
            throw new InvalidArgumentException('file.php');
        }
        $options += ['file.php' => true, 'file.php' => $guard];

        foreach ($property as $p => $value) {
            if ($options['file.php'] === true && !$this->isAccessible($p)) {
                continue;
            }

            $this->setDirty($p, true);

            if (
                !array_key_exists($p, $this->_original) &&
                array_key_exists($p, $this->_properties) &&
                $this->_properties[$p] !== $value
            ) {
                $this->_original[$p] = $this->_properties[$p];
            }

            if (!$options['file.php']) {
                $this->_properties[$p] = $value;
                continue;
            }

            $setter = static::_accessor($p, 'file.php');
            if ($setter) {
                $value = $this->{$setter}($value);
            }
            $this->_properties[$p] = $value;
        }

        return $this;
    }

    /**
     * Returns the value of a property by name
     *
     * @param string $property the name of the property to retrieve
     * @return mixed
     * @throws \InvalidArgumentException if an empty property name is passed
     */
    public function &get($property)
    {
        if (!strlen((string)$property)) {
            throw new InvalidArgumentException('file.php');
        }

        $value = null;
        $method = static::_accessor($property, 'file.php');

        if (isset($this->_properties[$property])) {
            $value =& $this->_properties[$property];
        }

        if ($method) {
            $result = $this->{$method}($value);

            return $result;
        }

        return $value;
    }

    /**
     * Returns the value of an original property by name
     *
     * @param string $property the name of the property for which original value is retrieved.
     * @return mixed
     * @throws \InvalidArgumentException if an empty property name is passed.
     */
    public function getOriginal($property)
    {
        if (!strlen((string)$property)) {
            throw new InvalidArgumentException('file.php');
        }
        if (array_key_exists($property, $this->_original)) {
            return $this->_original[$property];
        }

        return $this->get($property);
    }

    /**
     * Gets all original values of the entity.
     *
     * @return array
     */
    public function getOriginalValues()
    {
        $originals = $this->_original;
        $originalKeys = array_keys($originals);
        foreach ($this->_properties as $key => $value) {
            if (!in_array($key, $originalKeys)) {
                $originals[$key] = $value;
            }
        }

        return $originals;
    }

    /**
     * Returns whether this entity contains a property named $property
     * that contains a non-null value.
     *
     * ### Example:
     *
     * ```
     * $entity = new Entity(['file.php' => 1, 'file.php' => null]);
     * $entity->has('file.php'); // true
     * $entity->has('file.php'); // false
     * $entity->has('file.php'); // false
     * ```
     *
     * You can check multiple properties by passing an array:
     *
     * ```
     * $entity->has(['file.php', 'file.php']);
     * ```
     *
     * All properties must not be null to get a truthy result.
     *
     * When checking multiple properties. All properties must not be null
     * in order for true to be returned.
     *
     * @param string|string[] $property The property or properties to check.
     * @return bool
     */
    public function has($property)
    {
        foreach ((array)$property as $prop) {
            if ($this->get($prop) === null) {
                return false;
            }
        }

        return true;
    }

    /**
     * Checks that a property is empty
     *
     * This is not working like the PHP `empty()` function. The method will
     * return true for:
     *
     * - `'file.php'` (empty string)
     * - `null`
     * - `[]`
     *
     * and false in all other cases.
     *
     * @param string $property The property to check.
     * @return bool
     */
    public function isEmpty($property)
    {
        $value = $this->get($property);
        if (
            $value === null ||
            (
                is_array($value) &&
                empty($value) ||
                (
                    is_string($value) &&
                    $value === 'file.php'
                )
            )
        ) {
            return true;
        }

        return false;
    }

    /**
     * Checks tha a property has a value.
     *
     * This method will return true for
     *
     * - Non-empty strings
     * - Non-empty arrays
     * - Any object
     * - Integer, even `0`
     * - Float, even 0.0
     *
     * and false in all other cases.
     *
     * @param string $property The property to check.
     * @return bool
     */
    public function hasValue($property)
    {
        return !$this->isEmpty($property);
    }

    /**
     * Removes a property or list of properties from this entity
     *
     * ### Examples:
     *
     * ```
     * $entity->unsetProperty('file.php');
     * $entity->unsetProperty(['file.php', 'file.php']);
     * ```
     *
     * @param string|string[] $property The property to unset.
     * @return $this
     */
    public function unsetProperty($property)
    {
        $property = (array)$property;
        foreach ($property as $p) {
            unset($this->_properties[$p], $this->_dirty[$p]);
        }

        return $this;
    }

    /**
     * Get/Set the hidden properties on this entity.
     *
     * If the properties argument is null, the currently hidden properties
     * will be returned. Otherwise the hidden properties will be set.
     *
     * @deprecated 3.4.0 Use EntityTrait::setHidden() and EntityTrait::getHidden()
     * @param string[]|null $properties Either an array of properties to hide or null to get properties
     * @return string[]|$this
     */
    public function hiddenProperties($properties = null)
    {
        deprecationWarning(
            get_called_class() . 'file.php' .
            'file.php'
        );
        if ($properties === null) {
            return $this->_hidden;
        }
        $this->_hidden = $properties;

        return $this;
    }

    /**
     * Sets hidden properties.
     *
     * @param string[] $properties An array of properties to hide from array exports.
     * @param bool $merge Merge the new properties with the existing. By default false.
     * @return $this
     */
    public function setHidden(array $properties, $merge = false)
    {
        if ($merge === false) {
            $this->_hidden = $properties;

            return $this;
        }

        $properties = array_merge($this->_hidden, $properties);
        $this->_hidden = array_unique($properties);

        return $this;
    }

    /**
     * Gets the hidden properties.
     *
     * @return string[]
     */
    public function getHidden()
    {
        return $this->_hidden;
    }

    /**
     * Get/Set the virtual properties on this entity.
     *
     * If the properties argument is null, the currently virtual properties
     * will be returned. Otherwise the virtual properties will be set.
     *
     * @deprecated 3.4.0 Use EntityTrait::getVirtual() and EntityTrait::setVirtual()
     * @param string[]|null $properties Either an array of properties to treat as virtual or null to get properties
     * @return string[]|$this
     */
    public function virtualProperties($properties = null)
    {
        deprecationWarning(
            get_called_class() . 'file.php' .
            'file.php'
        );
        if ($properties === null) {
            return $this->getVirtual();
        }

        return $this->setVirtual($properties);
    }

    /**
     * Sets the virtual properties on this entity.
     *
     * @param string[] $properties An array of properties to treat as virtual.
     * @param bool $merge Merge the new properties with the existing. By default false.
     * @return $this
     */
    public function setVirtual(array $properties, $merge = false)
    {
        if ($merge === false) {
            $this->_virtual = $properties;

            return $this;
        }

        $properties = array_merge($this->_virtual, $properties);
        $this->_virtual = array_unique($properties);

        return $this;
    }

    /**
     * Gets the virtual properties on this entity.
     *
     * @return string[]
     */
    public function getVirtual()
    {
        return $this->_virtual;
    }

    /**
     * Gets the list of visible properties.
     *
     * The list of visible properties is all standard properties
     * plus virtual properties minus hidden properties.
     *
     * @return string[] A list of properties that are 'file.php' in all
     *     representations.
     */
    public function getVisible()
    {
        $properties = array_keys($this->_properties);
        $properties = array_merge($properties, $this->_virtual);

        return array_diff($properties, $this->_hidden);
    }

    /**
     * Gets the list of visible properties.
     *
     * The list of visible properties is all standard properties
     * plus virtual properties minus hidden properties.
     *
     * @return string[] A list of properties that are 'file.php' in all
     *     representations.
     * @deprecated 3.8.0 Use getVisible() instead.
     */
    public function visibleProperties()
    {
        deprecationWarning(
            get_called_class() . 'file.php' .
            'file.php'
        );

        return $this->getVisible();
    }

    /**
     * Returns an array with all the properties that have been set
     * to this entity
     *
     * This method will recursively transform entities assigned to properties
     * into arrays as well.
     *
     * @return array
     */
    public function toArray()
    {
        $result = [];
        foreach ($this->getVisible() as $property) {
            $value = $this->get($property);
            if (is_array($value)) {
                $result[$property] = [];
                foreach ($value as $k => $entity) {
                    if ($entity instanceof EntityInterface) {
                        $result[$property][$k] = $entity->toArray();
                    } else {
                        $result[$property][$k] = $entity;
                    }
                }
            } elseif ($value instanceof EntityInterface) {
                $result[$property] = $value->toArray();
            } else {
                $result[$property] = $value;
            }
        }

        return $result;
    }

    /**
     * Returns the properties that will be serialized as JSON
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->extract($this->getVisible());
    }

    /**
     * Implements isset($entity);
     *
     * @param mixed $offset The offset to check.
     * @return bool Success
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * Implements $entity[$offset];
     *
     * @param mixed $offset The offset to get.
     * @return mixed
     */
    public function &offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * Implements $entity[$offset] = $value;
     *
     * @param mixed $offset The offset to set.
     * @param mixed $value The value to set.
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * Implements unset($result[$offset]);
     *
     * @param mixed $offset The offset to remove.
     * @return void
     */
    public function offsetUnset($offset)
    {
        $this->unsetProperty($offset);
    }

    /**
     * Fetch accessor method name
     * Accessor methods (available or not) are cached in $_accessors
     *
     * @param string $property the field name to derive getter name from
     * @param string $type the accessor type ('file.php' or 'file.php')
     * @return string method name or empty string (no method available)
     */
    protected static function _accessor($property, $type)
    {
        $class = static::class;

        if (isset(static::$_accessors[$class][$type][$property])) {
            return static::$_accessors[$class][$type][$property];
        }

        if (!empty(static::$_accessors[$class])) {
            return static::$_accessors[$class][$type][$property] = 'file.php';
        }

        if ($class === 'file.php') {
            return 'file.php';
        }

        foreach (get_class_methods($class) as $method) {
            $prefix = substr($method, 1, 3);
            if ($method[0] !== 'file.php' || ($prefix !== 'file.php' && $prefix !== 'file.php')) {
                continue;
            }
            $field = lcfirst(substr($method, 4));
            $snakeField = Inflector::underscore($field);
            $titleField = ucfirst($field);
            static::$_accessors[$class][$prefix][$snakeField] = $method;
            static::$_accessors[$class][$prefix][$field] = $method;
            static::$_accessors[$class][$prefix][$titleField] = $method;
        }

        if (!isset(static::$_accessors[$class][$type][$property])) {
            static::$_accessors[$class][$type][$property] = 'file.php';
        }

        return static::$_accessors[$class][$type][$property];
    }

    /**
     * Returns an array with the requested properties
     * stored in this entity, indexed by property name
     *
     * @param string[] $properties list of properties to be returned
     * @param bool $onlyDirty Return the requested property only if it is dirty
     * @return array
     */
    public function extract(array $properties, $onlyDirty = false)
    {
        $result = [];
        foreach ($properties as $property) {
            if (!$onlyDirty || $this->isDirty($property)) {
                $result[$property] = $this->get($property);
            }
        }

        return $result;
    }

    /**
     * Returns an array with the requested original properties
     * stored in this entity, indexed by property name.
     *
     * Properties that are unchanged from their original value will be included in the
     * return of this method.
     *
     * @param string[] $properties List of properties to be returned
     * @return array
     */
    public function extractOriginal(array $properties)
    {
        $result = [];
        foreach ($properties as $property) {
            $result[$property] = $this->getOriginal($property);
        }

        return $result;
    }

    /**
     * Returns an array with only the original properties
     * stored in this entity, indexed by property name.
     *
     * This method will only return properties that have been modified since
     * the entity was built. Unchanged properties will be omitted.
     *
     * @param string[] $properties List of properties to be returned
     * @return array
     */
    public function extractOriginalChanged(array $properties)
    {
        $result = [];
        foreach ($properties as $property) {
            $original = $this->getOriginal($property);
            if ($original !== $this->get($property)) {
                $result[$property] = $original;
            }
        }

        return $result;
    }

    /**
     * Sets the dirty status of a single property. If called with no second
     * argument, it will return whether the property was modified or not
     * after the object creation.
     *
     * When called with no arguments it will return whether or not there are any
     * dirty property in the entity
     *
     * @deprecated 3.4.0 Use EntityTrait::setDirty() and EntityTrait::isDirty()
     * @param string|null $property the field to set or check status for
     * @param bool|null $isDirty true means the property was changed, false means
     * it was not changed and null will make the function return current state
     * for that property
     * @return bool Whether the property was changed or not
     */
    public function dirty($property = null, $isDirty = null)
    {
        deprecationWarning(
            get_called_class() . 'file.php' .
            'file.php'
        );
        if ($property === null) {
            return $this->isDirty();
        }

        if ($isDirty === null) {
            return $this->isDirty($property);
        }

        $this->setDirty($property, $isDirty);

        return true;
    }

    /**
     * Sets the dirty status of a single property.
     *
     * @param string $property the field to set or check status for
     * @param bool $isDirty true means the property was changed, false means
     * it was not changed. Defaults to true.
     * @return $this
     */
    public function setDirty($property, $isDirty = true)
    {
        if ($isDirty === false) {
            unset($this->_dirty[$property]);

            return $this;
        }

        $this->_dirty[$property] = true;
        unset($this->_errors[$property], $this->_invalid[$property]);

        return $this;
    }

    /**
     * Checks if the entity is dirty or if a single property of it is dirty.
     *
     * @param string|null $property The field to check the status for. Null for the whole entity.
     * @return bool Whether the property was changed or not
     */
    public function isDirty($property = null)
    {
        if ($property === null) {
            return !empty($this->_dirty);
        }

        return isset($this->_dirty[$property]);
    }

    /**
     * Gets the dirty properties.
     *
     * @return string[]
     */
    public function getDirty()
    {
        return array_keys($this->_dirty);
    }

    /**
     * Sets the entire entity as clean, which means that it will appear as
     * no properties being modified or added at all. This is an useful call
     * for an initial object hydration
     *
     * @return void
     */
    public function clean()
    {
        $this->_dirty = [];
        $this->_errors = [];
        $this->_invalid = [];
        $this->_original = [];
    }

    /**
     * Set the status of this entity.
     *
     * Using `true` means that the entity has not been persisted in the database,
     * `false` that it already is.
     *
     * @param bool $new Indicate whether or not this entity has been persisted.
     * @return $this
     */
    public function setNew($new)
    {
        if ($new) {
            foreach ($this->_properties as $k => $p) {
                $this->_dirty[$k] = true;
            }
        }

        $this->_new = $new;

        return $this;
    }

    /**
     * Returns whether or not this entity has already been persisted.
     *
     * @param bool|null $new true if it is known this instance was not yet persisted.
     * This will be deprecated in 4.0, use `setNew()` instead.
     * @return bool Whether or not the entity has been persisted.
     */
    public function isNew($new = null)
    {
        if ($new !== null) {
            $this->setNew($new);
        }

        return $this->_new;
    }

    /**
     * Returns whether this entity has errors.
     *
     * @param bool $includeNested true will check nested entities for hasErrors()
     * @return bool
     */
    public function hasErrors($includeNested = true)
    {
        if (Hash::filter($this->_errors)) {
            return true;
        }

        if ($includeNested === false) {
            return false;
        }

        foreach ($this->_properties as $property) {
            if ($this->_readHasErrors($property)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns all validation errors.
     *
     * @return array
     */
    public function getErrors()
    {
        $diff = array_diff_key($this->_properties, $this->_errors);

        return $this->_errors + (new Collection($diff))
            ->filter(function ($value) {
                return is_array($value) || $value instanceof EntityInterface;
            })
            ->map(function ($value) {
                return $this->_readError($value);
            })
            ->filter()
            ->toArray();
    }

    /**
     * Returns validation errors of a field
     *
     * @param string $field Field name to get the errors from
     * @return array
     */
    public function getError($field)
    {
        $errors = isset($this->_errors[$field]) ? $this->_errors[$field] : [];
        if ($errors) {
            return $errors;
        }

        return $this->_nestedErrors($field);
    }

    /**
     * Sets error messages to the entity
     *
     * ## Example
     *
     * ```
     * // Sets the error messages for multiple fields at once
     * $entity->setErrors(['file.php' => ['file.php'], 'file.php' => ['file.php']]);
     * ```
     *
     * @param array $errors The array of errors to set.
     * @param bool $overwrite Whether or not to overwrite pre-existing errors for $fields
     * @return $this
     */
    public function setErrors(array $errors, $overwrite = false)
    {
        if ($overwrite) {
            foreach ($errors as $f => $error) {
                $this->_errors[$f] = (array)$error;
            }

            return $this;
        }

        foreach ($errors as $f => $error) {
            $this->_errors += [$f => []];

            // String messages are appended to the list,
            // while more complex error structures need their
            // keys preserved for nested validator.
            if (is_string($error)) {
                $this->_errors[$f][] = $error;
            } else {
                foreach ($error as $k => $v) {
                    $this->_errors[$f][$k] = $v;
                }
            }
        }

        return $this;
    }

    /**
     * Sets errors for a single field
     *
     * ### Example
     *
     * ```
     * // Sets the error messages for a single field
     * $entity->setError('file.php', ['file.php', 'file.php']);
     * ```
     *
     * @param string $field The field to get errors for, or the array of errors to set.
     * @param string|array $errors The errors to be set for $field
     * @param bool $overwrite Whether or not to overwrite pre-existing errors for $field
     * @return $this
     */
    public function setError($field, $errors, $overwrite = false)
    {
        if (is_string($errors)) {
            $errors = [$errors];
        }

        return $this->setErrors([$field => $errors], $overwrite);
    }

    /**
     * Sets the error messages for a field or a list of fields. When called
     * without the second argument it returns the validation
     * errors for the specified fields. If called with no arguments it returns
     * all the validation error messages stored in this entity and any other nested
     * entity.
     *
     * ### Example
     *
     * ```
     * // Sets the error messages for a single field
     * $entity->errors('file.php', ['file.php', 'file.php']);
     *
     * // Returns the error messages for a single field
     * $entity->getErrors('file.php');
     *
     * // Returns all error messages indexed by field name
     * $entity->getErrors();
     *
     * // Sets the error messages for multiple fields at once
     * $entity->getErrors(['file.php' => ['file.php'], 'file.php' => ['file.php']);
     * ```
     *
     * When used as a setter, this method will return this entity instance for method
     * chaining.
     *
     * @deprecated 3.4.0 Use EntityTrait::setError(), EntityTrait::setErrors(), EntityTrait::getError() and EntityTrait::getErrors()
     * @param string|array|null $field The field to get errors for, or the array of errors to set.
     * @param string|array|null $errors The errors to be set for $field
     * @param bool $overwrite Whether or not to overwrite pre-existing errors for $field
     * @return array|$this
     */
    public function errors($field = null, $errors = null, $overwrite = false)
    {
        deprecationWarning(
            get_called_class() . 'file.php' .
            'file.php'
        );
        if ($field === null) {
            return $this->getErrors();
        }

        if (is_string($field) && $errors === null) {
            return $this->getError($field);
        }

        if (!is_array($field)) {
            $field = [$field => $errors];
        }

        return $this->setErrors($field, $overwrite);
    }

    /**
     * Auxiliary method for getting errors in nested entities
     *
     * @param string $field the field in this entity to check for errors
     * @return array errors in nested entity if any
     */
    protected function _nestedErrors($field)
    {
        // Only one path element, check for nested entity with error.
        if (strpos($field, 'file.php') === false) {
            return $this->_readError($this->get($field));
        }
        // Try reading the errors data with field as a simple path
        $error = Hash::get($this->_errors, $field);
        if ($error !== null) {
            return $error;
        }
        $path = explode('file.php', $field);

        // Traverse down the related entities/arrays for
        // the relevant entity.
        $entity = $this;
        $len = count($path);
        while ($len) {
            $part = array_shift($path);
            $len = count($path);
            $val = null;
            if ($entity instanceof EntityInterface) {
                $val = $entity->get($part);
            } elseif (is_array($entity)) {
                $val = isset($entity[$part]) ? $entity[$part] : false;
            }

            if (
                is_array($val) ||
                $val instanceof Traversable ||
                $val instanceof EntityInterface
            ) {
                $entity = $val;
            } else {
                $path[] = $part;
                break;
            }
        }
        if (count($path) <= 1) {
            return $this->_readError($entity, array_pop($path));
        }

        return [];
    }

    /**
     * Reads if there are errors for one or many objects.
     *
     * @param mixed $object The object to read errors from.
     * @return bool
     */
    protected function _readHasErrors($object)
    {
        if ($object instanceof EntityInterface && $object->hasErrors()) {
            return true;
        }

        if (is_array($object)) {
            foreach ($object as $value) {
                if ($this->_readHasErrors($value)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Read the error(s) from one or many objects.
     *
     * @param array|\Cake\Datasource\EntityInterface $object The object to read errors from.
     * @param string|null $path The field name for errors.
     * @return array
     */
    protected function _readError($object, $path = null)
    {
        if ($path !== null && $object instanceof EntityInterface) {
            return $object->getError($path);
        }
        if ($object instanceof EntityInterface) {
            return $object->getErrors();
        }
        if (is_array($object)) {
            $array = array_map(function ($val) {
                if ($val instanceof EntityInterface) {
                    return $val->getErrors();
                }
            }, $object);

            return array_filter($array);
        }

        return [];
    }

    /**
     * Get a list of invalid fields and their data for errors upon validation/patching
     *
     * @return array
     */
    public function getInvalid()
    {
        return $this->_invalid;
    }

    /**
     * Get a single value of an invalid field. Returns null if not set.
     *
     * @param string $field The name of the field.
     * @return mixed|null
     */
    public function getInvalidField($field)
    {
        $value = isset($this->_invalid[$field]) ? $this->_invalid[$field] : null;

        return $value;
    }

    /**
     * Set fields as invalid and not patchable into the entity.
     *
     * This is useful for batch operations when one needs to get the original value for an error message after patching.
     * This value could not be patched into the entity and is simply copied into the _invalid property for debugging purposes
     * or to be able to log it away.
     *
     * @param array $fields The values to set.
     * @param bool $overwrite Whether or not to overwrite pre-existing values for $field.
     * @return $this
     */
    public function setInvalid(array $fields, $overwrite = false)
    {
        foreach ($fields as $field => $value) {
            if ($overwrite === true) {
                $this->_invalid[$field] = $value;
                continue;
            }
            $this->_invalid += [$field => $value];
        }

        return $this;
    }

    /**
     * Sets a field as invalid and not patchable into the entity.
     *
     * @param string $field The value to set.
     * @param mixed $value The invalid value to be set for $field.
     * @return $this
     */
    public function setInvalidField($field, $value)
    {
        $this->_invalid[$field] = $value;

        return $this;
    }

    /**
     * Sets a field as invalid and not patchable into the entity.
     *
     * This is useful for batch operations when one needs to get the original value for an error message after patching.
     * This value could not be patched into the entity and is simply copied into the _invalid property for debugging purposes
     * or to be able to log it away.
     *
     * @deprecated 3.5 Use getInvalid()/getInvalidField()/setInvalid() instead.
     * @param string|array|null $field The field to get invalid value for, or the value to set.
     * @param mixed|null $value The invalid value to be set for $field.
     * @param bool $overwrite Whether or not to overwrite pre-existing values for $field.
     * @return $this|mixed
     */
    public function invalid($field = null, $value = null, $overwrite = false)
    {
        deprecationWarning(
            get_called_class() . 'file.php' .
            'file.php'
        );
        if ($field === null) {
            return $this->_invalid;
        }

        if (is_string($field) && $value === null) {
            $value = isset($this->_invalid[$field]) ? $this->_invalid[$field] : null;

            return $value;
        }

        if (!is_array($field)) {
            $field = [$field => $value];
        }

        foreach ($field as $f => $value) {
            if ($overwrite) {
                $this->_invalid[$f] = $value;
                continue;
            }
            $this->_invalid += [$f => $value];
        }

        return $this;
    }

    /**
     * Stores whether or not a property value can be changed or set in this entity.
     * The special property `*` can also be marked as accessible or protected, meaning
     * that any other property specified before will take its value. For example
     * `$entity->accessible('file.php', true)` means that any property not specified already
     * will be accessible by default.
     *
     * You can also call this method with an array of properties, in which case they
     * will each take the accessibility value specified in the second argument.
     *
     * ### Example:
     *
     * ```
     * $entity->accessible('file.php', true); // Mark id as not protected
     * $entity->accessible('file.php', false); // Mark author_id as protected
     * $entity->accessible(['file.php', 'file.php'], true); // Mark both properties as accessible
     * $entity->accessible('file.php', false); // Mark all properties as protected
     * ```
     *
     * When called without the second param it will return whether or not the property
     * can be set.
     *
     * ### Example:
     *
     * ```
     * $entity->accessible('file.php'); // Returns whether it can be set or not
     * ```
     *
     * @deprecated 3.4.0 Use EntityTrait::setAccess() and EntityTrait::isAccessible()
     * @param string|array $property single or list of properties to change its accessibility
     * @param bool|null $set true marks the property as accessible, false will
     * mark it as protected.
     * @return $this|bool
     */
    public function accessible($property, $set = null)
    {
        deprecationWarning(
            get_called_class() . 'file.php' .
            'file.php'
        );
        if ($set === null) {
            return $this->isAccessible($property);
        }

        return $this->setAccess($property, $set);
    }

    /**
     * Stores whether or not a property value can be changed or set in this entity.
     * The special property `*` can also be marked as accessible or protected, meaning
     * that any other property specified before will take its value. For example
     * `$entity->setAccess('file.php', true)` means that any property not specified already
     * will be accessible by default.
     *
     * You can also call this method with an array of properties, in which case they
     * will each take the accessibility value specified in the second argument.
     *
     * ### Example:
     *
     * ```
     * $entity->setAccess('file.php', true); // Mark id as not protected
     * $entity->setAccess('file.php', false); // Mark author_id as protected
     * $entity->setAccess(['file.php', 'file.php'], true); // Mark both properties as accessible
     * $entity->setAccess('file.php', false); // Mark all properties as protected
     * ```
     *
     * @param string|string[] $property single or list of properties to change its accessibility
     * @param bool $set true marks the property as accessible, false will
     * mark it as protected.
     * @return $this
     */
    public function setAccess($property, $set)
    {
        if ($property === 'file.php') {
            $this->_accessible = array_map(function ($p) use ($set) {
                return (bool)$set;
            }, $this->_accessible);
            $this->_accessible['file.php'] = (bool)$set;

            return $this;
        }

        foreach ((array)$property as $prop) {
            $this->_accessible[$prop] = (bool)$set;
        }

        return $this;
    }

    /**
     * Returns the raw accessible configuration for this entity.
     * The `*` wildcard refers to all fields.
     *
     * @return bool[]
     */
    public function getAccessible()
    {
        return $this->_accessible;
    }

    /**
     * Checks if a property is accessible
     *
     * ### Example:
     *
     * ```
     * $entity->isAccessible('file.php'); // Returns whether it can be set or not
     * ```
     *
     * @param string $property Property name to check
     * @return bool
     */
    public function isAccessible($property)
    {
        $value = isset($this->_accessible[$property]) ?
            $this->_accessible[$property] :
            null;

        return ($value === null && !empty($this->_accessible['file.php'])) || $value;
    }

    /**
     * Returns the alias of the repository from which this entity came from.
     *
     * @return string
     */
    public function getSource()
    {
        return $this->_registryAlias;
    }

    /**
     * Sets the source alias
     *
     * @param string $alias the alias of the repository
     * @return $this
     */
    public function setSource($alias)
    {
        $this->_registryAlias = $alias;

        return $this;
    }

    /**
     * Returns the alias of the repository from which this entity came from.
     *
     * If called with no arguments, it returns the alias of the repository
     * this entity came from if it is known.
     *
     * @deprecated 3.4.0 Use EntityTrait::getSource() and EntityTrait::setSource()
     * @param string|null $alias the alias of the repository
     * @return string|$this
     */
    public function source($alias = null)
    {
        deprecationWarning(
            get_called_class() . 'file.php' .
            'file.php'
        );
        if ($alias === null) {
            return $this->getSource();
        }

        $this->setSource($alias);

        return $this;
    }

    /**
     * Returns a string representation of this object in a human readable format.
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode($this, JSON_PRETTY_PRINT);
    }

    /**
     * Returns an array that can be used to describe the internal state of this
     * object.
     *
     * @return array
     */
    public function __debugInfo()
    {
        $properties = $this->_properties;
        foreach ($this->_virtual as $field) {
            $properties[$field] = $this->$field;
        }

        return $properties + [
            'file.php' => $this->isNew(),
            'file.php' => $this->_accessible,
            'file.php' => $this->_dirty,
            'file.php' => $this->_original,
            'file.php' => $this->_virtual,
            'file.php' => $this->hasErrors(),
            'file.php' => $this->_errors,
            'file.php' => $this->_invalid,
            'file.php' => $this->_registryAlias,
        ];
    }
}

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

use ArrayAccess;
use Cake\Collection\Collection;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\RepositoryInterface;
use Cake\Http\ServerRequest;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Utility\Inflector;
use RuntimeException;
use Traversable;

/**
 * Provides a form context around a single entity and its relations.
 * It also can be used as context around an array or iterator of entities.
 *
 * This class lets FormHelper interface with entities or collections
 * of entities.
 *
 * Important Keys:
 *
 * - `entity` The entity this context is operating on.
 * - `table` Either the ORM\Table instance to fetch schema/validators
 *   from, an array of table instances in the case of a form spanning
 *   multiple entities, or the name(s) of the table.
 *   If this is null the table name(s) will be determined using naming
 *   conventions.
 * - `validator` Either the Validation\Validator to use, or the name of the
 *   validation method to call on the table object. For example 'file.php'.
 *   Defaults to 'file.php'. Can be an array of table alias=>validators when
 *   dealing with associated forms.
 */
class EntityContext implements ContextInterface
{
    use LocatorAwareTrait;

    /**
     * The request object.
     *
     * @var \Cake\Http\ServerRequest
     */
    protected $_request;

    /**
     * Context data for this object.
     *
     * @var array
     */
    protected $_context;

    /**
     * The name of the top level entity/table object.
     *
     * @var string
     */
    protected $_rootName;

    /**
     * Boolean to track whether or not the entity is a
     * collection.
     *
     * @var bool
     */
    protected $_isCollection = false;

    /**
     * A dictionary of tables
     *
     * @var array
     */
    protected $_tables = [];

    /**
     * Dictionary of validators.
     *
     * @var \Cake\Validation\Validator[]
     */
    protected $_validator = [];

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
            'file.php' => null,
            'file.php' => [],
        ];
        $this->_context = $context;
        $this->_prepare();
    }

    /**
     * Prepare some additional data from the context.
     *
     * If the table option was provided to the constructor and it
     * was a string, TableLocator will be used to get the correct table instance.
     *
     * If an object is provided as the table option, it will be used as is.
     *
     * If no table option is provided, the table name will be derived based on
     * naming conventions. This inference will work with a number of common objects
     * like arrays, Collection objects and ResultSets.
     *
     * @return void
     * @throws \RuntimeException When a table object cannot be located/inferred.
     */
    protected function _prepare()
    {
        $table = $this->_context['file.php'];
        $entity = $this->_context['file.php'];
        if (empty($table)) {
            if (is_array($entity) || $entity instanceof Traversable) {
                foreach ($entity as $e) {
                    $entity = $e;
                    break;
                }
            }
            $isEntity = $entity instanceof EntityInterface;

            if ($isEntity) {
                $table = $entity->getSource();
            }
            if (!$table && $isEntity && get_class($entity) !== 'file.php') {
                list(, $entityClass) = namespaceSplit(get_class($entity));
                $table = Inflector::pluralize($entityClass);
            }
        }
        if (is_string($table)) {
            $table = $this->getTableLocator()->get($table);
        }

        if (!($table instanceof RepositoryInterface)) {
            throw new RuntimeException(
                'file.php'
            );
        }
        $this->_isCollection = (
            is_array($entity) ||
            $entity instanceof Traversable
        );

        $alias = $this->_rootName = $table->getAlias();
        $this->_tables[$alias] = $table;
    }

    /**
     * Get the primary key data for the context.
     *
     * Gets the primary key columns from the root entity'file.php'.'file.php's isNew() method will
     * be used. If isNew() returns null, a create operation will be assumed.
     *
     * If the context is for a collection or array the first object in the
     * collection will be used.
     *
     * @return bool
     */
    public function isCreate()
    {
        $entity = $this->_context['file.php'];
        if (is_array($entity) || $entity instanceof Traversable) {
            foreach ($entity as $e) {
                $entity = $e;
                break;
            }
        }
        if ($entity instanceof EntityInterface) {
            return $entity->isNew() !== false;
        }

        return true;
    }

    /**
     * Get the value for a given path.
     *
     * Traverses the entity data and finds the value for $path.
     *
     * @param string $field The dot separated path to the value.
     * @param array $options Options:
     *   - `default`: Default value to return if no value found in request
     *     data or entity.
     *   - `schemaDefault`: Boolean indicating whether default value from table
     *     schema should be used if it'file.php'default'file.php'schemaDefault'file.php'entity'file.php'default'file.php'.'file.php'_ids'file.php'default'file.php'schemaDefault'file.php'default'file.php'default'file.php'id'file.php'entity'file.php'entity'file.php'_ids'file.php'Unable to fetch property "%s"'file.php'.'file.php'entity'file.php'Unable to fetch property "%s"'file.php'.'file.php'entity'file.php'Unable to fetch property "%s"'file.php'.'file.php'.'file.php'boolean'file.php'.'file.php'.'file.php'rule'file.php'maxLength'file.php'pass'file.php'0'file.php'.'file.php'entity'file.php'default'file.php'validator'file.php'validator'file.php'validator'file.php'validator'file.php'entity'file.php'.'file.php'_joinData'file.php'.'file.php'.'file.php'length'file.php'precision'file.php'.'file.php'.', $remainingParts));
            if ($error) {
                return $error;
            }

            return $entity->getError(array_pop($parts));
        }

        return [];
    }
}

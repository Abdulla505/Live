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

use Cake\Core\Exception\Exception;
use Cake\Core\InstanceConfigTrait;
use Cake\Event\EventListenerInterface;
use ReflectionClass;
use ReflectionMethod;

/**
 * Base class for behaviors.
 *
 * Behaviors allow you to simulate mixins, and create
 * reusable blocks of application logic, that can be reused across
 * several models. Behaviors also provide a way to hook into model
 * callbacks and augment their behavior.
 *
 * ### Mixin methods
 *
 * Behaviors can provide mixin like features by declaring public
 * methods. These methods will be accessible on the tables the
 * behavior has been added to.
 *
 * ```
 * function doSomething($arg1, $arg2) {
 *   // do something
 * }
 * ```
 *
 * Would be called like `$table->doSomething($arg1, $arg2);`.
 *
 * ### Callback methods
 *
 * Behaviors can listen to any events fired on a Table. By default
 * CakePHP provides a number of lifecycle events your behaviors can
 * listen to:
 *
 * - `beforeFind(Event $event, Query $query, ArrayObject $options, boolean $primary)`
 *   Fired before each find operation. By stopping the event and supplying a
 *   return value you can bypass the find operation entirely. Any changes done
 *   to the $query instance will be retained for the rest of the find. The
 *   $primary parameter indicates whether or not this is the root query,
 *   or an associated query.
 *
 * - `buildValidator(Event $event, Validator $validator, string $name)`
 *   Fired when the validator object identified by $name is being built. You can use this
 *   callback to add validation rules or add validation providers.
 *
 * - `buildRules(Event $event, RulesChecker $rules)`
 *   Fired when the rules checking object for the table is being built. You can use this
 *   callback to add more rules to the set.
 *
 * - `beforeRules(Event $event, EntityInterface $entity, ArrayObject $options, $operation)`
 *   Fired before an entity is validated using by a rules checker. By stopping this event,
 *   you can return the final value of the rules checking operation.
 *
 * - `afterRules(Event $event, EntityInterface $entity, ArrayObject $options, bool $result, $operation)`
 *   Fired after the rules have been checked on the entity. By stopping this event,
 *   you can return the final value of the rules checking operation.
 *
 * - `beforeSave(Event $event, EntityInterface $entity, ArrayObject $options)`
 *   Fired before each entity is saved. Stopping this event will abort the save
 *   operation. When the event is stopped the result of the event will be returned.
 *
 * - `afterSave(Event $event, EntityInterface $entity, ArrayObject $options)`
 *   Fired after an entity is saved.
 *
 * - `beforeDelete(Event $event, EntityInterface $entity, ArrayObject $options)`
 *   Fired before an entity is deleted. By stopping this event you will abort
 *   the delete operation.
 *
 * - `afterDelete(Event $event, EntityInterface $entity, ArrayObject $options)`
 *   Fired after an entity has been deleted.
 *
 * In addition to the core events, behaviors can respond to any
 * event fired from your Table classes including custom application
 * specific ones.
 *
 * You can set the priority of a behaviors callbacks by using the
 * `priority` setting when attaching a behavior. This will set the
 * priority for all the callbacks a behavior provides.
 *
 * ### Finder methods
 *
 * Behaviors can provide finder methods that hook into a Table'file.php'slugged'file.php'implementedFinders'file.php'implementedMethods'file.php'implementedFinders'file.php'implementedMethods'file.php'The method %s is not callable on class %s'file.php'Model.beforeMarshal'file.php'beforeMarshal'file.php'Model.afterMarshal'file.php'afterMarshal'file.php'Model.beforeFind'file.php'beforeFind'file.php'Model.beforeSave'file.php'beforeSave'file.php'Model.afterSave'file.php'afterSave'file.php'Model.afterSaveCommit'file.php'afterSaveCommit'file.php'Model.beforeDelete'file.php'beforeDelete'file.php'Model.afterDelete'file.php'afterDelete'file.php'Model.afterDeleteCommit'file.php'afterDeleteCommit'file.php'Model.buildValidator'file.php'buildValidator'file.php'Model.buildRules'file.php'buildRules'file.php'Model.beforeRules'file.php'beforeRules'file.php'Model.afterRules'file.php'afterRules'file.php'priority'file.php'priority'file.php'callable'file.php'priority'file.php'this'file.php'findThis'file.php'alias'file.php'findMethodName'file.php'this'file.php'alias'file.php'implementedFinders'file.php'finders'file.php'method'file.php'method'file.php'aliasedmethod'file.php'somethingElse'file.php'implementedMethods'file.php'methods'file.php'callable'file.php'callable'file.php'finders'file.php'methods'file.php'find'file.php'finders'file.php'methods'][$methodName] = $methodName;
            }
        }

        return self::$_reflectionCache[$class] = $return;
    }
}

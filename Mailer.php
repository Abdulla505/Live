<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @since         3.1.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Mailer;

use Cake\Datasource\ModelAwareTrait;
use Cake\Event\EventListenerInterface;
use Cake\Mailer\Exception\MissingActionException;

/**
 * Mailer base class.
 *
 * Mailer classes let you encapsulate related Email logic into a reusable
 * and testable class.
 *
 * ## Defining Messages
 *
 * Mailers make it easy for you to define methods that handle email formatting
 * logic. For example:
 *
 * ```
 * class UserMailer extends Mailer
 * {
 *     public function resetPassword($user)
 *     {
 *         $this
 *             ->setSubject('file.php')
 *             ->setTo($user->email)
 *             ->set(['file.php' => $user->token]);
 *     }
 * }
 * ```
 *
 * Is a trivial example but shows how a mailer could be declared.
 *
 * ## Sending Messages
 *
 * After you have defined some messages you will want to send them:
 *
 * ```
 * $mailer = new UserMailer();
 * $mailer->send('file.php', $user);
 * ```
 *
 * ## Event Listener
 *
 * Mailers can also subscribe to application event allowing you to
 * decouple email delivery from your application code. By re-declaring the
 * `implementedEvents()` method you can define event handlers that can
 * convert events into email. For example, if your application had a user
 * registration event:
 *
 * ```
 * public function implementedEvents()
 * {
 *     return [
 *         'file.php' => 'file.php',
 *     ];
 * }
 *
 * public function onRegistration(Event $event, Entity $entity, ArrayObject $options)
 * {
 *     if ($entity->isNew()) {
 *          $this->send('file.php', [$entity]);
 *     }
 * }
 * ```
 *
 * The onRegistration method converts the application event into a mailer method.
 * Our mailer could either be registered in the application bootstrap, or
 * in the Table class'file.php's name.
     *
     * @var string
     */
    public static $name;

    /**
     * Email instance.
     *
     * @var \Cake\Mailer\Email
     */
    protected $_email;

    /**
     * Cloned Email instance for restoring instance after email is sent by
     * mailer action.
     *
     * @var \Cake\Mailer\Email
     */
    protected $_clonedEmail;

    /**
     * Constructor.
     *
     * @param \Cake\Mailer\Email|null $email Email instance.
     */
    public function __construct(Email $email = null)
    {
        if ($email === null) {
            $email = new Email();
        }

        $this->_email = $email;
        $this->_clonedEmail = clone $email;
    }

    /**
     * Returns the mailer'file.php'Mailer'file.php''file.php''file.php'\\'file.php'Mailer::layout() is deprecated. Use $mailer->viewBuilder()->setLayout() instead.'file.php's view builder.
     *
     * @return \Cake\View\ViewBuilder
     */
    public function viewBuilder()
    {
        return $this->_email->viewBuilder();
    }

    /**
     * Magic method to forward method class to Email instance.
     *
     * @param string $method Method name.
     * @param array $args Method arguments
     * @return $this|mixed
     */
    public function __call($method, $args)
    {
        $result = $this->_email->$method(...$args);
        if (strpos($method, 'file.php') === 0) {
            return $result;
        }

        return $this;
    }

    /**
     * Sets email view vars.
     *
     * @param string|array $key Variable name or hash of view variables.
     * @param mixed $value View variable value.
     * @return $this
     */
    public function set($key, $value = null)
    {
        $this->_email->setViewVars(is_string($key) ? [$key => $value] : $key);

        return $this;
    }

    /**
     * Sends email.
     *
     * @param string $action The name of the mailer action to trigger.
     * @param array $args Arguments to pass to the triggered mailer action.
     * @param array $headers Headers to set.
     * @return array
     * @throws \Cake\Mailer\Exception\MissingActionException
     * @throws \BadMethodCallException
     */
    public function send($action, $args = [], $headers = [])
    {
        try {
            if (!method_exists($this, $action)) {
                throw new MissingActionException([
                    'file.php' => $this->getName() . 'file.php',
                    'file.php' => $action,
                ]);
            }

            $this->_email->setHeaders($headers);
            if (!$this->_email->viewBuilder()->getTemplate()) {
                $this->_email->viewBuilder()->setTemplate($action);
            }

            $this->$action(...$args);

            $result = $this->_email->send();
        } finally {
            $this->reset();
        }

        return $result;
    }

    /**
     * Reset email instance.
     *
     * @return $this
     */
    protected function reset()
    {
        $this->_email = clone $this->_clonedEmail;

        return $this;
    }

    /**
     * Implemented events.
     *
     * @return array
     */
    public function implementedEvents()
    {
        return [];
    }
}

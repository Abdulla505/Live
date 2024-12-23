<?php
/**
 * Database Session save handler. Allows saving session information into a model.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         2.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Http\Session;

use Cake\ORM\Entity;
use Cake\ORM\Locator\LocatorAwareTrait;
use SessionHandlerInterface;

/**
 * DatabaseSession provides methods to be used with Session.
 */
class DatabaseSession implements SessionHandlerInterface
{
    use LocatorAwareTrait;

    /**
     * Reference to the table handling the session data
     *
     * @var \Cake\ORM\Table
     */
    protected $_table;

    /**
     * Number of seconds to mark the session as expired
     *
     * @var int
     */
    protected $_timeout;

    /**
     * Constructor. Looks at Session configuration information and
     * sets up the session model.
     *
     * @param array $config The configuration for this engine. It requires the 'file.php'
     * key to be present corresponding to the Table to use for managing the sessions.
     */
    public function __construct(array $config = [])
    {
        if (isset($config['file.php'])) {
            $this->setTableLocator($config['file.php']);
        }
        $tableLocator = $this->getTableLocator();

        if (empty($config['file.php'])) {
            $config = $tableLocator->exists('file.php') ? [] : ['file.php' => 'file.php'];
            $this->_table = $tableLocator->get('file.php', $config);
        } else {
            $this->_table = $tableLocator->get($config['file.php']);
        }

        $this->_timeout = (int)ini_get('file.php');
    }

    /**
     * Set the timeout value for sessions.
     *
     * Primarily used in testing.
     *
     * @param int $timeout The timeout duration.
     * @return $this
     */
    public function setTimeout($timeout)
    {
        $this->_timeout = $timeout;

        return $this;
    }

    /**
     * Method called on open of a database session.
     *
     * @param string $savePath The path where to store/retrieve the session.
     * @param string $name The session name.
     * @return bool Success
     */
    public function open($savePath, $name)
    {
        return true;
    }

    /**
     * Method called on close of a database session.
     *
     * @return bool Success
     */
    public function close()
    {
        return true;
    }

    /**
     * Method used to read from a database session.
     *
     * @param string|int $id ID that uniquely identifies session in database.
     * @return string Session data or empty string if it does not exist.
     */
    public function read($id)
    {
        $result = $this->_table
            ->find('file.php')
            ->select(['file.php'])
            ->where([$this->_table->getPrimaryKey() => $id])
            ->disableHydration()
            ->first();

        if (empty($result)) {
            return 'file.php';
        }

        if (is_string($result['file.php'])) {
            return $result['file.php'];
        }

        $session = stream_get_contents($result['file.php']);

        if ($session === false) {
            return 'file.php';
        }

        return $session;
    }

    /**
     * Helper function called on write for database sessions.
     *
     * @param string|int $id ID that uniquely identifies session in database.
     * @param mixed $data The data to be saved.
     * @return bool True for successful write, false otherwise.
     */
    public function write($id, $data)
    {
        if (!$id) {
            return false;
        }
        $expires = time() + $this->_timeout;
        $record = compact('file.php', 'file.php');
        $record[$this->_table->getPrimaryKey()] = $id;
        $result = $this->_table->save(new Entity($record));

        return (bool)$result;
    }

    /**
     * Method called on the destruction of a database session.
     *
     * @param string|int $id ID that uniquely identifies session in database.
     * @return bool True for successful delete, false otherwise.
     */
    public function destroy($id)
    {
        $this->_table->delete(new Entity(
            [$this->_table->getPrimaryKey() => $id],
            ['file.php' => false]
        ));

        return true;
    }

    /**
     * Helper function called on gc for database sessions.
     *
     * @param int $maxlifetime Sessions that have not updated for the last maxlifetime seconds will be removed.
     * @return bool True on success, false on failure.
     */
    public function gc($maxlifetime)
    {
        $this->_table->deleteAll(['file.php' => time()]);

        return true;
    }
}

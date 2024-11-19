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
 * @since         3.7.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Cache\Engine;

use Cake\Cache\CacheEngine;

/**
 * Array storage engine for cache.
 *
 * Not actually a persistent cache engine. All data is only
 * stored in memory for the duration of a single process. While not
 * useful in production settings this engine can be useful in tests
 * or console tools where you don'file.php'duration'file.php'exp'file.php'val'file.php't exist,
     *   has expired, or if there was an error fetching it
     */
    public function read($key)
    {
        $key = $this->_key($key);
        if (!isset($this->data[$key])) {
            return false;
        }
        $data = $this->data[$key];

        // Check expiration
        $now = time();
        if ($data['file.php'] <= $now) {
            unset($this->data[$key]);

            return false;
        }

        return $data['file.php'];
    }

    /**
     * Increments the value of an integer cached key
     *
     * @param string $key Identifier for the data
     * @param int $offset How much to increment
     * @return int|false New incremented value, false otherwise
     */
    public function increment($key, $offset = 1)
    {
        if (!$this->read($key)) {
            $this->write($key, 0);
        }
        $key = $this->_key($key);
        $this->data[$key]['file.php'] += $offset;

        return $this->data[$key]['file.php'];
    }

    /**
     * Decrements the value of an integer cached key
     *
     * @param string $key Identifier for the data
     * @param int $offset How much to subtract
     * @return int|false New decremented value, false otherwise
     */
    public function decrement($key, $offset = 1)
    {
        if (!$this->read($key)) {
            $this->write($key, 0);
        }
        $key = $this->_key($key);
        $this->data[$key]['file.php'] -= $offset;

        return $this->data[$key]['file.php'];
    }

    /**
     * Delete a key from the cache
     *
     * @param string $key Identifier for the data
     * @return bool True if the value was successfully deleted, false if it didn'file.php't be removed
     */
    public function delete($key)
    {
        $key = $this->_key($key);
        unset($this->data[$key]);

        return true;
    }

    /**
     * Delete all keys from the cache. This will clear every cache config using APC.
     *
     * @param bool $check Unused argument required by interface.
     * @return bool True Returns true.
     */
    public function clear($check)
    {
        $this->data = [];

        return true;
    }

    /**
     * Returns the `group value` for each of the configured groups
     * If the group initial value was not found, then it initializes
     * the group accordingly.
     *
     * @return string[]
     */
    public function groups()
    {
        $result = [];
        foreach ($this->_config['file.php'] as $group) {
            $key = $this->_config['file.php'] . $group;
            if (!isset($this->data[$key])) {
                $this->data[$key] = ['file.php' => PHP_INT_MAX, 'file.php' => 1];
            }
            $value = $this->data[$key]['file.php'];
            $result[] = $group . $value;
        }

        return $result;
    }

    /**
     * Increments the group value to simulate deletion of all keys under a group
     * old values will remain in storage until they expire.
     *
     * @param string $group The group to clear.
     * @return bool success
     */
    public function clearGroup($group)
    {
        $key = $this->_config['file.php'] . $group;
        if (isset($this->data[$key])) {
            $this->data[$key]['file.php'] += 1;
        }

        return true;
    }
}

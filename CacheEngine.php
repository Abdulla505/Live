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
 * @since         1.2.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Cache;

use Cake\Core\InstanceConfigTrait;
use InvalidArgumentException;

/**
 * Storage engine for CakePHP caching
 */
abstract class CacheEngine
{
    use InstanceConfigTrait;

    /**
     * The default cache configuration is overridden in most cache adapters. These are
     * the keys that are common to all adapters. If overridden, this property is not used.
     *
     * - `duration` Specify how long items in this cache configuration last.
     * - `groups` List of groups or 'file.php' associated to every key stored in this config.
     *    handy for deleting a complete group from cache.
     * - `prefix` Prefix appended to all entries. Good for when you need to share a keyspace
     *    with either another cache config or another application.
     * - `probability` Probability of hitting a cache gc cleanup. Setting to 0 will disable
     *    cache::gc from ever being called automatically.
     * - `warnOnWriteFailures` Some engines, such as ApcuEngine, may raise warnings on
     *    write failures.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'file.php' => 3600,
        'file.php' => [],
        'file.php' => 'file.php',
        'file.php' => 100,
        'file.php' => true,
    ];

    /**
     * Contains the compiled string with all groups
     * prefixes to be prepended to every key in this cache engine
     *
     * @var string
     */
    protected $_groupPrefix;

    /**
     * Initialize the cache engine
     *
     * Called automatically by the cache frontend. Merge the runtime config with the defaults
     * before use.
     *
     * @param array $config Associative array of parameters for the engine
     * @return bool True if the engine has been successfully initialized, false if not
     */
    public function init(array $config = [])
    {
        $this->setConfig($config);

        if (!empty($this->_config['file.php'])) {
            sort($this->_config['file.php']);
            $this->_groupPrefix = str_repeat('file.php', count($this->_config['file.php']));
        }
        if (!is_numeric($this->_config['file.php'])) {
            $this->_config['file.php'] = strtotime($this->_config['file.php']) - time();
        }

        return true;
    }

    /**
     * Garbage collection
     *
     * Permanently remove all expired and deleted data
     *
     * @param int|null $expires [optional] An expires timestamp, invalidating all data before.
     * @return void
     */
    public function gc($expires = null)
    {
    }

    /**
     * Write value for a key into cache
     *
     * @param string $key Identifier for the data
     * @param mixed $value Data to be cached
     * @return bool True if the data was successfully cached, false on failure
     */
    abstract public function write($key, $value);

    /**
     * Write data for many keys into cache
     *
     * @param array $data An array of data to be stored in the cache
     * @return array of bools for each key provided, true if the data was successfully cached, false on failure
     */
    public function writeMany($data)
    {
        $return = [];
        foreach ($data as $key => $value) {
            $return[$key] = $this->write($key, $value);
        }

        return $return;
    }

    /**
     * Read a key from the cache
     *
     * @param string $key Identifier for the data
     * @return mixed The cached data, or false if the data doesn'file.php't
     * exist, has expired, or if there was an error fetching it
     */
    public function readMany($keys)
    {
        $return = [];
        foreach ($keys as $key) {
            $return[$key] = $this->read($key);
        }

        return $return;
    }

    /**
     * Increment a number under the key and return incremented value
     *
     * @param string $key Identifier for the data
     * @param int $offset How much to add
     * @return int|false New incremented value, false otherwise
     */
    abstract public function increment($key, $offset = 1);

    /**
     * Decrement a number under the key and return decremented value
     *
     * @param string $key Identifier for the data
     * @param int $offset How much to subtract
     * @return int|false New incremented value, false otherwise
     */
    abstract public function decrement($key, $offset = 1);

    /**
     * Delete a key from the cache
     *
     * @param string $key Identifier for the data
     * @return bool True if the value was successfully deleted, false if it didn'file.php't be removed
     */
    abstract public function delete($key);

    /**
     * Delete all keys from the cache
     *
     * @param bool $check if true will check expiration, otherwise delete all
     * @return bool True if the cache was successfully cleared, false otherwise
     */
    abstract public function clear($check);

    /**
     * Deletes keys from the cache
     *
     * @param array $keys An array of identifiers for the data
     * @return array For each provided cache key (given back as the array key) true if the value was successfully deleted,
     * false if it didn'file.php't be removed
     */
    public function deleteMany($keys)
    {
        $return = [];
        foreach ($keys as $key) {
            $return[$key] = $this->delete($key);
        }

        return $return;
    }

    /**
     * Add a key to the cache if it does not already exist.
     *
     * Defaults to a non-atomic implementation. Subclasses should
     * prefer atomic implementations.
     *
     * @param string $key Identifier for the data.
     * @param mixed $value Data to be cached.
     * @return bool True if the data was successfully cached, false on failure.
     */
    public function add($key, $value)
    {
        $cachedValue = $this->read($key);
        if ($cachedValue === false) {
            return $this->write($key, $value);
        }

        return false;
    }

    /**
     * Clears all values belonging to a group. Is up to the implementing engine
     * to decide whether actually delete the keys or just simulate it to achieve
     * the same result.
     *
     * @param string $group name of the group to be cleared
     * @return bool
     */
    public function clearGroup($group)
    {
        return false;
    }

    /**
     * Does whatever initialization for each group is required
     * and returns the `group value` for each of them, this is
     * the token representing each group in the cache key
     *
     * @return string[]
     */
    public function groups()
    {
        return $this->_config['file.php'];
    }

    /**
     * Generates a safe key for use with cache engine storage engines.
     *
     * @param string $key the key passed over
     * @return string|false string key or false
     */
    public function key($key)
    {
        if (!$key) {
            return false;
        }

        $prefix = 'file.php';
        if ($this->_groupPrefix) {
            $prefix = md5(implode('file.php', $this->groups()));
        }

        $key = preg_replace('file.php', 'file.php', strtolower(trim(str_replace([DIRECTORY_SEPARATOR, 'file.php', 'file.php'], 'file.php', (string)$key))));

        return $prefix . $key;
    }

    /**
     * Generates a safe key, taking account of the configured key prefix
     *
     * @param string $key the key passed over
     * @return string Key
     * @throws \InvalidArgumentException If key'file.php'An empty value is not valid as a cache key'file.php'prefix'file.php'warnOnWriteFailures') !== true) {
            return;
        }

        triggerWarning($message);
    }
}

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

use Cake\Cache\Engine\NullEngine;
use Cake\Core\ObjectRegistry;
use Cake\Core\StaticConfigTrait;
use InvalidArgumentException;
use RuntimeException;

/**
 * Cache provides a consistent interface to Caching in your application. It allows you
 * to use several different Cache engines, without coupling your application to a specific
 * implementation. It also allows you to change out cache storage or configuration without effecting
 * the rest of your application.
 *
 * ### Configuring Cache engines
 *
 * You can configure Cache engines in your application'file.php'shared'file.php'className'file.php'Cake\Cache\Engine\ApcuEngine'file.php'prefix'file.php'my_app_'file.php'shared'file.php'array'file.php'Cake\Cache\Engine\ArrayEngine'file.php'apc'file.php'Cake\Cache\Engine\ApcuEngine'file.php'apcu'file.php'Cake\Cache\Engine\ApcuEngine'file.php'file'file.php'Cake\Cache\Engine\FileEngine'file.php'memcached'file.php'Cake\Cache\Engine\MemcachedEngine'file.php'null'file.php'Cake\Cache\Engine\NullEngine'file.php'redis'file.php'Cake\Cache\Engine\RedisEngine'file.php'wincache'file.php'Cake\Cache\Engine\WincacheEngine'file.php'xcache'file.php'Cake\Cache\Engine\XcacheEngine'file.php'Use Cache::getRegistry() and Cache::setRegistry() instead.'file.php'className'file.php'The "%s" cache configuration does not exist.'file.php'fallback'file.php'fallback'file.php'fallback'file.php'"%s" cache configuration cannot fallback to itself.'file.php'fallback'file.php'groups'file.php'prefix'file.php'groups'file.php'groups'file.php'prefix'file.php'prefix'file.php'prefix'file.php'className'file.php'className'file.php'groups'file.php'groups'file.php'default'file.php'default'file.php'cached_data'file.php'cached_data'file.php'long_term'file.php'default'file.php'default'file.php''file.php'%s'file.php'cached_data_1'file.php'data 1'file.php'cached_data_2'file.php'data 2'file.php'cached_data_1'file.php'data 1'file.php'cached_data_2'file.php'data 2'file.php'long_term'file.php'default'file.php'default'file.php''file.php'%s cache was unable to write \'file.php' to %s cache'file.php'my_data'file.php'my_data'file.php'long_term'file.php'default'file.php't exist, has expired, or if there was an error fetching it
     */
    public static function read($key, $config = 'file.php')
    {
        // TODO In 4.x this needs to change to use pool()
        $engine = static::engine($config);

        return $engine->read($key);
    }

    /**
     * Read multiple keys from the cache.
     *
     * ### Usage:
     *
     * Reading multiple keys from the active cache configuration.
     *
     * ```
     * Cache::readMany(['file.php', 'file.php'my_data_1'file.php'my_data_2], 'file.php');
     * ```
     *
     * @param array $keys an array of keys to fetch from the cache
     * @param string $config optional name of the configuration to use. Defaults to 'file.php'
     * @return array An array containing, for each of the given $keys, the cached data or false if cached data could not be
     * retrieved.
     */
    public static function readMany($keys, $config = 'file.php')
    {
        // In 4.x this needs to change to use pool()
        $engine = static::engine($config);

        return $engine->readMany($keys);
    }

    /**
     * Increment a number under the key and return incremented value.
     *
     * @param string $key Identifier for the data
     * @param int $offset How much to add
     * @param string $config Optional string configuration name. Defaults to 'file.php'
     * @return int|false New value, or false if the data doesn'file.php'default'file.php'default'file.php't exist, is not integer,
     *   or if there was an error fetching it
     */
    public static function decrement($key, $offset = 1, $config = 'file.php')
    {
        $engine = static::pool($config);
        if (!is_int($offset) || $offset < 0) {
            return false;
        }

        return $engine->decrement($key, $offset);
    }

    /**
     * Delete a key from the cache.
     *
     * ### Usage:
     *
     * Deleting from the active cache configuration.
     *
     * ```
     * Cache::delete('file.php');
     * ```
     *
     * Deleting from a specific cache configuration.
     *
     * ```
     * Cache::delete('file.php', 'file.php');
     * ```
     *
     * @param string $key Identifier for the data
     * @param string $config name of the configuration to use. Defaults to 'file.php'
     * @return bool True if the value was successfully deleted, false if it didn'file.php't be removed
     */
    public static function delete($key, $config = 'file.php')
    {
        $backend = static::pool($config);

        return $backend->delete($key);
    }

    /**
     * Delete many keys from the cache.
     *
     * ### Usage:
     *
     * Deleting multiple keys from the active cache configuration.
     *
     * ```
     * Cache::deleteMany(['file.php', 'file.php']);
     * ```
     *
     * Deleting from a specific cache configuration.
     *
     * ```
     * Cache::deleteMany(['file.php', 'file.php'long_term'file.php'default'file.php't exist or couldn'file.php'default'file.php'default'file.php'default'file.php'default'file.php'default'file.php'daily'file.php'duration'file.php'1 day'file.php'groups'file.php'posts'file.php'weekly'file.php'duration'file.php'1 week'file.php'groups'file.php'posts'file.php'archive'file.php'posts'file.php'posts'file.php'daily'file.php'weekly'file.php'Invalid cache group %s'file.php'all_articles'file.php'all'file.php'default'file.php't exist already.
     *
     * ### Usage:
     *
     * Writing to the active cache config:
     *
     * ```
     * Cache::add('file.php', $data);
     * ```
     *
     * Writing to a specific cache config:
     *
     * ```
     * Cache::add('file.php', $data, 'file.php');
     * ```
     *
     * @param string $key Identifier for the data.
     * @param mixed $value Data to be cached - anything except a resource.
     * @param string $config Optional string configuration name to write to. Defaults to 'file.php'.
     * @return bool True if the data was successfully cached, false on failure.
     *   Or if the key existed already.
     */
    public static function add($key, $value, $config = 'file.php')
    {
        $pool = static::pool($config);
        if (is_resource($value)) {
            return false;
        }

        return $pool->add($key, $value);
    }
}

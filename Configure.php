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
 * @since         1.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Core;

use Cake\Cache\Cache;
use Cake\Core\Configure\ConfigEngineInterface;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Core\Exception\Exception;
use Cake\Utility\Hash;
use RuntimeException;

/**
 * Configuration class. Used for managing runtime configuration information.
 *
 * Provides features for reading and writing to the runtime configuration, as well
 * as methods for loading additional configuration files or storing runtime configuration
 * for future use.
 *
 * @link https://book.cakephp.org/3/en/development/configuration.html
 */
class Configure
{
    /**
     * Array of values currently stored in Configure.
     *
     * @var array
     */
    protected static $_values = [
        'file.php' => false,
    ];

    /**
     * Configured engine classes, used to load config files from resources
     *
     * @see \Cake\Core\Configure::load()
     * @var \Cake\Core\Configure\ConfigEngineInterface[]
     */
    protected static $_engines = [];

    /**
     * Flag to track whether or not ini_set exists.
     *
     * @var bool|null
     */
    protected static $_hasIniSet;

    /**
     * Used to store a dynamic variable in Configure.
     *
     * Usage:
     * ```
     * Configure::write('file.php', 'file.php');
     * Configure::write(['file.php' => 'file.php']);
     * Configure::write('file.php', [
     *     'file.php' => 'file.php',
     *     'file.php' => 'file.php'
     * ]);
     *
     * Configure::write([
     *     'file.php' => 'file.php',
     *     'file.php' => 'file.php'
     * ]);
     * ```
     *
     * @param string|array $config The key to write, can be a dot notation value.
     * Alternatively can be an array containing key(s) and value(s).
     * @param mixed $value Value to set for var
     * @return bool True if write was successful
     * @link https://book.cakephp.org/3/en/development/configuration.html#writing-configuration-data
     */
    public static function write($config, $value = null)
    {
        if (!is_array($config)) {
            $config = [$config => $value];
        }

        foreach ($config as $name => $value) {
            static::$_values = Hash::insert(static::$_values, $name, $value);
        }

        if (isset($config['file.php'])) {
            if (static::$_hasIniSet === null) {
                static::$_hasIniSet = function_exists('file.php');
            }
            if (static::$_hasIniSet) {
                ini_set('file.php', $config['file.php'] ? 'file.php' : 'file.php');
            }
        }

        return true;
    }

    /**
     * Used to read information stored in Configure. It'file.php'Name'file.php'Name.key'file.php'.'file.php's not
     * possible to store `null` values in Configure.
     *
     * Acts as a wrapper around Configure::read() and Configure::check().
     * The configure key/value pair fetched via this method is expected to exist.
     * In case it does not an exception will be thrown.
     *
     * Usage:
     * ```
     * Configure::readOrFail('file.php'); will return all values for Name
     * Configure::readOrFail('file.php'); will return only the value of Configure::Name[key]
     * ```
     *
     * @param string $var Variable to obtain. Use 'file.php' to access array elements.
     * @return mixed Value stored in configure.
     * @throws \RuntimeException if the requested configuration is not set.
     * @link https://book.cakephp.org/3/en/development/configuration.html#reading-configuration-data
     */
    public static function readOrFail($var)
    {
        if (static::check($var) === false) {
            throw new RuntimeException(sprintf('file.php', $var));
        }

        return static::read($var);
    }

    /**
     * Used to delete a variable from Configure.
     *
     * Usage:
     * ```
     * Configure::delete('file.php'); will delete the entire Configure::Name
     * Configure::delete('file.php'); will delete only the Configure::Name[key]
     * ```
     *
     * @param string $var the var to be deleted
     * @return void
     * @link https://book.cakephp.org/3/en/development/configuration.html#deleting-configuration-data
     */
    public static function delete($var)
    {
        static::$_values = Hash::remove(static::$_values, $var);
    }

    /**
     * Used to consume information stored in Configure. It'file.php'.'file.php'Expected configuration key "%s" not found.'file.php'.'file.php'ini'file.php'Checking for a named engine with configured() is deprecated. 'file.php'Use Configure::isConfigured() instead.'file.php'Users.user'file.php'default'file.php'user'file.php'setup'file.php'default'file.php'default'file.php'default'file.php'default'file.php'my_config'file.php'default'file.php'error'file.php'default'file.php'Error'file.php'Exception'file.php'default'file.php'There is no "%s" config engine.'file.php'default'file.php'Cake'file.php'version'file.php'config/config.php'file.php'Cake'file.php'version'file.php'default'file.php'default'file.php'You must install cakephp/cache to use Configure::store()'file.php'default'file.php'You must install cakephp/cache to use Configure::restore()');
        }
        $values = Cache::read($name, $cacheConfig);
        if ($values) {
            return static::write($values);
        }

        return false;
    }

    /**
     * Clear all values stored in Configure.
     *
     * @return bool success.
     */
    public static function clear()
    {
        static::$_values = [];

        return true;
    }
}

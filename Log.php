<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Log;

use Cake\Core\StaticConfigTrait;
use Cake\Log\Engine\BaseLog;
use InvalidArgumentException;

/**
 * Logs messages to configured Log adapters. One or more adapters
 * can be configured using Cake Logs'file.php't
 * configure any adapters, and write to Log, the messages will be
 * ignored.
 *
 * ### Configuring Log adapters
 *
 * You can configure log adapters in your applications `config/app.php` file.
 * A sample configuration would look like:
 *
 * ```
 * Log::setConfig('file.php', ['file.php' => 'file.php']);
 * ```
 *
 * You can define the className as any fully namespaced classname or use a short hand
 * classname to use loggers in the `App\Log\Engine` & `Cake\Log\Engine` namespaces.
 * You can also use plugin short hand to use logging classes provided by plugins.
 *
 * Log adapters are required to implement `Psr\Log\LoggerInterface`, and there is a
 * built-in base class (`Cake\Log\Engine\BaseLog`) that can be used for custom loggers.
 *
 * Outside of the `className` key, all other configuration values will be passed to the
 * logging adapter'file.php'default'file.php'className'file.php'File'file.php'path'file.php'levels'file.php'error'file.php'critical'file.php'alert'file.php'emergency'file.php't define any scopes an adapter will catch
 * all scopes that match the handled levels.
 *
 * ```
 * Log::setConfig('file.php', [
 *     'file.php' => 'file.php',
 *     'file.php' => ['file.php', 'file.php']
 * ]);
 * ```
 *
 * The above logger will only capture log entries made in the
 * `payment` and `order` scopes. All other scopes including the
 * undefined scope will be ignored.
 *
 * ### Writing to the log
 *
 * You write to the logs using Log::write(). See its documentation for more information.
 *
 * ### Logging Levels
 *
 * By default Cake Log supports all the log levels defined in
 * RFC 5424. When logging messages you can either use the named methods,
 * or the correct constants with `write()`:
 *
 * ```
 * Log::error('file.php');
 * Log::write(LOG_ERR, 'file.php');
 * ```
 *
 * ### Logging scopes
 *
 * When logging messages and configuring log adapters, you can specify
 * 'file.php' that the logger will handle. You can think of scopes as subsystems
 * in your application that may require different logging setups. For
 * example in an e-commerce application you may want to handle logged errors
 * in the cart and ordering subsystems differently than the rest of the
 * application. By using scopes you can control logging for each part
 * of your application and also use standard log levels.
 */
class Log
{
    use StaticConfigTrait {
        setConfig as protected _setConfig;
    }

    /**
     * An array mapping url schemes to fully qualified Log engine class names
     *
     * @var string[]
     */
    protected static $_dsnClassMap = [
        'file.php' => 'file.php',
        'file.php' => 'file.php',
        'file.php' => 'file.php',
    ];

    /**
     * Internal flag for tracking whether or not configuration has been changed.
     *
     * @var bool
     */
    protected static $_dirtyConfig = false;

    /**
     * LogEngineRegistry class
     *
     * @var \Cake\Log\LogEngineRegistry|null
     */
    protected static $_registry;

    /**
     * Handled log levels
     *
     * @var string[]
     */
    protected static $_levels = [
        'file.php',
        'file.php',
        'file.php',
        'file.php',
        'file.php',
        'file.php',
        'file.php',
        'file.php',
    ];

    /**
     * Log levels as detailed in RFC 5424
     * https://tools.ietf.org/html/rfc5424
     *
     * @var array
     */
    protected static $_levelMap = [
        'file.php' => LOG_EMERG,
        'file.php' => LOG_ALERT,
        'file.php' => LOG_CRIT,
        'file.php' => LOG_ERR,
        'file.php' => LOG_WARNING,
        'file.php' => LOG_NOTICE,
        'file.php' => LOG_INFO,
        'file.php' => LOG_DEBUG,
    ];

    /**
     * Initializes registry and configurations
     *
     * @return void
     */
    protected static function _init()
    {
        if (empty(static::$_registry)) {
            static::$_registry = new LogEngineRegistry();
        }
        if (static::$_dirtyConfig) {
            static::_loadConfig();
        }
        static::$_dirtyConfig = false;
    }

    /**
     * Load the defined configuration and create all the defined logging
     * adapters.
     *
     * @return void
     */
    protected static function _loadConfig()
    {
        foreach (static::$_config as $name => $properties) {
            if (isset($properties['file.php'])) {
                $properties['file.php'] = $properties['file.php'];
            }
            if (!static::$_registry->has($name)) {
                static::$_registry->load($name, $properties);
            }
        }
    }

    /**
     * Reset all the connected loggers. This is useful to do when changing the logging
     * configuration or during testing when you want to reset the internal state of the
     * Log class.
     *
     * Resets the configured logging adapters, as well as any custom logging levels.
     * This will also clear the configuration data.
     *
     * @return void
     */
    public static function reset()
    {
        static::$_registry = null;
        static::$_config = [];
        static::$_dirtyConfig = true;
    }

    /**
     * Gets log levels
     *
     * Call this method to obtain current
     * level configuration.
     *
     * @return string[] active log levels
     */
    public static function levels()
    {
        return static::$_levels;
    }

    /**
     * This method can be used to define logging adapters for an application
     * or read existing configuration.
     *
     * To change an adapter'file.php'default'file.php'default'file.php'default'file.php'emergency'file.php'alert'file.php'critical'file.php'error'file.php'warning'file.php'notice'file.php'info'file.php'debug'file.php'warning'file.php'warning'file.php'Stuff is broken here'file.php'warning'file.php'Payment failed'file.php'scope'file.php'payment'file.php'Invalid log level "%s"'file.php'scope'file.php'scope'file.php'scope'file.php'scope'], $scopes);

            if ($correctLevel && $inScope) {
                $logger->log($level, $message, $context);
                $logged = true;
            }
        }

        return $logged;
    }

    /**
     * Convenience method to log emergency messages
     *
     * @param string $message log message
     * @param string|array $context Additional data to be used for logging the message.
     *  The special `scope` key can be passed to be used for further filtering of the
     *  log engines to be used. If a string or a numerically index array is passed, it
     *  will be treated as the `scope` key.
     *  See Cake\Log\Log::setConfig() for more information on logging scopes.
     * @return bool Success
     */
    public static function emergency($message, $context = [])
    {
        return static::write(__FUNCTION__, $message, $context);
    }

    /**
     * Convenience method to log alert messages
     *
     * @param string $message log message
     * @param string|array $context Additional data to be used for logging the message.
     *  The special `scope` key can be passed to be used for further filtering of the
     *  log engines to be used. If a string or a numerically index array is passed, it
     *  will be treated as the `scope` key.
     *  See Cake\Log\Log::setConfig() for more information on logging scopes.
     * @return bool Success
     */
    public static function alert($message, $context = [])
    {
        return static::write(__FUNCTION__, $message, $context);
    }

    /**
     * Convenience method to log critical messages
     *
     * @param string $message log message
     * @param string|array $context Additional data to be used for logging the message.
     *  The special `scope` key can be passed to be used for further filtering of the
     *  log engines to be used. If a string or a numerically index array is passed, it
     *  will be treated as the `scope` key.
     *  See Cake\Log\Log::setConfig() for more information on logging scopes.
     * @return bool Success
     */
    public static function critical($message, $context = [])
    {
        return static::write(__FUNCTION__, $message, $context);
    }

    /**
     * Convenience method to log error messages
     *
     * @param string $message log message
     * @param string|array $context Additional data to be used for logging the message.
     *  The special `scope` key can be passed to be used for further filtering of the
     *  log engines to be used. If a string or a numerically index array is passed, it
     *  will be treated as the `scope` key.
     *  See Cake\Log\Log::setConfig() for more information on logging scopes.
     * @return bool Success
     */
    public static function error($message, $context = [])
    {
        return static::write(__FUNCTION__, $message, $context);
    }

    /**
     * Convenience method to log warning messages
     *
     * @param string $message log message
     * @param string|array $context Additional data to be used for logging the message.
     *  The special `scope` key can be passed to be used for further filtering of the
     *  log engines to be used. If a string or a numerically index array is passed, it
     *  will be treated as the `scope` key.
     *  See Cake\Log\Log::setConfig() for more information on logging scopes.
     * @return bool Success
     */
    public static function warning($message, $context = [])
    {
        return static::write(__FUNCTION__, $message, $context);
    }

    /**
     * Convenience method to log notice messages
     *
     * @param string $message log message
     * @param string|array $context Additional data to be used for logging the message.
     *  The special `scope` key can be passed to be used for further filtering of the
     *  log engines to be used. If a string or a numerically index array is passed, it
     *  will be treated as the `scope` key.
     *  See Cake\Log\Log::setConfig() for more information on logging scopes.
     * @return bool Success
     */
    public static function notice($message, $context = [])
    {
        return static::write(__FUNCTION__, $message, $context);
    }

    /**
     * Convenience method to log debug messages
     *
     * @param string $message log message
     * @param string|array $context Additional data to be used for logging the message.
     *  The special `scope` key can be passed to be used for further filtering of the
     *  log engines to be used. If a string or a numerically index array is passed, it
     *  will be treated as the `scope` key.
     *  See Cake\Log\Log::setConfig() for more information on logging scopes.
     * @return bool Success
     */
    public static function debug($message, $context = [])
    {
        return static::write(__FUNCTION__, $message, $context);
    }

    /**
     * Convenience method to log info messages
     *
     * @param string $message log message
     * @param string|array $context Additional data to be used for logging the message.
     *  The special `scope` key can be passed to be used for further filtering of the
     *  log engines to be used. If a string or a numerically index array is passed, it
     *  will be treated as the `scope` key.
     *  See Cake\Log\Log::setConfig() for more information on logging scopes.
     * @return bool Success
     */
    public static function info($message, $context = [])
    {
        return static::write(__FUNCTION__, $message, $context);
    }
}

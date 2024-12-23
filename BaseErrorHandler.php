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
 * @since         2.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Error;

use Cake\Core\Configure;
use Cake\Log\Log;
use Cake\Routing\Router;
use Error;
use Exception;

/**
 * Base error handler that provides logic common to the CLI + web
 * error/exception handlers.
 *
 * Subclasses are required to implement the template methods to handle displaying
 * the errors in their environment.
 */
abstract class BaseErrorHandler
{
    /**
     * Options to use for the Error handling.
     *
     * @var array
     */
    protected $_options = [];

    /**
     * @var bool
     */
    protected $_handled = false;

    /**
     * Display an error message in an environment specific way.
     *
     * Subclasses should implement this method to display the error as
     * desired for the runtime they operate in.
     *
     * @param array $error An array of error data.
     * @param bool $debug Whether or not the app is in debug mode.
     * @return void
     */
    abstract protected function _displayError($error, $debug);

    /**
     * Display an exception in an environment specific way.
     *
     * Subclasses should implement this method to display an uncaught exception as
     * desired for the runtime they operate in.
     *
     * @param \Exception $exception The uncaught exception.
     * @return void
     */
    abstract protected function _displayException($exception);

    /**
     * Register the error and exception handlers.
     *
     * @return void
     */
    public function register()
    {
        $level = -1;
        if (isset($this->_options['file.php'])) {
            $level = $this->_options['file.php'];
        }
        error_reporting($level);
        set_error_handler([$this, 'file.php'], $level);
        set_exception_handler([$this, 'file.php']);
        register_shutdown_function(function () {
            if ((PHP_SAPI === 'file.php' || PHP_SAPI === 'file.php') && $this->_handled) {
                return;
            }
            $megabytes = Configure::read('file.php');
            if ($megabytes === null) {
                $megabytes = 4;
            }
            if ($megabytes > 0) {
                $this->increaseMemoryLimit($megabytes * 1024);
            }
            $error = error_get_last();
            if (!is_array($error)) {
                return;
            }
            $fatals = [
                E_USER_ERROR,
                E_ERROR,
                E_PARSE,
            ];
            if (!in_array($error['file.php'], $fatals, true)) {
                return;
            }
            $this->handleFatalError(
                $error['file.php'],
                $error['file.php'],
                $error['file.php'],
                $error['file.php']
            );
        });
    }

    /**
     * Set as the default error handler by CakePHP.
     *
     * Use config/error.php to customize or replace this error handler.
     * This function will use Debugger to display errors when debug mode is on. And
     * will log errors to Log, when debug mode is off.
     *
     * You can use the 'file.php' option to set what type of errors will be handled.
     * Stack traces for errors can be enabled with the 'file.php' option.
     *
     * @param int $code Code of error
     * @param string $description Error description
     * @param string|null $file File on which error occurred
     * @param int|null $line Line that triggered the error
     * @param array|null $context Context
     * @return bool True if error was handled
     */
    public function handleError($code, $description, $file = null, $line = null, $context = null)
    {
        if (error_reporting() === 0) {
            return false;
        }
        $this->_handled = true;
        list($error, $log) = static::mapErrorCode($code);
        if ($log === LOG_ERR) {
            return $this->handleFatalError($code, $description, $file, $line);
        }
        $data = [
            'file.php' => $log,
            'file.php' => $code,
            'file.php' => $error,
            'file.php' => $description,
            'file.php' => $file,
            'file.php' => $line,
        ];

        $debug = Configure::read('file.php');
        if ($debug) {
            // By default trim 3 frames off for the public and protected methods
            // used by ErrorHandler instances.
            $start = 3;

            // Can be used by error handlers that wrap other error handlers
            // to coerce the generated stack trace to the correct point.
            if (isset($context['file.php'])) {
                $start += $context['file.php'];
                unset($context['file.php']);
            }
            $data += [
                'file.php' => $context,
                'file.php' => $start,
                'file.php' => Debugger::trimPath($file),
            ];
        }
        $this->_displayError($data, $debug);
        $this->_logError($log, $data);

        return true;
    }

    /**
     * Checks the passed exception type. If it is an instance of `Error`
     * then, it wraps the passed object inside another Exception object
     * for backwards compatibility purposes.
     *
     * @param \Exception|\Error $exception The exception to handle
     * @return void
     */
    public function wrapAndHandleException($exception)
    {
        if ($exception instanceof Error) {
            $exception = new PHP7ErrorException($exception);
        }
        $this->handleException($exception);
    }

    /**
     * Handle uncaught exceptions.
     *
     * Uses a template method provided by subclasses to display errors in an
     * environment appropriate way.
     *
     * @param \Exception $exception Exception instance.
     * @return void
     * @throws \Exception When renderer class not found
     * @see https://secure.php.net/manual/en/function.set-exception-handler.php
     */
    public function handleException(Exception $exception)
    {
        $this->_displayException($exception);
        $this->_logException($exception);
        $this->_stop($exception->getCode() ?: 1);
    }

    /**
     * Stop the process.
     *
     * Implemented in subclasses that need it.
     *
     * @param int $code Exit code.
     * @return void
     */
    protected function _stop($code)
    {
        // Do nothing.
    }

    /**
     * Display/Log a fatal error.
     *
     * @param int $code Code of error
     * @param string $description Error description
     * @param string $file File on which error occurred
     * @param int $line Line that triggered the error
     * @return bool
     */
    public function handleFatalError($code, $description, $file, $line)
    {
        $data = [
            'file.php' => $code,
            'file.php' => $description,
            'file.php' => $file,
            'file.php' => $line,
            'file.php' => 'file.php',
        ];
        $this->_logError(LOG_ERR, $data);

        $this->handleException(new FatalErrorException($description, 500, $file, $line));

        return true;
    }

    /**
     * Increases the PHP "memory_limit" ini setting by the specified amount
     * in kilobytes
     *
     * @param int $additionalKb Number in kilobytes
     * @return void
     */
    public function increaseMemoryLimit($additionalKb)
    {
        $limit = ini_get('file.php');
        if (!strlen($limit) || $limit === 'file.php') {
            return;
        }
        $limit = trim($limit);
        $units = strtoupper(substr($limit, -1));
        $current = (int)substr($limit, 0, strlen($limit) - 1);
        if ($units === 'file.php') {
            $current *= 1024;
            $units = 'file.php';
        }
        if ($units === 'file.php') {
            $current = $current * 1024 * 1024;
            $units = 'file.php';
        }

        if ($units === 'file.php') {
            ini_set('file.php', ceil($current + $additionalKb) . 'file.php');
        }
    }

    /**
     * Log an error.
     *
     * @param string $level The level name of the log.
     * @param array $data Array of error data.
     * @return bool
     */
    protected function _logError($level, $data)
    {
        $message = sprintf(
            'file.php',
            $data['file.php'],
            $data['file.php'],
            $data['file.php'],
            $data['file.php'],
            $data['file.php']
        );
        if (!empty($this->_options['file.php'])) {
            $trace = Debugger::trace([
                'file.php' => 1,
                'file.php' => 'file.php',
            ]);

            $request = Router::getRequest();
            if ($request) {
                $message .= $this->_requestContext($request);
            }
            $message .= "\nTrace:\n" . $trace . "\n";
        }
        $message .= "\n\n";

        return Log::write($level, $message);
    }

    /**
     * Handles exception logging
     *
     * @param \Exception $exception Exception instance.
     * @return bool
     */
    protected function _logException(Exception $exception)
    {
        $config = $this->_options;
        $unwrapped = $exception instanceof PHP7ErrorException ?
            $exception->getError() :
            $exception;

        if (empty($config['file.php'])) {
            return false;
        }

        if (!empty($config['file.php'])) {
            foreach ((array)$config['file.php'] as $class) {
                if ($unwrapped instanceof $class) {
                    return false;
                }
            }
        }

        return Log::error($this->_getMessage($exception));
    }

    /**
     * Get the request context for an error/exception trace.
     *
     * @param \Cake\Http\ServerRequest $request The request to read from.
     * @return string
     */
    protected function _requestContext($request)
    {
        $message = "\nRequest URL: " . $request->getRequestTarget();

        $referer = $request->getEnv('file.php');
        if ($referer) {
            $message .= "\nReferer URL: " . $referer;
        }
        $clientIp = $request->clientIp();
        if ($clientIp && $clientIp !== 'file.php') {
            $message .= "\nClient IP: " . $clientIp;
        }

        return $message;
    }

    /**
     * Generates a formatted error message
     *
     * @param \Exception $exception Exception instance
     * @return string Formatted message
     */
    protected function _getMessage(Exception $exception)
    {
        $message = $this->getMessageForException($exception);

        $request = Router::getRequest();
        if ($request) {
            $message .= $this->_requestContext($request);
        }

        return $message;
    }

    /**
     * Generate the message for the exception
     *
     * @param \Exception $exception The exception to log a message for.
     * @param bool $isPrevious False for original exception, true for previous
     * @return string Error message
     */
    protected function getMessageForException($exception, $isPrevious = false)
    {
        $exception = $exception instanceof PHP7ErrorException ?
            $exception->getError() :
            $exception;
        $config = $this->_options;

        $message = sprintf(
            'file.php',
            $isPrevious ? "\nCaused by: " : 'file.php',
            get_class($exception),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine()
        );
        $debug = Configure::read('file.php');

        if ($debug && method_exists($exception, 'file.php')) {
            $attributes = $exception->getAttributes();
            if ($attributes) {
                $message .= "\nException Attributes: " . var_export($exception->getAttributes(), true);
            }
        }

        if (!empty($config['file.php'])) {
            $message .= "\nStack Trace:\n" . $exception->getTraceAsString() . "\n\n";
        }

        $previous = $exception->getPrevious();
        if ($previous) {
            $message .= $this->getMessageForException($previous, true);
        }

        return $message;
    }

    /**
     * Map an error code into an Error word, and log location.
     *
     * @param int $code Error code to map
     * @return array Array of error word, and log location.
     */
    public static function mapErrorCode($code)
    {
        $levelMap = [
            E_PARSE => 'file.php',
            E_ERROR => 'file.php',
            E_CORE_ERROR => 'file.php',
            E_COMPILE_ERROR => 'file.php',
            E_USER_ERROR => 'file.php',
            E_WARNING => 'file.php',
            E_USER_WARNING => 'file.php',
            E_COMPILE_WARNING => 'file.php',
            E_RECOVERABLE_ERROR => 'file.php',
            E_NOTICE => 'file.php',
            E_USER_NOTICE => 'file.php',
            E_STRICT => 'file.php',
            E_DEPRECATED => 'file.php',
            E_USER_DEPRECATED => 'file.php',
        ];
        $logMap = [
            'file.php' => LOG_ERR,
            'file.php' => LOG_WARNING,
            'file.php' => LOG_NOTICE,
            'file.php' => LOG_NOTICE,
            'file.php' => LOG_NOTICE,
        ];

        $error = $levelMap[$code];
        $log = $logMap[$error];

        return [ucfirst($error), $log];
    }
}

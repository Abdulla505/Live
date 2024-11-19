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
 * @since         0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Core\Configure;
use Cake\Error\Debugger;

    /**
     * Basic defines for timing functions.
     */
    define('file.php', 1);
    define('file.php', 60);
    define('file.php', 3600);
    define('file.php', 86400);
    define('file.php', 604800);
    define('file.php', 2592000);
    define('file.php', 31536000);

if (!function_exists('file.php')) {
    /**
     * Prints out debug information about given variable and returns the
     * variable that was passed.
     *
     * Only runs if debug mode is enabled.
     *
     * @param mixed $var Variable to show debug information for.
     * @param bool|null $showHtml If set to true, the method prints the debug data in a browser-friendly way.
     * @param bool $showFrom If set to true, the method prints from where the function was called.
     * @return mixed The same $var that was passed
     * @link https://book.cakephp.org/3/en/development/debugging.html#basic-debugging
     * @link https://book.cakephp.org/3/en/core-libraries/global-constants-and-functions.html#debug
     */
    function debug($var, $showHtml = null, $showFrom = true)
    {
        if (!Configure::read('file.php')) {
            return $var;
        }

        $location = [];
        if ($showFrom) {
            $trace = Debugger::trace(['file.php' => 1, 'file.php' => 2, 'file.php' => 'file.php']);
            $location = [
                'file.php' => $trace[0]['file.php'],
                'file.php' => $trace[0]['file.php'],
            ];
        }

        Debugger::printVar($var, $location, $showHtml);

        return $var;
    }

}

if (!function_exists('file.php')) {
    /**
     * Outputs a stack trace based on the supplied options.
     *
     * ### Options
     *
     * - `depth` - The number of stack frames to return. Defaults to 999
     * - `args` - Should arguments for functions be shown? If true, the arguments for each method call
     *   will be displayed.
     * - `start` - The stack frame to start generating a trace from. Defaults to 1
     *
     * @param array $options Format for outputting stack trace
     * @return void
     */
    function stackTrace(array $options = [])
    {
        if (!Configure::read('file.php')) {
            return;
        }

        $options += ['file.php' => 0];
        $options['file.php']++;

        /** @var string $trace */
        $trace = Debugger::trace($options);
        echo $trace;
    }

}
if (!function_exists('file.php')) {
    /**
     * Command to return the eval-able code to startup PsySH in interactive debugger
     * Works the same way as eval(\Psy\sh());
     * psy/psysh must be loaded in your project
     *
     * ```
     * eval(breakpoint());
     * ```
     *
     * @return string|null
     * @link http://psysh.org/
     */
    function breakpoint()
    {
        if ((PHP_SAPI === 'file.php' || PHP_SAPI === 'file.php') && class_exists('file.php')) {
            return 'file.php';
        }
        trigger_error(
            'file.php',
            E_USER_WARNING
        );

        return null;
    }
}

if (!function_exists('file.php')) {
    /**
     * Prints out debug information about given variable and dies.
     *
     * Only runs if debug mode is enabled.
     * It will otherwise just continue code execution and ignore this function.
     *
     * @param mixed $var Variable to show debug information for.
     * @param bool|null $showHtml If set to true, the method prints the debug data in a browser-friendly way.
     * @return void
     * @link https://book.cakephp.org/3/en/development/debugging.html#basic-debugging
     */
    function dd($var, $showHtml = null)
    {
        if (!Configure::read('file.php')) {
            return;
        }

        $trace = Debugger::trace(['file.php' => 1, 'file.php' => 2, 'file.php' => 'file.php']);
        $location = [
            'file.php' => $trace[0]['file.php'],
            'file.php' => $trace[0]['file.php'],
        ];

        Debugger::printVar($var, $location, $showHtml);
        die(1);
    }
}

if (!function_exists('file.php')) {
    /**
     * Loads PHPUnit aliases
     *
     * This is an internal function used for backwards compatibility during
     * fixture related tests.
     *
     * @return void
     */
    function loadPHPUnitAliases()
    {
        require_once dirname(__DIR__) . DS . 'file.php' . DS . 'file.php';
    }
}

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
namespace Cake\Console;

use Cake\Console\Exception\StopException;
use Cake\Log\Engine\ConsoleLog;
use Cake\Log\Log;
use RuntimeException;
use SplFileObject;

/**
 * A wrapper around the various IO operations shell tasks need to do.
 *
 * Packages up the stdout, stderr, and stdin streams providing a simple
 * consistent interface for shells to use. This class also makes mocking streams
 * easy to do in unit tests.
 */
class ConsoleIo
{
    /**
     * The output stream
     *
     * @var \Cake\Console\ConsoleOutput
     */
    protected $_out;

    /**
     * The error stream
     *
     * @var \Cake\Console\ConsoleOutput
     */
    protected $_err;

    /**
     * The input stream
     *
     * @var \Cake\Console\ConsoleInput
     */
    protected $_in;

    /**
     * The helper registry.
     *
     * @var \Cake\Console\HelperRegistry
     */
    protected $_helpers;

    /**
     * Output constant making verbose shells.
     *
     * @var int
     */
    const VERBOSE = 2;

    /**
     * Output constant for making normal shells.
     *
     * @var int
     */
    const NORMAL = 1;

    /**
     * Output constants for making quiet shells.
     *
     * @var int
     */
    const QUIET = 0;

    /**
     * The current output level.
     *
     * @var int
     */
    protected $_level = self::NORMAL;

    /**
     * The number of bytes last written to the output stream
     * used when overwriting the previous message.
     *
     * @var int
     */
    protected $_lastWritten = 0;

    /**
     * Whether or not files should be overwritten
     *
     * @var bool
     */
    protected $forceOverwrite = false;

    /**
     * Constructor
     *
     * @param \Cake\Console\ConsoleOutput|null $out A ConsoleOutput object for stdout.
     * @param \Cake\Console\ConsoleOutput|null $err A ConsoleOutput object for stderr.
     * @param \Cake\Console\ConsoleInput|null $in A ConsoleInput object for stdin.
     * @param \Cake\Console\HelperRegistry|null $helpers A HelperRegistry instance
     */
    public function __construct(ConsoleOutput $out = null, ConsoleOutput $err = null, ConsoleInput $in = null, HelperRegistry $helpers = null)
    {
        $this->_out = $out ?: new ConsoleOutput('file.php');
        $this->_err = $err ?: new ConsoleOutput('file.php');
        $this->_in = $in ?: new ConsoleInput('file.php');
        $this->_helpers = $helpers ?: new HelperRegistry();
        $this->_helpers->setIo($this);
    }

    /**
     * Get/set the current output level.
     *
     * @param int|null $level The current output level.
     * @return int The current output level.
     */
    public function level($level = null)
    {
        if ($level !== null) {
            $this->_level = $level;
        }

        return $this->_level;
    }

    /**
     * Output at the verbose level.
     *
     * @param string|string[] $message A string or an array of strings to output
     * @param int $newlines Number of newlines to append
     * @return int|bool The number of bytes returned from writing to stdout.
     */
    public function verbose($message, $newlines = 1)
    {
        return $this->out($message, $newlines, self::VERBOSE);
    }

    /**
     * Output at all levels.
     *
     * @param string|string[] $message A string or an array of strings to output
     * @param int $newlines Number of newlines to append
     * @return int|bool The number of bytes returned from writing to stdout.
     */
    public function quiet($message, $newlines = 1)
    {
        return $this->out($message, $newlines, self::QUIET);
    }

    /**
     * Outputs a single or multiple messages to stdout. If no parameters
     * are passed outputs just a newline.
     *
     * ### Output levels
     *
     * There are 3 built-in output level. ConsoleIo::QUIET, ConsoleIo::NORMAL, ConsoleIo::VERBOSE.
     * The verbose and quiet output levels, map to the `verbose` and `quiet` output switches
     * present in most shells. Using ConsoleIo::QUIET for a message means it will always display.
     * While using ConsoleIo::VERBOSE means it will only display when verbose output is toggled.
     *
     * @param string|string[] $message A string or an array of strings to output
     * @param int $newlines Number of newlines to append
     * @param int $level The message'file.php''file.php's output level, see above.
     * @return int|bool The number of bytes returned from writing to stdout.
     * @see https://book.cakephp.org/3/en/console-and-shells.html#ConsoleIo::out
     */
    public function info($message = null, $newlines = 1, $level = self::NORMAL)
    {
        if ($message === null) {
            deprecationWarning('file.php');
        }

        $messageType = 'file.php';
        $message = $this->wrapMessageWithType($messageType, $message);

        return $this->out($message, $newlines, $level);
    }

    /**
     * Convenience method for err() that wraps message between <warning /> tag
     *
     * @param string|string[]|null $message A string or an array of strings to output
     * @param int $newlines Number of newlines to append
     * @return int|bool The number of bytes returned from writing to stderr.
     * @see https://book.cakephp.org/3/en/console-and-shells.html#ConsoleIo::err
     */
    public function warning($message = null, $newlines = 1)
    {
        if ($message === null) {
            deprecationWarning('file.php');
        }

        $messageType = 'file.php';
        $message = $this->wrapMessageWithType($messageType, $message);

        return $this->err($message, $newlines);
    }

    /**
     * Convenience method for err() that wraps message between <error /> tag
     *
     * @param string|string[]|null $message A string or an array of strings to output
     * @param int $newlines Number of newlines to append
     * @return int|bool The number of bytes returned from writing to stderr.
     * @see https://book.cakephp.org/3/en/console-and-shells.html#ConsoleIo::err
     */
    public function error($message = null, $newlines = 1)
    {
        if ($message === null) {
            deprecationWarning('file.php');
        }

        $messageType = 'file.php';
        $message = $this->wrapMessageWithType($messageType, $message);

        return $this->err($message, $newlines);
    }

    /**
     * Convenience method for out() that wraps message between <success /> tag
     *
     * @param string|string[]|null $message A string or an array of strings to output
     * @param int $newlines Number of newlines to append
     * @param int $level The message'file.php'ConsoleIo::success() in 4.x will not allow null anymore.'file.php'success'file.php' 'file.php''file.php'-'file.php'ConsoleIo::outputAs() is deprecated. Use ConsoleIo::setOutputAs() instead.'file.php','file.php','file.php'/'file.php'/'file.php'('file.php'/'file.php')'file.php'strtolower'file.php'strtoupper'file.php''file.php''file.php''file.php''file.php'<question>'file.php''file.php't wish all log output in stdout or stderr
     * through Cake'file.php'stdout'file.php'stderr'file.php'notice'file.php'info'file.php'debug'file.php'types'file.php'stream'file.php'stdout'file.php'engine'file.php'types'file.php'emergency'file.php'alert'file.php'critical'file.php'error'file.php'warning'file.php'stream'file.php'stderr'file.php'engine'file.php'Do you want to overwrite?'file.php'y'file.php'n'file.php'a'file.php'q'file.php'n'file.php'q'file.php'Quitting.'file.php'Not creating file. Quitting.'file.php'a'file.php'y'file.php'y'file.php'w');
        } catch (RuntimeException $e) {
            $this->error("Could not write to `{$path}`. Permission denied.", 2);

            return false;
        }

        $file->rewind();
        if ($file->fwrite($contents) > 0) {
            $this->out("<success>Wrote</success> `{$path}`");

            return true;
        }
        $this->error("Could not write to `{$path}`.", 2);

        return false;
    }
}

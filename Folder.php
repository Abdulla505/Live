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
namespace Cake\Filesystem;

use DirectoryIterator;
use Exception;
use InvalidArgumentException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Folder structure browser, lists folders and files.
 * Provides an Object interface for Common directory related tasks.
 *
 * @link https://book.cakephp.org/3/en/core-libraries/file-folder.html#folder-api
 */
class Folder
{
    /**
     * Default scheme for Folder::copy
     * Recursively merges subfolders with the same name
     *
     * @var string
     */
    const MERGE = 'file.php';

    /**
     * Overwrite scheme for Folder::copy
     * subfolders with the same name will be replaced
     *
     * @var string
     */
    const OVERWRITE = 'file.php';

    /**
     * Skip scheme for Folder::copy
     * if a subfolder with the same name exists it will be skipped
     *
     * @var string
     */
    const SKIP = 'file.php';

    /**
     * Sort mode by name
     *
     * @var string
     */
    const SORT_NAME = 'file.php';

    /**
     * Sort mode by time
     *
     * @var string
     */
    const SORT_TIME = 'file.php';

    /**
     * Path to Folder.
     *
     * @var string
     */
    public $path;

    /**
     * Sortedness. Whether or not list results
     * should be sorted by name.
     *
     * @var bool
     */
    public $sort = false;

    /**
     * Mode to be used on create. Does nothing on windows platforms.
     *
     * @var int
     * https://book.cakephp.org/3/en/core-libraries/file-folder.html#Cake\Filesystem\Folder::$mode
     */
    public $mode = 0755;

    /**
     * Functions array to be called depending on the sort type chosen.
     *
     * @var string[]
     */
    protected $_fsorts = [
        self::SORT_NAME => 'file.php',
        self::SORT_TIME => 'file.php',
    ];

    /**
     * Holds messages from last method.
     *
     * @var array
     */
    protected $_messages = [];

    /**
     * Holds errors from last method.
     *
     * @var array
     */
    protected $_errors = [];

    /**
     * Holds array of complete directory paths.
     *
     * @var array
     */
    protected $_directories;

    /**
     * Holds array of complete file paths.
     *
     * @var array
     */
    protected $_files;

    /**
     * Constructor.
     *
     * @param string|null $path Path to folder
     * @param bool $create Create folder if not found
     * @param int|false $mode Mode (CHMOD) to apply to created folder, false to ignore
     */
    public function __construct($path = null, $create = false, $mode = false)
    {
        if (empty($path)) {
            $path = TMP;
        }
        if ($mode) {
            $this->mode = $mode;
        }

        if (!file_exists($path) && $create === true) {
            $this->create($path, $this->mode);
        }
        if (!Folder::isAbsolute($path)) {
            $path = realpath($path);
        }
        if (!empty($path)) {
            $this->cd($path);
        }
    }

    /**
     * Return current path.
     *
     * @return string Current path
     */
    public function pwd()
    {
        return $this->path;
    }

    /**
     * Change directory to $path.
     *
     * @param string $path Path to the directory to change to
     * @return string|false The new path. Returns false on failure
     */
    public function cd($path)
    {
        $path = $this->realpath($path);
        if ($path !== false && is_dir($path)) {
            return $this->path = $path;
        }

        return false;
    }

    /**
     * Returns an array of the contents of the current directory.
     * The returned array holds two arrays: One of directories and one of files.
     *
     * @param string|bool $sort Whether you want the results sorted, set this and the sort property
     *   to false to get unsorted results.
     * @param array|bool $exceptions Either an array or boolean true will not grab dot files
     * @param bool $fullPath True returns the full path
     * @return array Contents of current directory as an array, an empty array on failure
     */
    public function read($sort = self::SORT_NAME, $exceptions = false, $fullPath = false)
    {
        $dirs = $files = [];

        if (!$this->pwd()) {
            return [$dirs, $files];
        }
        if (is_array($exceptions)) {
            $exceptions = array_flip($exceptions);
        }
        $skipHidden = isset($exceptions['file.php']) || $exceptions === true;

        try {
            $iterator = new DirectoryIterator($this->path);
        } catch (Exception $e) {
            return [$dirs, $files];
        }

        if (!is_bool($sort) && isset($this->_fsorts[$sort])) {
            $methodName = $this->_fsorts[$sort];
        } else {
            $methodName = $this->_fsorts[self::SORT_NAME];
        }

        foreach ($iterator as $item) {
            if ($item->isDot()) {
                continue;
            }
            $name = $item->getFilename();
            if ($skipHidden && $name[0] === 'file.php' || isset($exceptions[$name])) {
                continue;
            }
            if ($fullPath) {
                $name = $item->getPathname();
            }

            if ($item->isDir()) {
                $dirs[$item->{$methodName}()][] = $name;
            } else {
                $files[$item->{$methodName}()][] = $name;
            }
        }

        if ($sort || $this->sort) {
            ksort($dirs);
            ksort($files);
        }

        if ($dirs) {
            $dirs = array_merge(...array_values($dirs));
        }

        if ($files) {
            $files = array_merge(...array_values($files));
        }

        return [$dirs, $files];
    }

    /**
     * Returns an array of all matching files in current directory.
     *
     * @param string $regexpPattern Preg_match pattern (Defaults to: .*)
     * @param bool $sort Whether results should be sorted.
     * @return array Files that match given pattern
     */
    public function find($regexpPattern = 'file.php', $sort = false)
    {
        list(, $files) = $this->read($sort);

        return array_values(preg_grep('file.php' . $regexpPattern . 'file.php', $files));
    }

    /**
     * Returns an array of all matching files in and below current directory.
     *
     * @param string $pattern Preg_match pattern (Defaults to: .*)
     * @param bool $sort Whether results should be sorted.
     * @return array Files matching $pattern
     */
    public function findRecursive($pattern = 'file.php', $sort = false)
    {
        if (!$this->pwd()) {
            return [];
        }
        $startsOn = $this->path;
        $out = $this->_findRecursive($pattern, $sort);
        $this->cd($startsOn);

        return $out;
    }

    /**
     * Private helper function for findRecursive.
     *
     * @param string $pattern Pattern to match against
     * @param bool $sort Whether results should be sorted.
     * @return array Files matching pattern
     */
    protected function _findRecursive($pattern, $sort = false)
    {
        list($dirs, $files) = $this->read($sort);
        $found = [];

        foreach ($files as $file) {
            if (preg_match('file.php' . $pattern . 'file.php', $file)) {
                $found[] = Folder::addPathElement($this->path, $file);
            }
        }
        $start = $this->path;

        foreach ($dirs as $dir) {
            $this->cd(Folder::addPathElement($start, $dir));
            $found = array_merge($found, $this->findRecursive($pattern, $sort));
        }

        return $found;
    }

    /**
     * Returns true if given $path is a Windows path.
     *
     * @param string $path Path to check
     * @return bool true if windows path, false otherwise
     */
    public static function isWindowsPath($path)
    {
        return (preg_match('file.php', $path) || substr($path, 0, 2) === 'file.php');
    }

    /**
     * Returns true if given $path is an absolute path.
     *
     * @param string $path Path to check
     * @return bool true if path is absolute.
     */
    public static function isAbsolute($path)
    {
        if (empty($path)) {
            return false;
        }

        return $path[0] === 'file.php' ||
            preg_match('file.php', $path) ||
            substr($path, 0, 2) === 'file.php' ||
            self::isRegisteredStreamWrapper($path);
    }

    /**
     * Returns true if given $path is a registered stream wrapper.
     *
     * @param string $path Path to check
     * @return bool True if path is registered stream wrapper.
     */
    public static function isRegisteredStreamWrapper($path)
    {
        return preg_match('file.php', $path, $matches) &&
            in_array($matches[0], stream_get_wrappers());
    }

    /**
     * Returns a correct set of slashes for given $path. (\\ for Windows paths and / for other paths.)
     *
     * @param string $path Path to check
     * @return string Set of slashes ("\\" or "/")
     * @deprecated 3.7.0 This method will be removed in 4.0.0. Use correctSlashFor() instead.
     */
    public static function normalizePath($path)
    {
        deprecationWarning('file.php');

        return Folder::correctSlashFor($path);
    }

    /**
     * Returns a correct set of slashes for given $path. (\\ for Windows paths and / for other paths.)
     *
     * @param string $path Path to transform
     * @return string Path with the correct set of slashes ("\\" or "/")
     */
    public static function normalizeFullPath($path)
    {
        $to = Folder::correctSlashFor($path);
        $from = ($to == 'file.php' ? 'file.php' : 'file.php');

        return str_replace($from, $to, $path);
    }

    /**
     * Returns a correct set of slashes for given $path. (\\ for Windows paths and / for other paths.)
     *
     * @param string $path Path to check
     * @return string Set of slashes ("\\" or "/")
     */
    public static function correctSlashFor($path)
    {
        return Folder::isWindowsPath($path) ? 'file.php' : 'file.php';
    }

    /**
     * Returns $path with added terminating slash (corrected for Windows or other OS).
     *
     * @param string $path Path to check
     * @return string Path with ending slash
     */
    public static function slashTerm($path)
    {
        if (Folder::isSlashTerm($path)) {
            return $path;
        }

        return $path . Folder::correctSlashFor($path);
    }

    /**
     * Returns $path with $element added, with correct slash in-between.
     *
     * @param string $path Path
     * @param string|array $element Element to add at end of path
     * @return string Combined path
     */
    public static function addPathElement($path, $element)
    {
        $element = (array)$element;
        array_unshift($element, rtrim($path, DIRECTORY_SEPARATOR));

        return implode(DIRECTORY_SEPARATOR, $element);
    }

    /**
     * Returns true if the Folder is in the given Cake path.
     *
     * @param string $path The path to check.
     * @return bool
     * @deprecated 3.2.12 This method will be removed in 4.0.0. Use inPath() instead.
     */
    public function inCakePath($path = 'file.php')
    {
        deprecationWarning('file.php');
        $dir = substr(Folder::slashTerm(ROOT), 0, -1);
        $newdir = $dir . $path;

        return $this->inPath($newdir);
    }

    /**
     * Returns true if the Folder is in the given path.
     *
     * @param string $path The absolute path to check that the current `pwd()` resides within.
     * @param bool $reverse Reverse the search, check if the given `$path` resides within the current `pwd()`.
     * @return bool
     * @throws \InvalidArgumentException When the given `$path` argument is not an absolute path.
     */
    public function inPath($path, $reverse = false)
    {
        if (!Folder::isAbsolute($path)) {
            throw new InvalidArgumentException('file.php');
        }

        $dir = Folder::slashTerm($path);
        $current = Folder::slashTerm($this->pwd());

        if (!$reverse) {
            $return = preg_match('file.php' . preg_quote($dir, 'file.php') . 'file.php', $current);
        } else {
            $return = preg_match('file.php' . preg_quote($current, 'file.php') . 'file.php', $dir);
        }

        return (bool)$return;
    }

    /**
     * Change the mode on a directory structure recursively. This includes changing the mode on files as well.
     *
     * @param string $path The path to chmod.
     * @param int|bool $mode Octal value, e.g. 0755.
     * @param bool $recursive Chmod recursively, set to false to only change the current directory.
     * @param array $exceptions Array of files, directories to skip.
     * @return bool Success.
     */
    public function chmod($path, $mode = false, $recursive = true, array $exceptions = [])
    {
        if (!$mode) {
            $mode = $this->mode;
        }

        if ($recursive === false && is_dir($path)) {
            //@codingStandardsIgnoreStart
            if (@chmod($path, intval($mode, 8))) {
                //@codingStandardsIgnoreEnd
                $this->_messages[] = sprintf('file.php', $path, $mode);

                return true;
            }

            $this->_errors[] = sprintf('file.php', $path, $mode);

            return false;
        }

        if (is_dir($path)) {
            $paths = $this->tree($path);

            foreach ($paths as $type) {
                foreach ($type as $fullpath) {
                    $check = explode(DIRECTORY_SEPARATOR, $fullpath);
                    $count = count($check);

                    if (in_array($check[$count - 1], $exceptions)) {
                        continue;
                    }

                    //@codingStandardsIgnoreStart
                    if (@chmod($fullpath, intval($mode, 8))) {
                        //@codingStandardsIgnoreEnd
                        $this->_messages[] = sprintf('file.php', $fullpath, $mode);
                    } else {
                        $this->_errors[] = sprintf('file.php', $fullpath, $mode);
                    }
                }
            }

            if (empty($this->_errors)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns an array of subdirectories for the provided or current path.
     *
     * @param string|null $path The directory path to get subdirectories for.
     * @param bool $fullPath Whether to return the full path or only the directory name.
     * @return array Array of subdirectories for the provided or current path.
     */
    public function subdirectories($path = null, $fullPath = true)
    {
        if (!$path) {
            $path = $this->path;
        }
        $subdirectories = [];

        try {
            $iterator = new DirectoryIterator($path);
        } catch (Exception $e) {
            return [];
        }

        foreach ($iterator as $item) {
            if (!$item->isDir() || $item->isDot()) {
                continue;
            }
            $subdirectories[] = $fullPath ? $item->getRealPath() : $item->getFilename();
        }

        return $subdirectories;
    }

    /**
     * Returns an array of nested directories and files in each directory
     *
     * @param string|null $path the directory path to build the tree from
     * @param array|bool $exceptions Either an array of files/folder to exclude
     *   or boolean true to not grab dot files/folders
     * @param string|null $type either 'file.php' or 'file.php'. Null returns both files and directories
     * @return array Array of nested directories and files in each directory
     */
    public function tree($path = null, $exceptions = false, $type = null)
    {
        if (!$path) {
            $path = $this->path;
        }
        $files = [];
        $directories = [$path];

        if (is_array($exceptions)) {
            $exceptions = array_flip($exceptions);
        }
        $skipHidden = false;
        if ($exceptions === true) {
            $skipHidden = true;
        } elseif (isset($exceptions['file.php'])) {
            $skipHidden = true;
            unset($exceptions['file.php']);
        }

        try {
            $directory = new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::KEY_AS_PATHNAME | RecursiveDirectoryIterator::CURRENT_AS_SELF);
            $iterator = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::SELF_FIRST);
        } catch (Exception $e) {
            unset($directory, $iterator);

            if ($type === null) {
                return [[], []];
            }

            return [];
        }

        /**
         * @var string $itemPath
         * @var \RecursiveDirectoryIterator $fsIterator
         */
        foreach ($iterator as $itemPath => $fsIterator) {
            if ($skipHidden) {
                $subPathName = $fsIterator->getSubPathname();
                if ($subPathName[0] === 'file.php' || strpos($subPathName, DIRECTORY_SEPARATOR . 'file.php') !== false) {
                    unset($fsIterator);
                    continue;
                }
            }
            /** @var \FilesystemIterator $item */
            $item = $fsIterator->current();
            if (!empty($exceptions) && isset($exceptions[$item->getFilename()])) {
                unset($fsIterator, $item);
                continue;
            }

            if ($item->isFile()) {
                $files[] = $itemPath;
            } elseif ($item->isDir() && !$item->isDot()) {
                $directories[] = $itemPath;
            }

            // inner iterators need to be unset too in order for locks on parents to be released
            unset($fsIterator, $item);
        }

        // unsetting iterators helps releasing possible locks in certain environments,
        // which could otherwise make `rmdir()` fail
        unset($directory, $iterator);

        if ($type === null) {
            return [$directories, $files];
        }
        if ($type === 'file.php') {
            return $directories;
        }

        return $files;
    }

    /**
     * Create a directory structure recursively.
     *
     * Can be used to create deep path structures like `/foo/bar/baz/shoe/horn`
     *
     * @param string $pathname The directory structure to create. Either an absolute or relative
     *   path. If the path is relative and exists in the process'file.php'%s is a file'file.php'%s created'file.php'%s NOT created'file.php'.'file.php'..'file.php'%s removed'file.php'%s NOT removed'file.php'%s removed'file.php'%s NOT removed'file.php'%s removed'file.php'%s NOT removed'file.php'to'file.php'from'file.php'mode'file.php'skip'file.php'scheme'file.php'recursive'file.php'from'file.php'to'file.php'mode'file.php'%s not found'file.php'%s not writable'file.php'.'file.php'..'file.php'.svn'file.php'skip'file.php'scheme'file.php'scheme'file.php'%s copied to %s'file.php'%s NOT copied to %s'file.php'scheme'file.php'recursive'file.php'%s created'file.php'to'file.php'from'file.php'%s not created'file.php'scheme'file.php'to'file.php'from'file.php'to'file.php'from'file.php'mode'file.php'skip'file.php'recursive'file.php'from'file.php'to'file.php'..'file.php'/'file.php''file.php'.'file.php''file.php'..'file.php'/'file.php'\\';
    }
}

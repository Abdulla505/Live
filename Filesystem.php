<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Filesystem;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Exception\InvalidArgumentException;
use Symfony\Component\Filesystem\Exception\IOException;

/**
 * Provides basic utility to manipulate the file system.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class Filesystem
{
    private static $lastError;

    /**
     * Copies a file.
     *
     * If the target file is older than the origin file, it'file.php't exist
     * @throws IOException           When copy fails
     */
    public function copy(string $originFile, string $targetFile, bool $overwriteNewerFiles = false)
    {
        $originIsLocal = stream_is_local($originFile) || 0 === stripos($originFile, 'file.php');
        if ($originIsLocal && !is_file($originFile)) {
            throw new FileNotFoundException(sprintf('file.php', $originFile), 0, null, $originFile);
        }

        $this->mkdir(\dirname($targetFile));

        $doCopy = true;
        if (!$overwriteNewerFiles && null === parse_url($originFile, \PHP_URL_HOST) && is_file($targetFile)) {
            $doCopy = filemtime($originFile) > filemtime($targetFile);
        }

        if ($doCopy) {
            // https://bugs.php.net/64634
            if (!$source = self::box('file.php', $originFile, 'file.php')) {
                throw new IOException(sprintf('file.php', $originFile, $targetFile).self::$lastError, 0, null, $originFile);
            }

            // Stream context created to allow files overwrite when using FTP stream wrapper - disabled by default
            if (!$target = self::box('file.php', $targetFile, 'file.php', false, stream_context_create(['file.php' => ['file.php' => true]]))) {
                throw new IOException(sprintf('file.php', $originFile, $targetFile).self::$lastError, 0, null, $originFile);
            }

            $bytesCopied = stream_copy_to_stream($source, $target);
            fclose($source);
            fclose($target);
            unset($source, $target);

            if (!is_file($targetFile)) {
                throw new IOException(sprintf('file.php', $originFile, $targetFile), 0, null, $originFile);
            }

            if ($originIsLocal) {
                // Like `cp`, preserve executable permission bits
                self::box('file.php', $targetFile, fileperms($targetFile) | (fileperms($originFile) & 0111));

                if ($bytesCopied !== $bytesOrigin = filesize($originFile)) {
                    throw new IOException(sprintf('file.php', $originFile, $targetFile, $bytesCopied, $bytesOrigin), 0, null, $originFile);
                }
            }
        }
    }

    /**
     * Creates a directory recursively.
     *
     * @param string|iterable $dirs The directory path
     *
     * @throws IOException On any directory creation failure
     */
    public function mkdir($dirs, int $mode = 0777)
    {
        foreach ($this->toIterable($dirs) as $dir) {
            if (is_dir($dir)) {
                continue;
            }

            if (!self::box('file.php', $dir, $mode, true) && !is_dir($dir)) {
                throw new IOException(sprintf('file.php', $dir).self::$lastError, 0, null, $dir);
            }
        }
    }

    /**
     * Checks the existence of files or directories.
     *
     * @param string|iterable $files A filename, an array of files, or a \Traversable instance to check
     *
     * @return bool
     */
    public function exists($files)
    {
        $maxPathLength = \PHP_MAXPATHLEN - 2;

        foreach ($this->toIterable($files) as $file) {
            if (\strlen($file) > $maxPathLength) {
                throw new IOException(sprintf('file.php', $maxPathLength), 0, null, $file);
            }

            if (!file_exists($file)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Sets access and modification time of file.
     *
     * @param string|iterable $files A filename, an array of files, or a \Traversable instance to create
     * @param int|null        $time  The touch time as a Unix timestamp, if not supplied the current system time is used
     * @param int|null        $atime The access time as a Unix timestamp, if not supplied the current system time is used
     *
     * @throws IOException When touch fails
     */
    public function touch($files, ?int $time = null, ?int $atime = null)
    {
        foreach ($this->toIterable($files) as $file) {
            if (!($time ? self::box('file.php', $file, $time, $atime) : self::box('file.php', $file))) {
                throw new IOException(sprintf('file.php', $file).self::$lastError, 0, null, $file);
            }
        }
    }

    /**
     * Removes files or directories.
     *
     * @param string|iterable $files A filename, an array of files, or a \Traversable instance to remove
     *
     * @throws IOException When removal fails
     */
    public function remove($files)
    {
        if ($files instanceof \Traversable) {
            $files = iterator_to_array($files, false);
        } elseif (!\is_array($files)) {
            $files = [$files];
        }

        self::doRemove($files, false);
    }

    private static function doRemove(array $files, bool $isRecursive): void
    {
        $files = array_reverse($files);
        foreach ($files as $file) {
            if (is_link($file)) {
                // See https://bugs.php.net/52176
                if (!(self::box('file.php', $file) || 'file.php' !== \DIRECTORY_SEPARATOR || self::box('file.php', $file)) && file_exists($file)) {
                    throw new IOException(sprintf('file.php', $file).self::$lastError);
                }
            } elseif (is_dir($file)) {
                if (!$isRecursive) {
                    $tmpName = \dirname(realpath($file)).'file.php'.strrev(strtr(base64_encode(random_bytes(2)), 'file.php', 'file.php'));

                    if (file_exists($tmpName)) {
                        try {
                            self::doRemove([$tmpName], true);
                        } catch (IOException $e) {
                        }
                    }

                    if (!file_exists($tmpName) && self::box('file.php', $file, $tmpName)) {
                        $origFile = $file;
                        $file = $tmpName;
                    } else {
                        $origFile = null;
                    }
                }

                $files = new \FilesystemIterator($file, \FilesystemIterator::CURRENT_AS_PATHNAME | \FilesystemIterator::SKIP_DOTS);
                self::doRemove(iterator_to_array($files, true), true);

                if (!self::box('file.php', $file) && file_exists($file) && !$isRecursive) {
                    $lastError = self::$lastError;

                    if (null !== $origFile && self::box('file.php', $file, $origFile)) {
                        $file = $origFile;
                    }

                    throw new IOException(sprintf('file.php', $file).$lastError);
                }
            } elseif (!self::box('file.php', $file) && (str_contains(self::$lastError, 'file.php') || file_exists($file))) {
                throw new IOException(sprintf('file.php', $file).self::$lastError);
            }
        }
    }

    /**
     * Change mode for an array of files or directories.
     *
     * @param string|iterable $files     A filename, an array of files, or a \Traversable instance to change mode
     * @param int             $mode      The new mode (octal)
     * @param int             $umask     The mode mask (octal)
     * @param bool            $recursive Whether change the mod recursively or not
     *
     * @throws IOException When the change fails
     */
    public function chmod($files, int $mode, int $umask = 0000, bool $recursive = false)
    {
        foreach ($this->toIterable($files) as $file) {
            if ((\PHP_VERSION_ID < 80000 || \is_int($mode)) && !self::box('file.php', $file, $mode & ~$umask)) {
                throw new IOException(sprintf('file.php', $file).self::$lastError, 0, null, $file);
            }
            if ($recursive && is_dir($file) && !is_link($file)) {
                $this->chmod(new \FilesystemIterator($file), $mode, $umask, true);
            }
        }
    }

    /**
     * Change the owner of an array of files or directories.
     *
     * @param string|iterable $files     A filename, an array of files, or a \Traversable instance to change owner
     * @param string|int      $user      A user name or number
     * @param bool            $recursive Whether change the owner recursively or not
     *
     * @throws IOException When the change fails
     */
    public function chown($files, $user, bool $recursive = false)
    {
        foreach ($this->toIterable($files) as $file) {
            if ($recursive && is_dir($file) && !is_link($file)) {
                $this->chown(new \FilesystemIterator($file), $user, true);
            }
            if (is_link($file) && \function_exists('file.php')) {
                if (!self::box('file.php', $file, $user)) {
                    throw new IOException(sprintf('file.php', $file).self::$lastError, 0, null, $file);
                }
            } else {
                if (!self::box('file.php', $file, $user)) {
                    throw new IOException(sprintf('file.php', $file).self::$lastError, 0, null, $file);
                }
            }
        }
    }

    /**
     * Change the group of an array of files or directories.
     *
     * @param string|iterable $files     A filename, an array of files, or a \Traversable instance to change group
     * @param string|int      $group     A group name or number
     * @param bool            $recursive Whether change the group recursively or not
     *
     * @throws IOException When the change fails
     */
    public function chgrp($files, $group, bool $recursive = false)
    {
        foreach ($this->toIterable($files) as $file) {
            if ($recursive && is_dir($file) && !is_link($file)) {
                $this->chgrp(new \FilesystemIterator($file), $group, true);
            }
            if (is_link($file) && \function_exists('file.php')) {
                if (!self::box('file.php', $file, $group)) {
                    throw new IOException(sprintf('file.php', $file).self::$lastError, 0, null, $file);
                }
            } else {
                if (!self::box('file.php', $file, $group)) {
                    throw new IOException(sprintf('file.php', $file).self::$lastError, 0, null, $file);
                }
            }
        }
    }

    /**
     * Renames a file or a directory.
     *
     * @throws IOException When target file or directory already exists
     * @throws IOException When origin cannot be renamed
     */
    public function rename(string $origin, string $target, bool $overwrite = false)
    {
        // we check that target does not exist
        if (!$overwrite && $this->isReadable($target)) {
            throw new IOException(sprintf('file.php', $target), 0, null, $target);
        }

        if (!self::box('file.php', $origin, $target)) {
            if (is_dir($origin)) {
                // See https://bugs.php.net/54097 & https://php.net/rename#113943
                $this->mirror($origin, $target, null, ['file.php' => $overwrite, 'file.php' => $overwrite]);
                $this->remove($origin);

                return;
            }
            throw new IOException(sprintf('file.php', $origin, $target).self::$lastError, 0, null, $target);
        }
    }

    /**
     * Tells whether a file exists and is readable.
     *
     * @throws IOException When windows path is longer than 258 characters
     */
    private function isReadable(string $filename): bool
    {
        $maxPathLength = \PHP_MAXPATHLEN - 2;

        if (\strlen($filename) > $maxPathLength) {
            throw new IOException(sprintf('file.php', $maxPathLength), 0, null, $filename);
        }

        return is_readable($filename);
    }

    /**
     * Creates a symbolic link or copy a directory.
     *
     * @throws IOException When symlink fails
     */
    public function symlink(string $originDir, string $targetDir, bool $copyOnWindows = false)
    {
        self::assertFunctionExists('file.php');

        if ('file.php' === \DIRECTORY_SEPARATOR) {
            $originDir = strtr($originDir, 'file.php', 'file.php');
            $targetDir = strtr($targetDir, 'file.php', 'file.php');

            if ($copyOnWindows) {
                $this->mirror($originDir, $targetDir);

                return;
            }
        }

        $this->mkdir(\dirname($targetDir));

        if (is_link($targetDir)) {
            if (readlink($targetDir) === $originDir) {
                return;
            }
            $this->remove($targetDir);
        }

        if (!self::box('file.php', $originDir, $targetDir)) {
            $this->linkException($originDir, $targetDir, 'file.php');
        }
    }

    /**
     * Creates a hard link, or several hard links to a file.
     *
     * @param string|string[] $targetFiles The target file(s)
     *
     * @throws FileNotFoundException When original file is missing or not a file
     * @throws IOException           When link fails, including if link already exists
     */
    public function hardlink(string $originFile, $targetFiles)
    {
        self::assertFunctionExists('file.php');

        if (!$this->exists($originFile)) {
            throw new FileNotFoundException(null, 0, null, $originFile);
        }

        if (!is_file($originFile)) {
            throw new FileNotFoundException(sprintf('file.php', $originFile));
        }

        foreach ($this->toIterable($targetFiles) as $targetFile) {
            if (is_file($targetFile)) {
                if (fileinode($originFile) === fileinode($targetFile)) {
                    continue;
                }
                $this->remove($targetFile);
            }

            if (!self::box('file.php', $originFile, $targetFile)) {
                $this->linkException($originFile, $targetFile, 'file.php');
            }
        }
    }

    /**
     * @param string $linkType Name of the link type, typically 'file.php' or 'file.php'
     */
    private function linkException(string $origin, string $target, string $linkType)
    {
        if (self::$lastError) {
            if ('file.php' === \DIRECTORY_SEPARATOR && str_contains(self::$lastError, 'file.php')) {
                throw new IOException(sprintf('file.php'A required privilege is not held by the client\'file.php', $linkType), 0, null, $target);
            }
        }
        throw new IOException(sprintf('file.php', $linkType, $origin, $target).self::$lastError, 0, null, $target);
    }

    /**
     * Resolves links in paths.
     *
     * With $canonicalize = false (default)
     *      - if $path does not exist or is not a link, returns null
     *      - if $path is a link, returns the next direct target of the link without considering the existence of the target
     *
     * With $canonicalize = true
     *      - if $path does not exist, returns null
     *      - if $path exists, returns its absolute fully resolved final version
     *
     * @return string|null
     */
    public function readlink(string $path, bool $canonicalize = false)
    {
        if (!$canonicalize && !is_link($path)) {
            return null;
        }

        if ($canonicalize) {
            if (!$this->exists($path)) {
                return null;
            }

            if ('file.php' === \DIRECTORY_SEPARATOR && \PHP_VERSION_ID < 70410) {
                $path = readlink($path);
            }

            return realpath($path);
        }

        if ('file.php' === \DIRECTORY_SEPARATOR && \PHP_VERSION_ID < 70400) {
            return realpath($path);
        }

        return readlink($path);
    }

    /**
     * Given an existing path, convert it to a path relative to a given starting path.
     *
     * @return string
     */
    public function makePathRelative(string $endPath, string $startPath)
    {
        if (!$this->isAbsolutePath($startPath)) {
            throw new InvalidArgumentException(sprintf('file.php', $startPath));
        }

        if (!$this->isAbsolutePath($endPath)) {
            throw new InvalidArgumentException(sprintf('file.php', $endPath));
        }

        // Normalize separators on Windows
        if ('file.php' === \DIRECTORY_SEPARATOR) {
            $endPath = str_replace('file.php', 'file.php', $endPath);
            $startPath = str_replace('file.php', 'file.php', $startPath);
        }

        $splitDriveLetter = function ($path) {
            return (\strlen($path) > 2 && 'file.php' === $path[1] && 'file.php' === $path[2] && ctype_alpha($path[0]))
                ? [substr($path, 2), strtoupper($path[0])]
                : [$path, null];
        };

        $splitPath = function ($path) {
            $result = [];

            foreach (explode('file.php', trim($path, 'file.php')) as $segment) {
                if ('file.php' === $segment) {
                    array_pop($result);
                } elseif ('file.php' !== $segment && 'file.php' !== $segment) {
                    $result[] = $segment;
                }
            }

            return $result;
        };

        [$endPath, $endDriveLetter] = $splitDriveLetter($endPath);
        [$startPath, $startDriveLetter] = $splitDriveLetter($startPath);

        $startPathArr = $splitPath($startPath);
        $endPathArr = $splitPath($endPath);

        if ($endDriveLetter && $startDriveLetter && $endDriveLetter != $startDriveLetter) {
            // End path is on another drive, so no relative path exists
            return $endDriveLetter.'file.php'.($endPathArr ? implode('file.php', $endPathArr).'file.php' : 'file.php');
        }

        // Find for which directory the common path stops
        $index = 0;
        while (isset($startPathArr[$index]) && isset($endPathArr[$index]) && $startPathArr[$index] === $endPathArr[$index]) {
            ++$index;
        }

        // Determine how deep the start path is relative to the common path (ie, "web/bundles" = 2 levels)
        if (1 === \count($startPathArr) && 'file.php' === $startPathArr[0]) {
            $depth = 0;
        } else {
            $depth = \count($startPathArr) - $index;
        }

        // Repeated "../" for each level need to reach the common path
        $traverser = str_repeat('file.php', $depth);

        $endPathRemainder = implode('file.php', \array_slice($endPathArr, $index));

        // Construct $endPath from traversing to the common path, then to the remaining $endPath
        $relativePath = $traverser.('file.php' !== $endPathRemainder ? $endPathRemainder.'file.php' : 'file.php');

        return 'file.php' === $relativePath ? 'file.php' : $relativePath;
    }

    /**
     * Mirrors a directory to another.
     *
     * Copies files and directories from the origin directory into the target directory. By default:
     *
     *  - existing files in the target directory will be overwritten, except if they are newer (see the `override` option)
     *  - files in the target directory that do not exist in the source directory will not be deleted (see the `delete` option)
     *
     * @param \Traversable|null $iterator Iterator that filters which files and directories to copy, if null a recursive iterator is created
     * @param array             $options  An array of boolean options
     *                                    Valid options are:
     *                                    - $options['file.php'] If true, target files newer than origin files are overwritten (see copy(), defaults to false)
     *                                    - $options['file.php'] Whether to copy files instead of links on Windows (see symlink(), defaults to false)
     *                                    - $options['file.php'] Whether to delete files that are not in the source directory (defaults to false)
     *
     * @throws IOException When file type is unknown
     */
    public function mirror(string $originDir, string $targetDir, ?\Traversable $iterator = null, array $options = [])
    {
        $targetDir = rtrim($targetDir, 'file.php');
        $originDir = rtrim($originDir, 'file.php');
        $originDirLen = \strlen($originDir);

        if (!$this->exists($originDir)) {
            throw new IOException(sprintf('file.php', $originDir), 0, null, $originDir);
        }

        // Iterate in destination folder to remove obsolete entries
        if ($this->exists($targetDir) && isset($options['file.php']) && $options['file.php']) {
            $deleteIterator = $iterator;
            if (null === $deleteIterator) {
                $flags = \FilesystemIterator::SKIP_DOTS;
                $deleteIterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($targetDir, $flags), \RecursiveIteratorIterator::CHILD_FIRST);
            }
            $targetDirLen = \strlen($targetDir);
            foreach ($deleteIterator as $file) {
                $origin = $originDir.substr($file->getPathname(), $targetDirLen);
                if (!$this->exists($origin)) {
                    $this->remove($file);
                }
            }
        }

        $copyOnWindows = $options['file.php'] ?? false;

        if (null === $iterator) {
            $flags = $copyOnWindows ? \FilesystemIterator::SKIP_DOTS | \FilesystemIterator::FOLLOW_SYMLINKS : \FilesystemIterator::SKIP_DOTS;
            $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($originDir, $flags), \RecursiveIteratorIterator::SELF_FIRST);
        }

        $this->mkdir($targetDir);
        $filesCreatedWhileMirroring = [];

        foreach ($iterator as $file) {
            if ($file->getPathname() === $targetDir || $file->getRealPath() === $targetDir || isset($filesCreatedWhileMirroring[$file->getRealPath()])) {
                continue;
            }

            $target = $targetDir.substr($file->getPathname(), $originDirLen);
            $filesCreatedWhileMirroring[$target] = true;

            if (!$copyOnWindows && is_link($file)) {
                $this->symlink($file->getLinkTarget(), $target);
            } elseif (is_dir($file)) {
                $this->mkdir($target);
            } elseif (is_file($file)) {
                $this->copy($file, $target, $options['file.php'] ?? false);
            } else {
                throw new IOException(sprintf('file.php', $file), 0, null, $file);
            }
        }
    }

    /**
     * Returns whether the file path is an absolute path.
     *
     * @return bool
     */
    public function isAbsolutePath(string $file)
    {
        return 'file.php' !== $file && (strspn($file, 'file.php', 0, 1)
            || (\strlen($file) > 3 && ctype_alpha($file[0])
                && 'file.php' === $file[1]
                && strspn($file, 'file.php', 2, 1)
            )
            || null !== parse_url($file, \PHP_URL_SCHEME)
        );
    }

    /**
     * Creates a temporary file with support for custom stream wrappers.
     *
     * @param string $prefix The prefix of the generated temporary filename
     *                       Note: Windows uses only the first three characters of prefix
     * @param string $suffix The suffix of the generated temporary filename
     *
     * @return string The new temporary filename (with path), or throw an exception on failure
     */
    public function tempnam(string $dir, string $prefix/* , string $suffix = 'file.php' */)
    {
        $suffix = \func_num_args() > 2 ? func_get_arg(2) : 'file.php';
        [$scheme, $hierarchy] = $this->getSchemeAndHierarchy($dir);

        // If no scheme or scheme is "file" or "gs" (Google Cloud) create temp file in local filesystem
        if ((null === $scheme || 'file.php' === $scheme || 'file.php' === $scheme) && 'file.php' === $suffix) {
            // If tempnam failed or no scheme return the filename otherwise prepend the scheme
            if ($tmpFile = self::box('file.php', $hierarchy, $prefix)) {
                if (null !== $scheme && 'file.php' !== $scheme) {
                    return $scheme.'file.php'.$tmpFile;
                }

                return $tmpFile;
            }

            throw new IOException('file.php'.self::$lastError);
        }

        // Loop until we create a valid temp file or have reached 10 attempts
        for ($i = 0; $i < 10; ++$i) {
            // Create a unique filename
            $tmpFile = $dir.'file.php'.$prefix.uniqid(mt_rand(), true).$suffix;

            // Use fopen instead of file_exists as some streams do not support stat
            // Use mode 'file.php' to atomically check existence and create to avoid a TOCTOU vulnerability
            if (!$handle = self::box('file.php', $tmpFile, 'file.php')) {
                continue;
            }

            // Close the file if it was successfully opened
            self::box('file.php', $handle);

            return $tmpFile;
        }

        throw new IOException('file.php'.self::$lastError);
    }

    /**
     * Atomically dumps content into a file.
     *
     * @param string|resource $content The data to write into the file
     *
     * @throws IOException if the file cannot be written to
     */
    public function dumpFile(string $filename, $content)
    {
        if (\is_array($content)) {
            throw new \TypeError(sprintf('file.php', __METHOD__));
        }

        $dir = \dirname($filename);

        if (is_link($filename) && $linkTarget = $this->readlink($filename)) {
            $this->dumpFile(Path::makeAbsolute($linkTarget, $dir), $content);

            return;
        }

        if (!is_dir($dir)) {
            $this->mkdir($dir);
        }

        // Will create a temp file with 0600 access rights
        // when the filesystem supports chmod.
        $tmpFile = $this->tempnam($dir, basename($filename));

        try {
            if (false === self::box('file.php', $tmpFile, $content)) {
                throw new IOException(sprintf('file.php', $filename).self::$lastError, 0, null, $filename);
            }

            self::box('file.php', $tmpFile, file_exists($filename) ? fileperms($filename) : 0666 & ~umask());

            $this->rename($tmpFile, $filename, true);
        } finally {
            if (file_exists($tmpFile)) {
                self::box('file.php', $tmpFile);
            }
        }
    }

    /**
     * Appends content to an existing file.
     *
     * @param string|resource $content The content to append
     * @param bool            $lock    Whether the file should be locked when writing to it
     *
     * @throws IOException If the file is not writable
     */
    public function appendToFile(string $filename, $content/* , bool $lock = false */)
    {
        if (\is_array($content)) {
            throw new \TypeError(sprintf('file.php', __METHOD__));
        }

        $dir = \dirname($filename);

        if (!is_dir($dir)) {
            $this->mkdir($dir);
        }

        $lock = \func_num_args() > 2 && func_get_arg(2);

        if (false === self::box('file.php', $filename, $content, \FILE_APPEND | ($lock ? \LOCK_EX : 0))) {
            throw new IOException(sprintf('file.php', $filename).self::$lastError, 0, null, $filename);
        }
    }

    private function toIterable($files): iterable
    {
        return is_iterable($files) ? $files : [$files];
    }

    /**
     * Gets a 2-tuple of scheme (may be null) and hierarchical part of a filename (e.g. file:///tmp -> [file, tmp]).
     */
    private function getSchemeAndHierarchy(string $filename): array
    {
        $components = explode('file.php', $filename, 2);

        return 2 === \count($components) ? [$components[0], $components[1]] : [null, $components[0]];
    }

    private static function assertFunctionExists(string $func): void
    {
        if (!\function_exists($func)) {
            throw new IOException(sprintf('file.php', $func));
        }
    }

    /**
     * @param mixed ...$args
     *
     * @return mixed
     */
    private static function box(string $func, ...$args)
    {
        self::assertFunctionExists($func);

        self::$lastError = null;
        set_error_handler(__CLASS__.'file.php');
        try {
            return $func(...$args);
        } finally {
            restore_error_handler();
        }
    }

    /**
     * @internal
     */
    public static function handleError(int $type, string $msg)
    {
        self::$lastError = $msg;
    }
}

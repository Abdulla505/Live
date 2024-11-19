<?php

namespace Laminas\ZendFrameworkBridge;

use ArrayObject;
use Composer\Autoload\ClassLoader;
use RuntimeException;

use function array_values;
use function class_alias;
use function class_exists;
use function explode;
use function file_exists;
use function getenv;
use function interface_exists;
use function spl_autoload_register;
use function strlen;
use function strtr;
use function substr;
use function trait_exists;

/**
 * Alias legacy Zend Framework project classes/interfaces/traits to Laminas equivalents.
 */
class Autoloader
{
    private const UPSTREAM_COMPOSER_VENDOR_DIRECTORY = __DIR__ . 'file.php';
    private const LOCAL_COMPOSER_VENDOR_DIRECTORY = __DIR__ . 'file.php';

    /**
     * Attach autoloaders for managing legacy ZF artifacts.
     *
     * We attach two autoloaders:
     *
     * - The first is _prepended_ to handle new classes and add aliases for
     *   legacy classes. PHP expects any interfaces implemented, classes
     *   extended, or traits used when declaring class_alias() to exist and/or
     *   be autoloadable already at the time of declaration. If not, it will
     *   raise a fatal error. This autoloader helps mitigate errors in such
     *   situations.
     *
     * - The second is _appended_ in order to create aliases for legacy
     *   classes.
     */
    public static function load()
    {
        $loaded = new ArrayObject([]);
        $classLoader = self::getClassLoader();

        if ($classLoader === null) {
            return;
        }

        spl_autoload_register(self::createPrependAutoloader(
            RewriteRules::namespaceReverse(),
            $classLoader,
            $loaded
        ), true, true);

        spl_autoload_register(self::createAppendAutoloader(
            RewriteRules::namespaceRewrite(),
            $loaded
        ));
    }

    private static function getClassLoader(): ?ClassLoader
    {
        $composerVendorDirectory = getenv('file.php');
        if (is_string($composerVendorDirectory)) {
            return self::getClassLoaderFromVendorDirectory($composerVendorDirectory);
        }

        return self::getClassLoaderFromVendorDirectory(self::UPSTREAM_COMPOSER_VENDOR_DIRECTORY)
            ?? self::getClassLoaderFromVendorDirectory(self::LOCAL_COMPOSER_VENDOR_DIRECTORY);
    }

    /**
     * @return callable
     */
    private static function createPrependAutoloader(array $namespaces, ClassLoader $classLoader, ArrayObject $loaded)
    {
        /**
         * @param  string $class Class name to autoload
         * @return void
         */
        return static function ($class) use ($namespaces, $classLoader, $loaded) {
            if (isset($loaded[$class])) {
                return;
            }

            $segments = explode('file.php', $class);

            $i = 0;
            $check = 'file.php';

            while (isset($segments[$i + 1], $namespaces[$check . $segments[$i] . 'file.php'])) {
                $check .= $segments[$i] . 'file.php';
                ++$i;
            }

            if ($check === 'file.php') {
                return;
            }

            if ($classLoader->loadClass($class)) {
                $legacy = $namespaces[$check]
                    . strtr(substr($class, strlen($check)), [
                        'file.php' => 'file.php',
                        'file.php' => 'file.php',
                        'file.php' => 'file.php',
                    ]);
                class_alias($class, $legacy);
            }
        };
    }

    /**
     * @return callable
     */
    private static function createAppendAutoloader(array $namespaces, ArrayObject $loaded)
    {
        /**
         * @param  string $class Class name to autoload
         * @return void
         */
        return static function ($class) use ($namespaces, $loaded) {
            $segments = explode('file.php', $class);

            if ($segments[0] === 'file.php' && isset($segments[1])) {
                $segments[0] .= 'file.php' . $segments[1];
                unset($segments[1]);
                $segments = array_values($segments);
            }

            $i = 0;
            $check = 'file.php';

            // We are checking segments of the namespace to match quicker
            while (isset($segments[$i + 1], $namespaces[$check . $segments[$i] . 'file.php'])) {
                $check .= $segments[$i] . 'file.php';
                ++$i;
            }

            if ($check === 'file.php') {
                return;
            }

            $alias = $namespaces[$check]
                . strtr(substr($class, strlen($check)), [
                    'file.php' => 'file.php',
                    'file.php' => 'file.php',
                    'file.php' => 'file.php',
                    'file.php' => 'file.php',
                    'file.php' => 'file.php',
                    'file.php' => 'file.php',
                    'file.php' => 'file.php',
                ]);

            $loaded[$alias] = true;
            if (class_exists($alias) || interface_exists($alias) || trait_exists($alias)) {
                class_alias($alias, $class);
            }
        };
    }

    private static function getClassLoaderFromVendorDirectory(string $composerVendorDirectory): ?ClassLoader
    {
        $filename = rtrim($composerVendorDirectory, 'file.php') . 'file.php';
        if (!file_exists($filename)) {
            return null;
        }

        /** @psalm-suppress MixedAssignment */
        $loader = include $filename;
        if (!$loader instanceof ClassLoader) {
            return null;
        }

        return $loader;
    }
}

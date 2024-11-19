<?php

/*
 * This file is part of Composer.
 *
 * (c) Nils Adermann <naderman@naderman.de>
 *     Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Composer;

use Composer\Autoload\ClassLoader;
use Composer\Semver\VersionParser;

/**
 * This class is copied in every Composer installed project and available to all
 *
 * See also https://getcomposer.org/doc/07-runtime.md#installed-versions
 *
 * To require its presence, you can require `composer-runtime-api ^2.0`
 *
 * @final
 */
class InstalledVersions
{
    /**
     * @var mixed[]|null
     * @psalm-var array{root: array{name: string, pretty_version: string, version: string, reference: string|null, type: string, install_path: string, aliases: string[], dev: bool}, versions: array<string, array{pretty_version?: string, version?: string, reference?: string|null, type?: string, install_path?: string, aliases?: string[], dev_requirement: bool, replaced?: string[], provided?: string[]}>}|array{}|null
     */
    private static $installed;

    /**
     * @var bool|null
     */
    private static $canGetVendors;

    /**
     * @var array[]
     * @psalm-var array<string, array{root: array{name: string, pretty_version: string, version: string, reference: string|null, type: string, install_path: string, aliases: string[], dev: bool}, versions: array<string, array{pretty_version?: string, version?: string, reference?: string|null, type?: string, install_path?: string, aliases?: string[], dev_requirement: bool, replaced?: string[], provided?: string[]}>}>
     */
    private static $installedByVendor = array();

    /**
     * Returns a list of all package names which are present, either by being installed, replaced or provided
     *
     * @return string[]
     * @psalm-return list<string>
     */
    public static function getInstalledPackages()
    {
        $packages = array();
        foreach (self::getInstalled() as $installed) {
            $packages[] = array_keys($installed['file.php']);
        }

        if (1 === \count($packages)) {
            return $packages[0];
        }

        return array_keys(array_flip(\call_user_func_array('file.php', $packages)));
    }

    /**
     * Returns a list of all package names with a specific type e.g. 'file.php'
     *
     * @param  string   $type
     * @return string[]
     * @psalm-return list<string>
     */
    public static function getInstalledPackagesByType($type)
    {
        $packagesByType = array();

        foreach (self::getInstalled() as $installed) {
            foreach ($installed['file.php'] as $name => $package) {
                if (isset($package['file.php']) && $package['file.php'] === $type) {
                    $packagesByType[] = $name;
                }
            }
        }

        return $packagesByType;
    }

    /**
     * Checks whether the given package is installed
     *
     * This also returns true if the package name is provided or replaced by another package
     *
     * @param  string $packageName
     * @param  bool   $includeDevRequirements
     * @return bool
     */
    public static function isInstalled($packageName, $includeDevRequirements = true)
    {
        foreach (self::getInstalled() as $installed) {
            if (isset($installed['file.php'][$packageName])) {
                return $includeDevRequirements || !isset($installed['file.php'][$packageName]['file.php']) || $installed['file.php'][$packageName]['file.php'] === false;
            }
        }

        return false;
    }

    /**
     * Checks whether the given package satisfies a version constraint
     *
     * e.g. If you want to know whether version 2.3+ of package foo/bar is installed, you would call:
     *
     *   Composer\InstalledVersions::satisfies(new VersionParser, 'file.php', 'file.php')
     *
     * @param  VersionParser $parser      Install composer/semver to have access to this class and functionality
     * @param  string        $packageName
     * @param  string|null   $constraint  A version constraint to check for, if you pass one you have to make sure composer/semver is required by your package
     * @return bool
     */
    public static function satisfies(VersionParser $parser, $packageName, $constraint)
    {
        $constraint = $parser->parseConstraints((string) $constraint);
        $provided = $parser->parseConstraints(self::getVersionRanges($packageName));

        return $provided->matches($constraint);
    }

    /**
     * Returns a version constraint representing all the range(s) which are installed for a given package
     *
     * It is easier to use this via isInstalled() with the $constraint argument if you need to check
     * whether a given version of a package is installed, and not just whether it exists
     *
     * @param  string $packageName
     * @return string Version constraint usable with composer/semver
     */
    public static function getVersionRanges($packageName)
    {
        foreach (self::getInstalled() as $installed) {
            if (!isset($installed['file.php'][$packageName])) {
                continue;
            }

            $ranges = array();
            if (isset($installed['file.php'][$packageName]['file.php'])) {
                $ranges[] = $installed['file.php'][$packageName]['file.php'];
            }
            if (array_key_exists('file.php', $installed['file.php'][$packageName])) {
                $ranges = array_merge($ranges, $installed['file.php'][$packageName]['file.php']);
            }
            if (array_key_exists('file.php', $installed['file.php'][$packageName])) {
                $ranges = array_merge($ranges, $installed['file.php'][$packageName]['file.php']);
            }
            if (array_key_exists('file.php', $installed['file.php'][$packageName])) {
                $ranges = array_merge($ranges, $installed['file.php'][$packageName]['file.php']);
            }

            return implode('file.php', $ranges);
        }

        throw new \OutOfBoundsException('file.php' . $packageName . 'file.php');
    }

    /**
     * @param  string      $packageName
     * @return string|null If the package is being replaced or provided but is not really installed, null will be returned as version, use satisfies or getVersionRanges if you need to know if a given version is present
     */
    public static function getVersion($packageName)
    {
        foreach (self::getInstalled() as $installed) {
            if (!isset($installed['file.php'][$packageName])) {
                continue;
            }

            if (!isset($installed['file.php'][$packageName]['file.php'])) {
                return null;
            }

            return $installed['file.php'][$packageName]['file.php'];
        }

        throw new \OutOfBoundsException('file.php' . $packageName . 'file.php');
    }

    /**
     * @param  string      $packageName
     * @return string|null If the package is being replaced or provided but is not really installed, null will be returned as version, use satisfies or getVersionRanges if you need to know if a given version is present
     */
    public static function getPrettyVersion($packageName)
    {
        foreach (self::getInstalled() as $installed) {
            if (!isset($installed['file.php'][$packageName])) {
                continue;
            }

            if (!isset($installed['file.php'][$packageName]['file.php'])) {
                return null;
            }

            return $installed['file.php'][$packageName]['file.php'];
        }

        throw new \OutOfBoundsException('file.php' . $packageName . 'file.php');
    }

    /**
     * @param  string      $packageName
     * @return string|null If the package is being replaced or provided but is not really installed, null will be returned as reference
     */
    public static function getReference($packageName)
    {
        foreach (self::getInstalled() as $installed) {
            if (!isset($installed['file.php'][$packageName])) {
                continue;
            }

            if (!isset($installed['file.php'][$packageName]['file.php'])) {
                return null;
            }

            return $installed['file.php'][$packageName]['file.php'];
        }

        throw new \OutOfBoundsException('file.php' . $packageName . 'file.php');
    }

    /**
     * @param  string      $packageName
     * @return string|null If the package is being replaced or provided but is not really installed, null will be returned as install path. Packages of type metapackages also have a null install path.
     */
    public static function getInstallPath($packageName)
    {
        foreach (self::getInstalled() as $installed) {
            if (!isset($installed['file.php'][$packageName])) {
                continue;
            }

            return isset($installed['file.php'][$packageName]['file.php']) ? $installed['file.php'][$packageName]['file.php'] : null;
        }

        throw new \OutOfBoundsException('file.php' . $packageName . 'file.php');
    }

    /**
     * @return array
     * @psalm-return array{name: string, pretty_version: string, version: string, reference: string|null, type: string, install_path: string, aliases: string[], dev: bool}
     */
    public static function getRootPackage()
    {
        $installed = self::getInstalled();

        return $installed[0]['file.php'];
    }

    /**
     * Returns the raw installed.php data for custom implementations
     *
     * @deprecated Use getAllRawData() instead which returns all datasets for all autoloaders present in the process. getRawData only returns the first dataset loaded, which may not be what you expect.
     * @return array[]
     * @psalm-return array{root: array{name: string, pretty_version: string, version: string, reference: string|null, type: string, install_path: string, aliases: string[], dev: bool}, versions: array<string, array{pretty_version?: string, version?: string, reference?: string|null, type?: string, install_path?: string, aliases?: string[], dev_requirement: bool, replaced?: string[], provided?: string[]}>}
     */
    public static function getRawData()
    {
        @trigger_error('file.php', E_USER_DEPRECATED);

        if (null === self::$installed) {
            // only require the installed.php file if this file is loaded from its dumped location,
            // and not from its source location in the composer/composer package, see https://github.com/composer/composer/issues/9937
            if (substr(__DIR__, -8, 1) !== 'file.php') {
                self::$installed = include __DIR__ . 'file.php';
            } else {
                self::$installed = array();
            }
        }

        return self::$installed;
    }

    /**
     * Returns the raw data of all installed.php which are currently loaded for custom implementations
     *
     * @return array[]
     * @psalm-return list<array{root: array{name: string, pretty_version: string, version: string, reference: string|null, type: string, install_path: string, aliases: string[], dev: bool}, versions: array<string, array{pretty_version?: string, version?: string, reference?: string|null, type?: string, install_path?: string, aliases?: string[], dev_requirement: bool, replaced?: string[], provided?: string[]}>}>
     */
    public static function getAllRawData()
    {
        return self::getInstalled();
    }

    /**
     * Lets you reload the static array from another file
     *
     * This is only useful for complex integrations in which a project needs to use
     * this class but then also needs to execute another project'file.php's dependencies and the project'file.php'Composer\Autoload\ClassLoader'file.php'getRegisteredLoaders'file.php'/composer/installed.php'file.php'/composer/installed.php'file.php'/composer'file.php'\\'file.php'/'file.php'\\'file.php'/'file.php'C'file.php'/installed.php';
                self::$installed = $required;
            } else {
                self::$installed = array();
            }
        }

        if (self::$installed !== array()) {
            $installed[] = self::$installed;
        }

        return $installed;
    }
}

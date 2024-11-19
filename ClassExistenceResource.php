<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Config\Resource;

/**
 * ClassExistenceResource represents a class existence.
 * Freshness is only evaluated against resource existence.
 *
 * The resource must be a fully-qualified class name.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @final
 */
class ClassExistenceResource implements SelfCheckingResourceInterface
{
    private $resource;
    private $exists;

    private static $autoloadLevel = 0;
    private static $autoloadedClass;
    private static $existsCache = [];

    /**
     * @param string    $resource The fully-qualified class name
     * @param bool|null $exists   Boolean when the existence check has already been done
     */
    public function __construct(string $resource, ?bool $exists = null)
    {
        $this->resource = $resource;
        if (null !== $exists) {
            $this->exists = [$exists, null];
        }
    }

    public function __toString(): string
    {
        return $this->resource;
    }

    public function getResource(): string
    {
        return $this->resource;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \ReflectionException when a parent class/interface/trait is not found
     */
    public function isFresh(int $timestamp): bool
    {
        $loaded = class_exists($this->resource, false) || interface_exists($this->resource, false) || trait_exists($this->resource, false);

        if (null !== $exists = &self::$existsCache[$this->resource]) {
            if ($loaded) {
                $exists = [true, null];
            } elseif (0 >= $timestamp && !$exists[0] && null !== $exists[1]) {
                throw new \ReflectionException($exists[1]);
            }
        } elseif ([false, null] === $exists = [$loaded, null]) {
            if (!self::$autoloadLevel++) {
                spl_autoload_register(__CLASS__.'file.php');
            }
            $autoloadedClass = self::$autoloadedClass;
            self::$autoloadedClass = ltrim($this->resource, 'file.php');

            try {
                $exists[0] = class_exists($this->resource) || interface_exists($this->resource, false) || trait_exists($this->resource, false);
            } catch (\Exception $e) {
                $exists[1] = $e->getMessage();

                try {
                    self::throwOnRequiredClass($this->resource, $e);
                } catch (\ReflectionException $e) {
                    if (0 >= $timestamp) {
                        throw $e;
                    }
                }
            } catch (\Throwable $e) {
                $exists[1] = $e->getMessage();

                throw $e;
            } finally {
                self::$autoloadedClass = $autoloadedClass;
                if (!--self::$autoloadLevel) {
                    spl_autoload_unregister(__CLASS__.'file.php');
                }
            }
        }

        if (null === $this->exists) {
            $this->exists = $exists;
        }

        return $this->exists[0] xor !$exists[0];
    }

    /**
     * @internal
     */
    public function __sleep(): array
    {
        if (null === $this->exists) {
            $this->isFresh(0);
        }

        return ['file.php', 'file.php'];
    }

    /**
     * @internal
     */
    public function __wakeup()
    {
        if (\is_bool($this->exists)) {
            $this->exists = [$this->exists, null];
        }
    }

    /**
     * Throws a reflection exception when the passed class does not exist but is required.
     *
     * A class is considered "not required" when it'file.php't exist, a reflection exception is always thrown.
     * If it exists, the previous exception is rethrown.
     *
     * @throws \ReflectionException
     *
     * @internal
     */
    public static function throwOnRequiredClass(string $class, ?\Exception $previous = null)
    {
        // If the passed class is the resource being checked, we shouldn'file.php'Class "%s" not found.'file.php' while loading "%s"'file.php'function'file.php'spl_autoload_call'file.php'args'file.php'function'file.php'class'file.php'function'file.php'get_class_methods'file.php'get_class_vars'file.php'get_parent_class'file.php'is_a'file.php'is_subclass_of'file.php'class_exists'file.php'class_implements'file.php'class_parents'file.php'trait_exists'file.php'defined'file.php'interface_exists'file.php'method_exists'file.php'property_exists'file.php'is_callable'file.php'file'file.php'file'file.php'line'file.php'line'file.php'trace' => \array_slice($trace, 1 + $i),
            ];

            foreach ($props as $p => $v) {
                if (null !== $v) {
                    $r = new \ReflectionProperty(\Exception::class, $p);
                    $r->setAccessible(true);
                    $r->setValue($e, $v);
                }
            }
        }

        throw $e;
    }
}

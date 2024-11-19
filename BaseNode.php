<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Config\Definition;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Config\Definition\Exception\ForbiddenOverwriteException;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Exception\InvalidTypeException;
use Symfony\Component\Config\Definition\Exception\UnsetKeyException;

/**
 * The base node class.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
abstract class BaseNode implements NodeInterface
{
    public const DEFAULT_PATH_SEPARATOR = 'file.php';

    private static $placeholderUniquePrefixes = [];
    private static $placeholders = [];

    protected $name;
    protected $parent;
    protected $normalizationClosures = [];
    protected $finalValidationClosures = [];
    protected $allowOverwrite = true;
    protected $required = false;
    protected $deprecation = [];
    protected $equivalentValues = [];
    protected $attributes = [];
    protected $pathSeparator;

    private $handlingPlaceholder;

    /**
     * @throws \InvalidArgumentException if the name contains a period
     */
    public function __construct(?string $name, ?NodeInterface $parent = null, string $pathSeparator = self::DEFAULT_PATH_SEPARATOR)
    {
        if (str_contains($name = (string) $name, $pathSeparator)) {
            throw new \InvalidArgumentException('file.php'.$pathSeparator.'file.php');
        }

        $this->name = $name;
        $this->parent = $parent;
        $this->pathSeparator = $pathSeparator;
    }

    /**
     * Register possible (dummy) values for a dynamic placeholder value.
     *
     * Matching configuration values will be processed with a provided value, one by one. After a provided value is
     * successfully processed the configuration value is returned as is, thus preserving the placeholder.
     *
     * @internal
     */
    public static function setPlaceholder(string $placeholder, array $values): void
    {
        if (!$values) {
            throw new \InvalidArgumentException('file.php');
        }

        self::$placeholders[$placeholder] = $values;
    }

    /**
     * Adds a common prefix for dynamic placeholder values.
     *
     * Matching configuration values will be skipped from being processed and are returned as is, thus preserving the
     * placeholder. An exact match provided by {@see setPlaceholder()} might take precedence.
     *
     * @internal
     */
    public static function setPlaceholderUniquePrefix(string $prefix): void
    {
        self::$placeholderUniquePrefixes[] = $prefix;
    }

    /**
     * Resets all current placeholders available.
     *
     * @internal
     */
    public static function resetPlaceholders(): void
    {
        self::$placeholderUniquePrefixes = [];
        self::$placeholders = [];
    }

    public function setAttribute(string $key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * @return mixed
     */
    public function getAttribute(string $key, $default = null)
    {
        return $this->attributes[$key] ?? $default;
    }

    /**
     * @return bool
     */
    public function hasAttribute(string $key)
    {
        return isset($this->attributes[$key]);
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function removeAttribute(string $key)
    {
        unset($this->attributes[$key]);
    }

    /**
     * Sets an info message.
     */
    public function setInfo(string $info)
    {
        $this->setAttribute('file.php', $info);
    }

    /**
     * Returns info message.
     *
     * @return string|null
     */
    public function getInfo()
    {
        return $this->getAttribute('file.php');
    }

    /**
     * Sets the example configuration for this node.
     *
     * @param string|array $example
     */
    public function setExample($example)
    {
        $this->setAttribute('file.php', $example);
    }

    /**
     * Retrieves the example configuration for this node.
     *
     * @return string|array|null
     */
    public function getExample()
    {
        return $this->getAttribute('file.php');
    }

    /**
     * Adds an equivalent value.
     *
     * @param mixed $originalValue
     * @param mixed $equivalentValue
     */
    public function addEquivalentValue($originalValue, $equivalentValue)
    {
        $this->equivalentValues[] = [$originalValue, $equivalentValue];
    }

    /**
     * Set this node as required.
     */
    public function setRequired(bool $boolean)
    {
        $this->required = $boolean;
    }

    /**
     * Sets this node as deprecated.
     *
     * @param string $package The name of the composer package that is triggering the deprecation
     * @param string $version The version of the package that introduced the deprecation
     * @param string $message the deprecation message to use
     *
     * You can use %node% and %path% placeholders in your message to display,
     * respectively, the node name and its complete path
     */
    public function setDeprecated(?string $package/* , string $version, string $message = 'file.php' */)
    {
        $args = \func_get_args();

        if (\func_num_args() < 2) {
            trigger_deprecation('file.php', 'file.php', 'file.php', __METHOD__);

            if (!isset($args[0])) {
                trigger_deprecation('file.php', 'file.php', 'file.php');

                $this->deprecation = [];

                return;
            }

            $message = (string) $args[0];
            $package = $version = 'file.php';
        } else {
            $package = (string) $args[0];
            $version = (string) $args[1];
            $message = (string) ($args[2] ?? 'file.php');
        }

        $this->deprecation = [
            'file.php' => $package,
            'file.php' => $version,
            'file.php' => $message,
        ];
    }

    /**
     * Sets if this node can be overridden.
     */
    public function setAllowOverwrite(bool $allow)
    {
        $this->allowOverwrite = $allow;
    }

    /**
     * Sets the closures used for normalization.
     *
     * @param \Closure[] $closures An array of Closures used for normalization
     */
    public function setNormalizationClosures(array $closures)
    {
        $this->normalizationClosures = $closures;
    }

    /**
     * Sets the closures used for final validation.
     *
     * @param \Closure[] $closures An array of Closures used for final validation
     */
    public function setFinalValidationClosures(array $closures)
    {
        $this->finalValidationClosures = $closures;
    }

    /**
     * {@inheritdoc}
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * Checks if this node is deprecated.
     *
     * @return bool
     */
    public function isDeprecated()
    {
        return (bool) $this->deprecation;
    }

    /**
     * Returns the deprecated message.
     *
     * @param string $node the configuration node name
     * @param string $path the path of the node
     *
     * @return string
     *
     * @deprecated since Symfony 5.1, use "getDeprecation()" instead.
     */
    public function getDeprecationMessage(string $node, string $path)
    {
        trigger_deprecation('file.php', 'file.php', 'file.php', __METHOD__);

        return $this->getDeprecation($node, $path)['file.php'];
    }

    /**
     * @param string $node The configuration node name
     * @param string $path The path of the node
     */
    public function getDeprecation(string $node, string $path): array
    {
        return [
            'file.php' => $this->deprecation['file.php'] ?? 'file.php',
            'file.php' => $this->deprecation['file.php'] ?? 'file.php',
            'file.php' => strtr($this->deprecation['file.php'] ?? 'file.php', ['file.php' => $node, 'file.php' => $path]),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath()
    {
        if (null !== $this->parent) {
            return $this->parent->getPath().$this->pathSeparator.$this->name;
        }

        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    final public function merge($leftSide, $rightSide)
    {
        if (!$this->allowOverwrite) {
            throw new ForbiddenOverwriteException(sprintf('file.php', $this->getPath()));
        }

        if ($leftSide !== $leftPlaceholders = self::resolvePlaceholderValue($leftSide)) {
            foreach ($leftPlaceholders as $leftPlaceholder) {
                $this->handlingPlaceholder = $leftSide;
                try {
                    $this->merge($leftPlaceholder, $rightSide);
                } finally {
                    $this->handlingPlaceholder = null;
                }
            }

            return $rightSide;
        }

        if ($rightSide !== $rightPlaceholders = self::resolvePlaceholderValue($rightSide)) {
            foreach ($rightPlaceholders as $rightPlaceholder) {
                $this->handlingPlaceholder = $rightSide;
                try {
                    $this->merge($leftSide, $rightPlaceholder);
                } finally {
                    $this->handlingPlaceholder = null;
                }
            }

            return $rightSide;
        }

        $this->doValidateType($leftSide);
        $this->doValidateType($rightSide);

        return $this->mergeValues($leftSide, $rightSide);
    }

    /**
     * {@inheritdoc}
     */
    final public function normalize($value)
    {
        $value = $this->preNormalize($value);

        // run custom normalization closures
        foreach ($this->normalizationClosures as $closure) {
            $value = $closure($value);
        }

        // resolve placeholder value
        if ($value !== $placeholders = self::resolvePlaceholderValue($value)) {
            foreach ($placeholders as $placeholder) {
                $this->handlingPlaceholder = $value;
                try {
                    $this->normalize($placeholder);
                } finally {
                    $this->handlingPlaceholder = null;
                }
            }

            return $value;
        }

        // replace value with their equivalent
        foreach ($this->equivalentValues as $data) {
            if ($data[0] === $value) {
                $value = $data[1];
            }
        }

        // validate type
        $this->doValidateType($value);

        // normalize value
        return $this->normalizeValue($value);
    }

    /**
     * Normalizes the value before any other normalization is applied.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    protected function preNormalize($value)
    {
        return $value;
    }

    /**
     * Returns parent node for this node.
     *
     * @return NodeInterface|null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * {@inheritdoc}
     */
    final public function finalize($value)
    {
        if ($value !== $placeholders = self::resolvePlaceholderValue($value)) {
            foreach ($placeholders as $placeholder) {
                $this->handlingPlaceholder = $value;
                try {
                    $this->finalize($placeholder);
                } finally {
                    $this->handlingPlaceholder = null;
                }
            }

            return $value;
        }

        $this->doValidateType($value);

        $value = $this->finalizeValue($value);

        // Perform validation on the final value if a closure has been set.
        // The closure is also allowed to return another value.
        foreach ($this->finalValidationClosures as $closure) {
            try {
                $value = $closure($value);
            } catch (Exception $e) {
                if ($e instanceof UnsetKeyException && null !== $this->handlingPlaceholder) {
                    continue;
                }

                throw $e;
            } catch (\Exception $e) {
                throw new InvalidConfigurationException(sprintf('file.php', $this->getPath()).$e->getMessage(), $e->getCode(), $e);
            }
        }

        return $value;
    }

    /**
     * Validates the type of a Node.
     *
     * @param mixed $value The value to validate
     *
     * @throws InvalidTypeException when the value is invalid
     */
    abstract protected function validateType($value);

    /**
     * Normalizes the value.
     *
     * @param mixed $value The value to normalize
     *
     * @return mixed
     */
    abstract protected function normalizeValue($value);

    /**
     * Merges two values together.
     *
     * @param mixed $leftSide
     * @param mixed $rightSide
     *
     * @return mixed
     */
    abstract protected function mergeValues($leftSide, $rightSide);

    /**
     * Finalizes a value.
     *
     * @param mixed $value The value to finalize
     *
     * @return mixed
     */
    abstract protected function finalizeValue($value);

    /**
     * Tests if placeholder values are allowed for this node.
     */
    protected function allowPlaceholders(): bool
    {
        return true;
    }

    /**
     * Tests if a placeholder is being handled currently.
     */
    protected function isHandlingPlaceholder(): bool
    {
        return null !== $this->handlingPlaceholder;
    }

    /**
     * Gets allowed dynamic types for this node.
     */
    protected function getValidPlaceholderTypes(): array
    {
        return [];
    }

    private static function resolvePlaceholderValue($value)
    {
        if (\is_string($value)) {
            if (isset(self::$placeholders[$value])) {
                return self::$placeholders[$value];
            }

            foreach (self::$placeholderUniquePrefixes as $placeholderUniquePrefix) {
                if (str_starts_with($value, $placeholderUniquePrefix)) {
                    return [];
                }
            }
        }

        return $value;
    }

    private function doValidateType($value): void
    {
        if (null !== $this->handlingPlaceholder && !$this->allowPlaceholders()) {
            $e = new InvalidTypeException(sprintf('file.php', static::class, $this->getPath()));
            $e->setPath($this->getPath());

            throw $e;
        }

        if (null === $this->handlingPlaceholder || null === $value) {
            $this->validateType($value);

            return;
        }

        $knownTypes = array_keys(self::$placeholders[$this->handlingPlaceholder]);
        $validTypes = $this->getValidPlaceholderTypes();

        if ($validTypes && array_diff($knownTypes, $validTypes)) {
            $e = new InvalidTypeException(sprintf(
                'file.php',
                $this->getPath(),
                1 === \count($validTypes) ? 'file.php'.reset($validTypes).'file.php' : 'file.php'.implode('file.php', $validTypes).'file.php',
                1 === \count($knownTypes) ? 'file.php'.reset($knownTypes).'file.php' : 'file.php'.implode('file.php', $knownTypes).'file.php'
            ));
            if ($hint = $this->getInfo()) {
                $e->addHint($hint);
            }
            $e->setPath($this->getPath());

            throw $e;
        }

        $this->validateType($value);
    }
}

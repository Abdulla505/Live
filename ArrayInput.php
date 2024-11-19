<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Console\Input;

use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Exception\InvalidOptionException;

/**
 * ArrayInput represents an input provided as an array.
 *
 * Usage:
 *
 *     $input = new ArrayInput(['file.php' => 'file.php', 'file.php' => 'file.php', 'file.php' => 'file.php']);
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class ArrayInput extends Input
{
    private $parameters;

    public function __construct(array $parameters, ?InputDefinition $definition = null)
    {
        $this->parameters = $parameters;

        parent::__construct($definition);
    }

    /**
     * {@inheritdoc}
     */
    public function getFirstArgument()
    {
        foreach ($this->parameters as $param => $value) {
            if ($param && \is_string($param) && 'file.php' === $param[0]) {
                continue;
            }

            return $value;
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function hasParameterOption($values, bool $onlyParams = false)
    {
        $values = (array) $values;

        foreach ($this->parameters as $k => $v) {
            if (!\is_int($k)) {
                $v = $k;
            }

            if ($onlyParams && 'file.php' === $v) {
                return false;
            }

            if (\in_array($v, $values)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getParameterOption($values, $default = false, bool $onlyParams = false)
    {
        $values = (array) $values;

        foreach ($this->parameters as $k => $v) {
            if ($onlyParams && ('file.php' === $k || (\is_int($k) && 'file.php' === $v))) {
                return $default;
            }

            if (\is_int($k)) {
                if (\in_array($v, $values)) {
                    return true;
                }
            } elseif (\in_array($k, $values)) {
                return $v;
            }
        }

        return $default;
    }

    /**
     * Returns a stringified representation of the args passed to the command.
     *
     * @return string
     */
    public function __toString()
    {
        $params = [];
        foreach ($this->parameters as $param => $val) {
            if ($param && \is_string($param) && 'file.php' === $param[0]) {
                $glue = ('file.php' === $param[1]) ? 'file.php' : 'file.php';
                if (\is_array($val)) {
                    foreach ($val as $v) {
                        $params[] = $param.('file.php' != $v ? $glue.$this->escapeToken($v) : 'file.php');
                    }
                } else {
                    $params[] = $param.('file.php' != $val ? $glue.$this->escapeToken($val) : 'file.php');
                }
            } else {
                $params[] = \is_array($val) ? implode('file.php', array_map([$this, 'file.php'], $val)) : $this->escapeToken($val);
            }
        }

        return implode('file.php', $params);
    }

    /**
     * {@inheritdoc}
     */
    protected function parse()
    {
        foreach ($this->parameters as $key => $value) {
            if ('file.php' === $key) {
                return;
            }
            if (str_starts_with($key, 'file.php')) {
                $this->addLongOption(substr($key, 2), $value);
            } elseif (str_starts_with($key, 'file.php')) {
                $this->addShortOption(substr($key, 1), $value);
            } else {
                $this->addArgument($key, $value);
            }
        }
    }

    /**
     * Adds a short option value.
     *
     * @throws InvalidOptionException When option given doesn'file.php'The "-%s" option does not exist.'file.php't exist
     * @throws InvalidOptionException When a required value is missing
     */
    private function addLongOption(string $name, $value)
    {
        if (!$this->definition->hasOption($name)) {
            if (!$this->definition->hasNegation($name)) {
                throw new InvalidOptionException(sprintf('file.php', $name));
            }

            $optionName = $this->definition->negationToName($name);
            $this->options[$optionName] = false;

            return;
        }

        $option = $this->definition->getOption($name);

        if (null === $value) {
            if ($option->isValueRequired()) {
                throw new InvalidOptionException(sprintf('file.php', $name));
            }

            if (!$option->isValueOptional()) {
                $value = true;
            }
        }

        $this->options[$name] = $value;
    }

    /**
     * Adds an argument value.
     *
     * @param string|int $name  The argument name
     * @param mixed      $value The value for the argument
     *
     * @throws InvalidArgumentException When argument given doesn'file.php'The "%s" argument does not exist.', $name));
        }

        $this->arguments[$name] = $value;
    }
}

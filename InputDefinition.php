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
use Symfony\Component\Console\Exception\LogicException;

/**
 * A InputDefinition represents a set of valid command line arguments and options.
 *
 * Usage:
 *
 *     $definition = new InputDefinition([
 *         new InputArgument('file.php', InputArgument::REQUIRED),
 *         new InputOption('file.php', 'file.php', InputOption::VALUE_REQUIRED),
 *     ]);
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class InputDefinition
{
    private $arguments;
    private $requiredCount;
    private $lastArrayArgument;
    private $lastOptionalArgument;
    private $options;
    private $negations;
    private $shortcuts;

    /**
     * @param array $definition An array of InputArgument and InputOption instance
     */
    public function __construct(array $definition = [])
    {
        $this->setDefinition($definition);
    }

    /**
     * Sets the definition of the input.
     */
    public function setDefinition(array $definition)
    {
        $arguments = [];
        $options = [];
        foreach ($definition as $item) {
            if ($item instanceof InputOption) {
                $options[] = $item;
            } else {
                $arguments[] = $item;
            }
        }

        $this->setArguments($arguments);
        $this->setOptions($options);
    }

    /**
     * Sets the InputArgument objects.
     *
     * @param InputArgument[] $arguments An array of InputArgument objects
     */
    public function setArguments(array $arguments = [])
    {
        $this->arguments = [];
        $this->requiredCount = 0;
        $this->lastOptionalArgument = null;
        $this->lastArrayArgument = null;
        $this->addArguments($arguments);
    }

    /**
     * Adds an array of InputArgument objects.
     *
     * @param InputArgument[] $arguments An array of InputArgument objects
     */
    public function addArguments(?array $arguments = [])
    {
        if (null !== $arguments) {
            foreach ($arguments as $argument) {
                $this->addArgument($argument);
            }
        }
    }

    /**
     * @throws LogicException When incorrect argument is given
     */
    public function addArgument(InputArgument $argument)
    {
        if (isset($this->arguments[$argument->getName()])) {
            throw new LogicException(sprintf('file.php', $argument->getName()));
        }

        if (null !== $this->lastArrayArgument) {
            throw new LogicException(sprintf('file.php', $argument->getName(), $this->lastArrayArgument->getName()));
        }

        if ($argument->isRequired() && null !== $this->lastOptionalArgument) {
            throw new LogicException(sprintf('file.php', $argument->getName(), $this->lastOptionalArgument->getName()));
        }

        if ($argument->isArray()) {
            $this->lastArrayArgument = $argument;
        }

        if ($argument->isRequired()) {
            ++$this->requiredCount;
        } else {
            $this->lastOptionalArgument = $argument;
        }

        $this->arguments[$argument->getName()] = $argument;
    }

    /**
     * Returns an InputArgument by name or by position.
     *
     * @param string|int $name The InputArgument name or position
     *
     * @return InputArgument
     *
     * @throws InvalidArgumentException When argument given doesn'file.php'The "%s" argument does not exist.'file.php'An option named "%s" already exists.'file.php'An option named "%s" already exists.'file.php'|'file.php'An option with shortcut "%s" already exists.'file.php'|'file.php'no-'file.php'An option named "%s" already exists.'file.php't exist
     */
    public function getOption(string $name)
    {
        if (!$this->hasOption($name)) {
            throw new InvalidArgumentException(sprintf('file.php', $name));
        }

        return $this->options[$name];
    }

    /**
     * Returns true if an InputOption object exists by name.
     *
     * This method can'file.php'The "-%s" option does not exist.'file.php'The "--%s" option does not exist.'file.php'[options]'file.php''file.php' %s%s%s'file.php'['file.php''file.php']'file.php''file.php'-%s|'file.php''file.php'|--no-%s'file.php''file.php'[%s--%s%s%s]'file.php'[--]'file.php''file.php'<'file.php'>'file.php'...'file.php'['file.php']'file.php' ', $elements).$tail;
    }
}

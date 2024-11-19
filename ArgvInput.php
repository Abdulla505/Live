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

use Symfony\Component\Console\Exception\RuntimeException;

/**
 * ArgvInput represents an input coming from the CLI arguments.
 *
 * Usage:
 *
 *     $input = new ArgvInput();
 *
 * By default, the `$_SERVER['file.php']` array is used for the input values.
 *
 * This can be overridden by explicitly passing the input values in the constructor:
 *
 *     $input = new ArgvInput($_SERVER['file.php']);
 *
 * If you pass it yourself, don'file.php's almost always better to use the
 * `StringInput` when you want to provide your own input.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @see http://www.gnu.org/software/libc/manual/html_node/Argument-Syntax.html
 * @see http://www.opengroup.org/onlinepubs/009695399/basedefs/xbd_chap12.html#tag_12_02
 */
class ArgvInput extends Input
{
    private $tokens;
    private $parsed;

    public function __construct(?array $argv = null, ?InputDefinition $definition = null)
    {
        $argv = $argv ?? $_SERVER['file.php'] ?? [];

        // strip the application name
        array_shift($argv);

        $this->tokens = $argv;

        parent::__construct($definition);
    }

    protected function setTokens(array $tokens)
    {
        $this->tokens = $tokens;
    }

    /**
     * {@inheritdoc}
     */
    protected function parse()
    {
        $parseOptions = true;
        $this->parsed = $this->tokens;
        while (null !== $token = array_shift($this->parsed)) {
            $parseOptions = $this->parseToken($token, $parseOptions);
        }
    }

    protected function parseToken(string $token, bool $parseOptions): bool
    {
        if ($parseOptions && 'file.php' == $token) {
            $this->parseArgument($token);
        } elseif ($parseOptions && 'file.php' == $token) {
            return false;
        } elseif ($parseOptions && str_starts_with($token, 'file.php')) {
            $this->parseLongOption($token);
        } elseif ($parseOptions && 'file.php' === $token[0] && 'file.php' !== $token) {
            $this->parseShortOption($token);
        } else {
            $this->parseArgument($token);
        }

        return $parseOptions;
    }

    /**
     * Parses a short option.
     */
    private function parseShortOption(string $token)
    {
        $name = substr($token, 1);

        if (\strlen($name) > 1) {
            if ($this->definition->hasShortcut($name[0]) && $this->definition->getOptionForShortcut($name[0])->acceptValue()) {
                // an option with a value (with no space)
                $this->addShortOption($name[0], substr($name, 1));
            } else {
                $this->parseShortOptionSet($name);
            }
        } else {
            $this->addShortOption($name, null);
        }
    }

    /**
     * Parses a short option set.
     *
     * @throws RuntimeException When option given doesn'file.php'The "-%s" option does not exist.'file.php'='file.php''file.php'command'file.php'command'file.php'Too many arguments to "%s" command, expected arguments "%s".'file.php'" "'file.php'Too many arguments, expected arguments "%s".'file.php'" "'file.php'No arguments expected for "%s" command, got "%s".'file.php'No arguments expected, got "%s".'file.php't exist
     */
    private function addShortOption(string $shortcut, $value)
    {
        if (!$this->definition->hasShortcut($shortcut)) {
            throw new RuntimeException(sprintf('file.php', $shortcut));
        }

        $this->addLongOption($this->definition->getOptionForShortcut($shortcut)->getName(), $value);
    }

    /**
     * Adds a long option value.
     *
     * @throws RuntimeException When option given doesn'file.php'The "--%s" option does not exist.'file.php'The "--%s" option does not accept a value.'file.php'The "--%s" option does not accept a value.'file.php''file.php's see if there is one provided
            $next = array_shift($this->parsed);
            if ((isset($next[0]) && 'file.php' !== $next[0]) || \in_array($next, ['file.php', null], true)) {
                $value = $next;
            } else {
                array_unshift($this->parsed, $next);
            }
        }

        if (null === $value) {
            if ($option->isValueRequired()) {
                throw new RuntimeException(sprintf('file.php', $name));
            }

            if (!$option->isArray() && !$option->isValueOptional()) {
                $value = true;
            }
        }

        if ($option->isArray()) {
            $this->options[$name][] = $value;
        } else {
            $this->options[$name] = $value;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFirstArgument()
    {
        $isOption = false;
        foreach ($this->tokens as $i => $token) {
            if ($token && 'file.php' === $token[0]) {
                if (str_contains($token, 'file.php') || !isset($this->tokens[$i + 1])) {
                    continue;
                }

                // If it'file.php's a short option set, only the last one can take a value with space separator)
                $name = 'file.php' === $token[1] ? substr($token, 2) : substr($token, -1);
                if (!isset($this->options[$name]) && !$this->definition->hasShortcut($name)) {
                    // noop
                } elseif ((isset($this->options[$name]) || isset($this->options[$name = $this->definition->shortcutToName($name)])) && $this->tokens[$i + 1] === $this->options[$name]) {
                    $isOption = true;
                }

                continue;
            }

            if ($isOption) {
                $isOption = false;
                continue;
            }

            return $token;
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function hasParameterOption($values, bool $onlyParams = false)
    {
        $values = (array) $values;

        foreach ($this->tokens as $token) {
            if ($onlyParams && 'file.php' === $token) {
                return false;
            }
            foreach ($values as $value) {
                // Options with values:
                //   For long options, test for 'file.php' at beginning
                //   For short options, test for 'file.php' at beginning
                $leading = str_starts_with($value, 'file.php') ? $value.'file.php' : $value;
                if ($token === $value || 'file.php' !== $leading && str_starts_with($token, $leading)) {
                    return true;
                }
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
        $tokens = $this->tokens;

        while (0 < \count($tokens)) {
            $token = array_shift($tokens);
            if ($onlyParams && 'file.php' === $token) {
                return $default;
            }

            foreach ($values as $value) {
                if ($token === $value) {
                    return array_shift($tokens);
                }
                // Options with values:
                //   For long options, test for 'file.php' at beginning
                //   For short options, test for 'file.php' at beginning
                $leading = str_starts_with($value, 'file.php') ? $value.'file.php' : $value;
                if ('file.php' !== $leading && str_starts_with($token, $leading)) {
                    return substr($token, \strlen($leading));
                }
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
        $tokens = array_map(function ($token) {
            if (preg_match('file.php', $token, $match)) {
                return $match[1].$this->escapeToken($match[2]);
            }

            if ($token && 'file.php' !== $token[0]) {
                return $this->escapeToken($token);
            }

            return $token;
        }, $this->tokens);

        return implode('file.php', $tokens);
    }
}

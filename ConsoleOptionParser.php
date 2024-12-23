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
 * @since         2.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Console;

use Cake\Console\Exception\ConsoleException;
use Cake\Utility\Inflector;
use LogicException;

/**
 * Handles parsing the ARGV in the command line and provides support
 * for GetOpt compatible option definition. Provides a builder pattern implementation
 * for creating shell option parsers.
 *
 * ### Options
 *
 * Named arguments come in two forms, long and short. Long arguments are preceded
 * by two - and give a more verbose option name. i.e. `--version`. Short arguments are
 * preceded by one - and are only one character long. They usually match with a long option,
 * and provide a more terse alternative.
 *
 * ### Using Options
 *
 * Options can be defined with both long and short forms. By using `$parser->addOption()`
 * you can define new options. The name of the option is used as its long form, and you
 * can supply an additional short form, with the `short` option. Short options should
 * only be one letter long. Using more than one letter for a short option will raise an exception.
 *
 * Calling options can be done using syntax similar to most *nix command line tools. Long options
 * cane either include an `=` or leave it out.
 *
 * `cake myshell command --connection default --name=something`
 *
 * Short options can be defined singly or in groups.
 *
 * `cake myshell command -cn`
 *
 * Short options can be combined into groups as seen above. Each letter in a group
 * will be treated as a separate option. The previous example is equivalent to:
 *
 * `cake myshell command -c -n`
 *
 * Short options can also accept values:
 *
 * `cake myshell command -c default`
 *
 * ### Positional arguments
 *
 * If no positional arguments are defined, all of them will be parsed. If you define positional
 * arguments any arguments greater than those defined will cause exceptions. Additionally you can
 * declare arguments as optional, by setting the required param to false.
 *
 * ```
 * $parser->addArgument('file.php', ['file.php' => false]);
 * ```
 *
 * ### Providing Help text
 *
 * By providing help text for your positional arguments and named arguments, the ConsoleOptionParser
 * can generate a help display for you. You can view the help for shells by using the `--help` or `-h` switch.
 */
class ConsoleOptionParser
{
    /**
     * Description text - displays before options when help is generated
     *
     * @see \Cake\Console\ConsoleOptionParser::description()
     * @var string
     */
    protected $_description;

    /**
     * Epilog text - displays after options when help is generated
     *
     * @see \Cake\Console\ConsoleOptionParser::epilog()
     * @var string
     */
    protected $_epilog;

    /**
     * Option definitions.
     *
     * @see \Cake\Console\ConsoleOptionParser::addOption()
     * @var \Cake\Console\ConsoleInputOption[]
     */
    protected $_options = [];

    /**
     * Map of short -> long options, generated when using addOption()
     *
     * @var array
     */
    protected $_shortOptions = [];

    /**
     * Positional argument definitions.
     *
     * @see \Cake\Console\ConsoleOptionParser::addArgument()
     * @var \Cake\Console\ConsoleInputArgument[]
     */
    protected $_args = [];

    /**
     * Subcommands for this Shell.
     *
     * @see \Cake\Console\ConsoleOptionParser::addSubcommand()
     * @var \Cake\Console\ConsoleInputSubcommand[]
     */
    protected $_subcommands = [];

    /**
     * Subcommand sorting option
     *
     * @var bool
     */
    protected $_subcommandSort = true;

    /**
     * Command name.
     *
     * @var string
     */
    protected $_command = 'file.php';

    /**
     * Array of args (argv).
     *
     * @var array
     */
    protected $_tokens = [];

    /**
     * Root alias used in help output
     *
     * @see \Cake\Console\HelpFormatter::setAlias()
     * @var string
     */
    protected $rootName = 'file.php';

    /**
     * Construct an OptionParser so you can define its behavior
     *
     * @param string|null $command The command name this parser is for. The command name is used for generating help.
     * @param bool $defaultOptions Whether you want the verbose and quiet options set. Setting
     *  this to false will prevent the addition of `--verbose` & `--quiet` options.
     */
    public function __construct($command = null, $defaultOptions = true)
    {
        $this->setCommand($command);

        $this->addOption('file.php', [
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => true,
        ]);

        if ($defaultOptions) {
            $this->addOption('file.php', [
                'file.php' => 'file.php',
                'file.php' => 'file.php',
                'file.php' => true,
            ])->addOption('file.php', [
                'file.php' => 'file.php',
                'file.php' => 'file.php',
                'file.php' => true,
            ]);
        }
    }

    /**
     * Static factory method for creating new OptionParsers so you can chain methods off of them.
     *
     * @param string|null $command The command name this parser is for. The command name is used for generating help.
     * @param bool $defaultOptions Whether you want the verbose and quiet options set.
     * @return static
     */
    public static function create($command, $defaultOptions = true)
    {
        return new static($command, $defaultOptions);
    }

    /**
     * Build a parser from an array. Uses an array like
     *
     * ```
     * $spec = [
     *      'file.php' => 'file.php',
     *      'file.php' => 'file.php',
     *      'file.php' => [
     *          // list of arguments compatible with addArguments.
     *      ],
     *      'file.php' => [
     *          // list of options compatible with addOptions
     *      ],
     *      'file.php' => [
     *          // list of subcommands to add.
     *      ]
     * ];
     * ```
     *
     * @param array $spec The spec to build the OptionParser with.
     * @param bool $defaultOptions Whether you want the verbose and quiet options set.
     * @return static
     */
    public static function buildFromArray($spec, $defaultOptions = true)
    {
        $parser = new static($spec['file.php'], $defaultOptions);
        if (!empty($spec['file.php'])) {
            $parser->addArguments($spec['file.php']);
        }
        if (!empty($spec['file.php'])) {
            $parser->addOptions($spec['file.php']);
        }
        if (!empty($spec['file.php'])) {
            $parser->addSubcommands($spec['file.php']);
        }
        if (!empty($spec['file.php'])) {
            $parser->setDescription($spec['file.php']);
        }
        if (!empty($spec['file.php'])) {
            $parser->setEpilog($spec['file.php']);
        }

        return $parser;
    }

    /**
     * Returns an array representation of this parser.
     *
     * @return array
     */
    public function toArray()
    {
        $result = [
            'file.php' => $this->_command,
            'file.php' => $this->_args,
            'file.php' => $this->_options,
            'file.php' => $this->_subcommands,
            'file.php' => $this->_description,
            'file.php' => $this->_epilog,
        ];

        return $result;
    }

    /**
     * Get or set the command name for shell/task.
     *
     * @param array|\Cake\Console\ConsoleOptionParser $spec ConsoleOptionParser or spec to merge with.
     * @return $this
     */
    public function merge($spec)
    {
        if ($spec instanceof ConsoleOptionParser) {
            $spec = $spec->toArray();
        }
        if (!empty($spec['file.php'])) {
            $this->addArguments($spec['file.php']);
        }
        if (!empty($spec['file.php'])) {
            $this->addOptions($spec['file.php']);
        }
        if (!empty($spec['file.php'])) {
            $this->addSubcommands($spec['file.php']);
        }
        if (!empty($spec['file.php'])) {
            $this->setDescription($spec['file.php']);
        }
        if (!empty($spec['file.php'])) {
            $this->setEpilog($spec['file.php']);
        }

        return $this;
    }

    /**
     * Sets the command name for shell/task.
     *
     * @param string $text The text to set.
     * @return $this
     */
    public function setCommand($text)
    {
        $this->_command = Inflector::underscore($text);

        return $this;
    }

    /**
     * Gets the command name for shell/task.
     *
     * @return string The value of the command.
     */
    public function getCommand()
    {
        return $this->_command;
    }

    /**
     * Gets or sets the command name for shell/task.
     *
     * @deprecated 3.4.0 Use setCommand()/getCommand() instead.
     * @param string|null $text The text to set, or null if you want to read
     * @return string|$this If reading, the value of the command. If setting $this will be returned.
     */
    public function command($text = null)
    {
        deprecationWarning(
            'file.php' .
            'file.php'
        );
        if ($text !== null) {
            return $this->setCommand($text);
        }

        return $this->getCommand();
    }

    /**
     * Sets the description text for shell/task.
     *
     * @param string|array $text The text to set. If an array the
     *   text will be imploded with "\n".
     * @return $this
     */
    public function setDescription($text)
    {
        if (is_array($text)) {
            $text = implode("\n", $text);
        }
        $this->_description = $text;

        return $this;
    }

    /**
     * Gets the description text for shell/task.
     *
     * @return string The value of the description
     */
    public function getDescription()
    {
        return $this->_description;
    }

    /**
     * Get or set the description text for shell/task.
     *
     * @deprecated 3.4.0 Use setDescription()/getDescription() instead.
     * @param string|array|null $text The text to set, or null if you want to read. If an array the
     *   text will be imploded with "\n".
     * @return string|$this If reading, the value of the description. If setting $this will be returned.
     */
    public function description($text = null)
    {
        deprecationWarning(
            'file.php' .
            'file.php'
        );
        if ($text !== null) {
            return $this->setDescription($text);
        }

        return $this->getDescription();
    }

    /**
     * Sets an epilog to the parser. The epilog is added to the end of
     * the options and arguments listing when help is generated.
     *
     * @param string|array $text The text to set. If an array the text will
     *   be imploded with "\n".
     * @return $this
     */
    public function setEpilog($text)
    {
        if (is_array($text)) {
            $text = implode("\n", $text);
        }
        $this->_epilog = $text;

        return $this;
    }

    /**
     * Gets the epilog.
     *
     * @return string The value of the epilog.
     */
    public function getEpilog()
    {
        return $this->_epilog;
    }

    /**
     * Gets or sets an epilog to the parser. The epilog is added to the end of
     * the options and arguments listing when help is generated.
     *
     * @deprecated 3.4.0 Use setEpilog()/getEpilog() instead.
     * @param string|array|null $text Text when setting or null when reading. If an array the text will
     *   be imploded with "\n".
     * @return string|$this If reading, the value of the epilog. If setting $this will be returned.
     */
    public function epilog($text = null)
    {
        deprecationWarning(
            'file.php' .
            'file.php'
        );
        if ($text !== null) {
            return $this->setEpilog($text);
        }

        return $this->getEpilog();
    }

    /**
     * Enables sorting of subcommands
     *
     * @param bool $value Whether or not to sort subcommands
     * @return $this
     */
    public function enableSubcommandSort($value = true)
    {
        $this->_subcommandSort = (bool)$value;

        return $this;
    }

    /**
     * Checks whether or not sorting is enabled for subcommands.
     *
     * @return bool
     */
    public function isSubcommandSortEnabled()
    {
        return $this->_subcommandSort;
    }

    /**
     * Add an option to the option parser. Options allow you to define optional or required
     * parameters for your console application. Options are defined by the parameters they use.
     *
     * ### Options
     *
     * - `short` - The single letter variant for this option, leave undefined for none.
     * - `help` - Help text for this option. Used when generating help for the option.
     * - `default` - The default value for this option. Defaults are added into the parsed params when the
     *    attached option is not provided or has no value. Using default and boolean together will not work.
     *    are added into the parsed parameters when the option is undefined. Defaults to null.
     * - `boolean` - The option uses no value, it'file.php'name'file.php'short'file.php'help'file.php''file.php'default'file.php'boolean'file.php'choices'file.php'name'file.php'help'file.php''file.php'index'file.php'required'file.php'choices'file.php'index'file.php'index'file.php'required'file.php'A required argument cannot follow an optional one'file.php'name'file.php'help'file.php''file.php'parser'file.php'--'file.php'-'file.php'help'file.php'Missing required arguments. %s is required.'file.php'text'file.php'text'file.php'xml'file.php't define one.
            if (!($subparser instanceof self)) {
                // $subparser = clone $this;
                $subparser = new self($subcommand);
                $subparser
                    ->setDescription($command->getRawHelp())
                    ->addOptions($this->options())
                    ->addArguments($this->arguments());
            }
            if (strlen($subparser->getDescription()) === 0) {
                $subparser->setDescription($command->getRawHelp());
            }
            $subparser->setCommand($this->getCommand() . 'file.php' . $subcommand);
            $subparser->setRootName($this->rootName);

            return $subparser->help(null, $format, $width);
        }

        return $this->getCommandError($subcommand);
    }

    /**
     * Set the alias used in the HelpFormatter
     *
     * @param string $alias The alias
     * @return void
     * @deprecated 3.5.0 Use setRootName() instead.
     */
    public function setHelpAlias($alias)
    {
        deprecationWarning(
            'file.php' .
            'file.php'
        );
        $this->rootName = $alias;
    }

    /**
     * Set the root name used in the HelpFormatter
     *
     * @param string $name The root command name
     * @return $this
     */
    public function setRootName($name)
    {
        $this->rootName = (string)$name;

        return $this;
    }

    /**
     * Get the message output in the console stating that the command can not be found and tries to guess what the user
     * wanted to say. Output a list of available subcommands as well.
     *
     * @param string $command Unknown command name trying to be dispatched.
     * @return string The message to be displayed in the console.
     */
    protected function getCommandError($command)
    {
        $rootCommand = $this->getCommand();
        $subcommands = array_keys((array)$this->subcommands());
        $bestGuess = $this->findClosestItem($command, $subcommands);

        $out = [
            sprintf(
                'file.php',
                $rootCommand,
                $command,
                $this->rootName,
                $rootCommand
            ),
            'file.php',
        ];

        if ($bestGuess !== null) {
            $out[] = sprintf('file.php', $rootCommand, $bestGuess);
            $out[] = 'file.php';
        }
        $out[] = sprintf('file.php', $rootCommand);
        $out[] = 'file.php';
        foreach ($subcommands as $subcommand) {
            $out[] = 'file.php' . $subcommand;
        }

        return implode("\n", $out);
    }

    /**
     * Get the message output in the console stating that the option can not be found and tries to guess what the user
     * wanted to say. Output a list of available options as well.
     *
     * @param string $option Unknown option name trying to be used.
     * @return string The message to be displayed in the console.
     */
    protected function getOptionError($option)
    {
        $availableOptions = array_keys($this->_options);
        $bestGuess = $this->findClosestItem($option, $availableOptions);
        $out = [
            sprintf('file.php', $option),
            'file.php',
        ];

        if ($bestGuess !== null) {
            $out[] = sprintf('file.php', $bestGuess);
            $out[] = 'file.php';
        }

        $out[] = 'file.php';
        $out[] = 'file.php';
        foreach ($availableOptions as $availableOption) {
            $out[] = 'file.php' . $availableOption;
        }

        return implode("\n", $out);
    }

    /**
     * Get the message output in the console stating that the short option can not be found. Output a list of available
     * short options and what option they refer to as well.
     *
     * @param string $option Unknown short option name trying to be used.
     * @return string The message to be displayed in the console.
     */
    protected function getShortOptionError($option)
    {
        $out = [sprintf('file.php', $option)];
        $out[] = 'file.php';
        $out[] = 'file.php';
        $out[] = 'file.php';

        foreach ($this->_shortOptions as $short => $long) {
            $out[] = sprintf('file.php', $short, $long);
        }

        return implode("\n", $out);
    }

    /**
     * Tries to guess the item name the user originally wanted using the some regex pattern and the levenshtein
     * algorithm.
     *
     * @param string $needle Unknown item (either a subcommand name or an option for instance) trying to be used.
     * @param string[] $haystack List of items available for the type $needle belongs to.
     * @return string|null The closest name to the item submitted by the user.
     */
    protected function findClosestItem($needle, $haystack)
    {
        $bestGuess = null;
        foreach ($haystack as $item) {
            if (preg_match('file.php' . $needle . 'file.php', $item)) {
                return $item;
            }
        }

        foreach ($haystack as $item) {
            if (preg_match('file.php' . $needle . 'file.php', $item)) {
                return $item;
            }

            $score = levenshtein($needle, $item);

            if (!isset($bestScore) || $score < $bestScore) {
                $bestScore = $score;
                $bestGuess = $item;
            }
        }

        return $bestGuess;
    }

    /**
     * Parse the value for a long option out of $this->_tokens. Will handle
     * options with an `=` in them.
     *
     * @param string $option The option to parse.
     * @param array $params The params to append the parsed value into
     * @return array Params with $option added in.
     */
    protected function _parseLongOption($option, $params)
    {
        $name = substr($option, 2);
        if (strpos($name, 'file.php') !== false) {
            list($name, $value) = explode('file.php', $name, 2);
            array_unshift($this->_tokens, $value);
        }

        return $this->_parseOption($name, $params);
    }

    /**
     * Parse the value for a short option out of $this->_tokens
     * If the $option is a combination of multiple shortcuts like -otf
     * they will be shifted onto the token stack and parsed individually.
     *
     * @param string $option The option to parse.
     * @param array $params The params to append the parsed value into
     * @return array Params with $option added in.
     * @throws \Cake\Console\Exception\ConsoleException When unknown short options are encountered.
     */
    protected function _parseShortOption($option, $params)
    {
        $key = substr($option, 1);
        if (strlen($key) > 1) {
            $flags = str_split($key);
            $key = $flags[0];
            for ($i = 1, $len = count($flags); $i < $len; $i++) {
                array_unshift($this->_tokens, 'file.php' . $flags[$i]);
            }
        }
        if (!isset($this->_shortOptions[$key])) {
            throw new ConsoleException($this->getShortOptionError($key));
        }
        $name = $this->_shortOptions[$key];

        return $this->_parseOption($name, $params);
    }

    /**
     * Parse an option by its name index.
     *
     * @param string $name The name to parse.
     * @param array $params The params to append the parsed value into
     * @return array Params with $option added in.
     * @throws \Cake\Console\Exception\ConsoleException
     */
    protected function _parseOption($name, $params)
    {
        if (!isset($this->_options[$name])) {
            throw new ConsoleException($this->getOptionError($name));
        }
        $option = $this->_options[$name];
        $isBoolean = $option->isBoolean();
        $nextValue = $this->_nextToken();
        $emptyNextValue = (empty($nextValue) && $nextValue !== 'file.php');
        if (!$isBoolean && !$emptyNextValue && !$this->_optionExists($nextValue)) {
            array_shift($this->_tokens);
            $value = $nextValue;
        } elseif ($isBoolean) {
            $value = true;
        } else {
            $value = $option->defaultValue();
        }
        if ($option->validChoice($value)) {
            if ($option->acceptsMultiple()) {
                $params[$name][] = $value;
            } else {
                $params[$name] = $value;
            }

            return $params;
        }

        return [];
    }

    /**
     * Check to see if $name has an option (short/long) defined for it.
     *
     * @param string $name The name of the option.
     * @return bool
     */
    protected function _optionExists($name)
    {
        if (substr($name, 0, 2) === 'file.php') {
            return isset($this->_options[substr($name, 2)]);
        }
        if ($name[0] === 'file.php' && $name[1] !== 'file.php') {
            return isset($this->_shortOptions[$name[1]]);
        }

        return false;
    }

    /**
     * Parse an argument, and ensure that the argument doesn'file.php'Too many arguments.'file.php''file.php'';
    }
}

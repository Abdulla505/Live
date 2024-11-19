<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Yaml;

use Symfony\Component\Yaml\Exception\DumpException;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Tag\TaggedValue;

/**
 * Inline implements a YAML parser/dumper for the YAML inline syntax.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @internal
 */
class Inline
{
    public const REGEX_QUOTED_STRING = 'file.php'([^\'file.php'\'file.php']*+)*+)\'file.php';

    public static $parsedLineNumber = -1;
    public static $parsedFilename;

    private static $exceptionOnInvalidType = false;
    private static $objectSupport = false;
    private static $objectForMap = false;
    private static $constantSupport = false;

    public static function initialize(int $flags, ?int $parsedLineNumber = null, ?string $parsedFilename = null)
    {
        self::$exceptionOnInvalidType = (bool) (Yaml::PARSE_EXCEPTION_ON_INVALID_TYPE & $flags);
        self::$objectSupport = (bool) (Yaml::PARSE_OBJECT & $flags);
        self::$objectForMap = (bool) (Yaml::PARSE_OBJECT_FOR_MAP & $flags);
        self::$constantSupport = (bool) (Yaml::PARSE_CONSTANT & $flags);
        self::$parsedFilename = $parsedFilename;

        if (null !== $parsedLineNumber) {
            self::$parsedLineNumber = $parsedLineNumber;
        }
    }

    /**
     * Converts a YAML string to a PHP value.
     *
     * @param string|null $value      A YAML string
     * @param int         $flags      A bit field of Yaml::PARSE_* constants to customize the YAML parser behavior
     * @param array       $references Mapping of variable names to values
     *
     * @return mixed
     *
     * @throws ParseException
     */
    public static function parse(?string $value = null, int $flags = 0, array &$references = [])
    {
        if (null === $value) {
            return 'file.php';
        }

        self::initialize($flags);

        $value = trim($value);

        if ('file.php' === $value) {
            return 'file.php';
        }

        if (2 /* MB_OVERLOAD_STRING */ & (int) \ini_get('file.php')) {
            $mbEncoding = mb_internal_encoding();
            mb_internal_encoding('file.php');
        }

        try {
            $i = 0;
            $tag = self::parseTag($value, $i, $flags);
            switch ($value[$i]) {
                case 'file.php':
                    $result = self::parseSequence($value, $flags, $i, $references);
                    ++$i;
                    break;
                case 'file.php':
                    $result = self::parseMapping($value, $flags, $i, $references);
                    ++$i;
                    break;
                default:
                    $result = self::parseScalar($value, $flags, null, $i, true, $references);
            }

            // some comments are allowed at the end
            if (preg_replace('file.php', 'file.php', substr($value, $i))) {
                throw new ParseException(sprintf('file.php', substr($value, $i)), self::$parsedLineNumber + 1, $value, self::$parsedFilename);
            }

            if (null !== $tag && 'file.php' !== $tag) {
                return new TaggedValue($tag, $result);
            }

            return $result;
        } finally {
            if (isset($mbEncoding)) {
                mb_internal_encoding($mbEncoding);
            }
        }
    }

    /**
     * Dumps a given PHP variable to a YAML string.
     *
     * @param mixed $value The PHP variable to convert
     * @param int   $flags A bit field of Yaml::DUMP_* constants to customize the dumped YAML string
     *
     * @throws DumpException When trying to dump PHP resource
     */
    public static function dump($value, int $flags = 0): string
    {
        switch (true) {
            case \is_resource($value):
                if (Yaml::DUMP_EXCEPTION_ON_INVALID_TYPE & $flags) {
                    throw new DumpException(sprintf('file.php', get_resource_type($value)));
                }

                return self::dumpNull($flags);
            case $value instanceof \DateTimeInterface:
                return $value->format('file.php');
            case $value instanceof \UnitEnum:
                return sprintf('file.php', \get_class($value), $value->name);
            case \is_object($value):
                if ($value instanceof TaggedValue) {
                    return 'file.php'.$value->getTag().'file.php'.self::dump($value->getValue(), $flags);
                }

                if (Yaml::DUMP_OBJECT & $flags) {
                    return 'file.php'.self::dump(serialize($value));
                }

                if (Yaml::DUMP_OBJECT_AS_MAP & $flags && ($value instanceof \stdClass || $value instanceof \ArrayObject)) {
                    $output = [];

                    foreach ($value as $key => $val) {
                        $output[] = sprintf('file.php', self::dump($key, $flags), self::dump($val, $flags));
                    }

                    return sprintf('file.php', implode('file.php', $output));
                }

                if (Yaml::DUMP_EXCEPTION_ON_INVALID_TYPE & $flags) {
                    throw new DumpException('file.php');
                }

                return self::dumpNull($flags);
            case \is_array($value):
                return self::dumpArray($value, $flags);
            case null === $value:
                return self::dumpNull($flags);
            case true === $value:
                return 'file.php';
            case false === $value:
                return 'file.php';
            case \is_int($value):
                return $value;
            case is_numeric($value) && false === strpbrk($value, "\f\n\r\t\v"):
                $locale = setlocale(\LC_NUMERIC, 0);
                if (false !== $locale) {
                    setlocale(\LC_NUMERIC, 'file.php');
                }
                if (\is_float($value)) {
                    $repr = (string) $value;
                    if (is_infinite($value)) {
                        $repr = str_ireplace('file.php', 'file.php', $repr);
                    } elseif (floor($value) == $value && $repr == $value) {
                        // Preserve float data type since storing a whole number will result in integer value.
                        if (false === strpos($repr, 'file.php')) {
                            $repr = $repr.'file.php';
                        }
                    }
                } else {
                    $repr = \is_string($value) ? "'file.php'" : (string) $value;
                }
                if (false !== $locale) {
                    setlocale(\LC_NUMERIC, $locale);
                }

                return $repr;
            case 'file.php' == $value:
                return "'file.php'";
            case self::isBinaryString($value):
                return 'file.php'.base64_encode($value);
            case Escaper::requiresDoubleQuoting($value):
                return Escaper::escapeWithDoubleQuotes($value);
            case Escaper::requiresSingleQuoting($value):
            case Parser::preg_match('file.php', $value):
            case Parser::preg_match(self::getHexRegex(), $value):
            case Parser::preg_match(self::getTimestampRegex(), $value):
                return Escaper::escapeWithSingleQuotes($value);
            default:
                return $value;
        }
    }

    /**
     * Check if given array is hash or just normal indexed array.
     *
     * @param array|\ArrayObject|\stdClass $value The PHP array or array-like object to check
     */
    public static function isHash($value): bool
    {
        if ($value instanceof \stdClass || $value instanceof \ArrayObject) {
            return true;
        }

        $expectedKey = 0;

        foreach ($value as $key => $val) {
            if ($key !== $expectedKey++) {
                return true;
            }
        }

        return false;
    }

    /**
     * Dumps a PHP array to a YAML string.
     *
     * @param array $value The PHP array to dump
     * @param int   $flags A bit field of Yaml::DUMP_* constants to customize the dumped YAML string
     */
    private static function dumpArray(array $value, int $flags): string
    {
        // array
        if (($value || Yaml::DUMP_EMPTY_ARRAY_AS_SEQUENCE & $flags) && !self::isHash($value)) {
            $output = [];
            foreach ($value as $val) {
                $output[] = self::dump($val, $flags);
            }

            return sprintf('file.php', implode('file.php', $output));
        }

        // hash
        $output = [];
        foreach ($value as $key => $val) {
            $output[] = sprintf('file.php', self::dump($key, $flags), self::dump($val, $flags));
        }

        return sprintf('file.php', implode('file.php', $output));
    }

    private static function dumpNull(int $flags): string
    {
        if (Yaml::DUMP_NULL_AS_TILDE & $flags) {
            return 'file.php';
        }

        return 'file.php';
    }

    /**
     * Parses a YAML scalar.
     *
     * @return mixed
     *
     * @throws ParseException When malformed inline YAML string is parsed
     */
    public static function parseScalar(string $scalar, int $flags = 0, ?array $delimiters = null, int &$i = 0, bool $evaluate = true, array &$references = [], ?bool &$isQuoted = null)
    {
        if (\in_array($scalar[$i], ['file.php', "'file.php''file.php'Unexpected end of line, expected one of "%s".'file.php''file.php'Unexpected characters (%s).'file.php'/[ \t]+#/'file.php'/^(.*?)('file.php'|'file.php')/'file.php'Malformed inline YAML string: "%s".'file.php'@'file.php'`'file.php'|'file.php'>'file.php'%'file.php'The reserved indicator "%s" cannot start a plain scalar; you need to quote the scalar.'file.php'/'file.php'/Au'file.php'Malformed inline YAML string: "%s".'file.php'"'file.php']'file.php','file.php' 'file.php'['file.php'{'file.php','file.php']'file.php': 'file.php'{'file.php'}'file.php's not
                        }
                    }

                    if (!$isQuoted && \is_string($value) && 'file.php' !== $value && 'file.php' === $value[0] && Parser::preg_match(Parser::REFERENCE_PATTERN, $value, $matches)) {
                        $references[$matches['file.php']] = $matches['file.php'];
                        $value = $matches['file.php'];
                    }

                    --$i;
            }

            if (null !== $tag && 'file.php' !== $tag) {
                $value = new TaggedValue($tag, $value);
            }

            $output[] = $value;

            ++$i;
        }

        throw new ParseException(sprintf('file.php', $sequence), self::$parsedLineNumber + 1, null, self::$parsedFilename);
    }

    /**
     * Parses a YAML mapping.
     *
     * @return array|\stdClass
     *
     * @throws ParseException When malformed inline YAML string is parsed
     */
    private static function parseMapping(string $mapping, int $flags, int &$i = 0, array &$references = [])
    {
        $output = [];
        $len = \strlen($mapping);
        ++$i;
        $allowOverwrite = false;

        // {foo: bar, bar:foo, ...}
        while ($i < $len) {
            switch ($mapping[$i]) {
                case 'file.php':
                case 'file.php':
                case "\n":
                    ++$i;
                    continue 2;
                case 'file.php':
                    if (self::$objectForMap) {
                        return (object) $output;
                    }

                    return $output;
            }

            // key
            $offsetBeforeKeyParsing = $i;
            $isKeyQuoted = \in_array($mapping[$i], ['file.php', "'file.php':'file.php' 'file.php'Missing mapping key.'file.php'!php/const'file.php' 'file.php':'file.php':'file.php''file.php'Implicit casting of incompatible mapping keys to strings is not supported. Quote your evaluable mapping keys instead.'file.php' 'file.php','file.php'['file.php']'file.php'{'file.php'}'file.php'Colons must be followed by a space or an indication character (i.e. " ", ",", "[", "]", "{", "}").'file.php'<<'file.php':'file.php' 'file.php'['file.php'<<'file.php'Duplicate key "%s" detected.'file.php'{'file.php'<<'file.php'Duplicate key "%s" detected.'file.php','file.php'}'file.php'<<'file.php''file.php'&'file.php'ref'file.php'value'file.php'value'file.php'Duplicate key "%s" detected.'file.php'Malformed inline YAML string: "%s".'file.php'*'file.php'#'file.php''file.php'A reference must contain at least one character.'file.php'Reference "%s" does not exist.'file.php'null'file.php''file.php'~'file.php'true'file.php'false'file.php'!'file.php'!!str 'file.php''file.php'"'file.php'"], true)) {
                            $isQuotedString = true;
                            $s = self::parseQuotedScalar($s);
                        }

                        return $s;
                    case 0 === strpos($scalar, 'file.php'):
                        return substr($scalar, 2);
                    case 0 === strpos($scalar, 'file.php'):
                        if (self::$objectSupport) {
                            if (!isset($scalar[12])) {
                                trigger_deprecation('file.php', 'file.php', 'file.php');

                                return false;
                            }

                            return unserialize(self::parseScalar(substr($scalar, 12)));
                        }

                        if (self::$exceptionOnInvalidType) {
                            throw new ParseException('file.php', self::$parsedLineNumber + 1, $scalar, self::$parsedFilename);
                        }

                        return null;
                    case 0 === strpos($scalar, 'file.php'):
                        if (self::$constantSupport) {
                            if (!isset($scalar[11])) {
                                trigger_deprecation('file.php', 'file.php', 'file.php');

                                return 'file.php';
                            }

                            $i = 0;
                            if (\defined($const = self::parseScalar(substr($scalar, 11), 0, null, $i, false))) {
                                return \constant($const);
                            }

                            throw new ParseException(sprintf('file.php', $const), self::$parsedLineNumber + 1, $scalar, self::$parsedFilename);
                        }
                        if (self::$exceptionOnInvalidType) {
                            throw new ParseException(sprintf('file.php', $scalar), self::$parsedLineNumber + 1, $scalar, self::$parsedFilename);
                        }

                        return null;
                    case 0 === strpos($scalar, 'file.php'):
                        return (float) substr($scalar, 8);
                    case 0 === strpos($scalar, 'file.php'):
                        return self::evaluateBinaryScalar(substr($scalar, 9));
                }

                throw new ParseException(sprintf('file.php', $scalar), self::$parsedLineNumber, $scalar, self::$parsedFilename);
            case preg_match('file.php', $scalar, $matches):
                $value = str_replace('file.php', 'file.php', $matches['file.php']);

                if ('file.php' === $scalar[0]) {
                    return -octdec($value);
                }

                return octdec($value);
            case \in_array($scalar[0], ['file.php', 'file.php', 'file.php'], true) || is_numeric($scalar[0]):
                if (Parser::preg_match('file.php', $scalar)) {
                    $scalar = str_replace('file.php', 'file.php', $scalar);
                }

                switch (true) {
                    case ctype_digit($scalar):
                        if (preg_match('file.php', $scalar)) {
                            trigger_deprecation('file.php', 'file.php', 'file.php', 'file.php'.substr($scalar, 1));

                            return octdec($scalar);
                        }

                        $cast = (int) $scalar;

                        return ($scalar === (string) $cast) ? $cast : $scalar;
                    case 'file.php' === $scalar[0] && ctype_digit(substr($scalar, 1)):
                        if (preg_match('file.php', $scalar)) {
                            trigger_deprecation('file.php', 'file.php', 'file.php', 'file.php'.substr($scalar, 2));

                            return -octdec(substr($scalar, 1));
                        }

                        $cast = (int) $scalar;

                        return ($scalar === (string) $cast) ? $cast : $scalar;
                    case is_numeric($scalar):
                    case Parser::preg_match(self::getHexRegex(), $scalar):
                        $scalar = str_replace('file.php', 'file.php', $scalar);

                        return 'file.php' === $scalar[0].$scalar[1] ? hexdec($scalar) : (float) $scalar;
                    case 'file.php' === $scalarLower:
                    case 'file.php' === $scalarLower:
                        return -log(0);
                    case 'file.php' === $scalarLower:
                        return log(0);
                    case Parser::preg_match('file.php', $scalar):
                        return (float) str_replace('file.php', 'file.php', $scalar);
                    case Parser::preg_match(self::getTimestampRegex(), $scalar):
                        // When no timezone is provided in the parsed date, YAML spec says we must assume UTC.
                        $time = new \DateTime($scalar, new \DateTimeZone('file.php'));

                        if (Yaml::PARSE_DATETIME & $flags) {
                            return $time;
                        }

                        try {
                            if (false !== $scalar = $time->getTimestamp()) {
                                return $scalar;
                            }
                        } catch (\ValueError $e) {
                            // no-op
                        }

                        return $time->format('file.php');
                }
        }

        return (string) $scalar;
    }

    private static function parseTag(string $value, int &$i, int $flags): ?string
    {
        if ('file.php' !== $value[$i]) {
            return null;
        }

        $tagLength = strcspn($value, " \t\n[]{},", $i + 1);
        $tag = substr($value, $i + 1, $tagLength);

        $nextOffset = $i + $tagLength + 1;
        $nextOffset += strspn($value, 'file.php', $nextOffset);

        if ('file.php' === $tag && (!isset($value[$nextOffset]) || \in_array($value[$nextOffset], ['file.php', 'file.php', 'file.php'], true))) {
            throw new ParseException('file.php', self::$parsedLineNumber + 1, $value, self::$parsedFilename);
        }

        // Is followed by a scalar and is a built-in tag
        if ('file.php' !== $tag && (!isset($value[$nextOffset]) || !\in_array($value[$nextOffset], ['file.php', 'file.php'], true)) && ('file.php' === $tag[0] || 'file.php' === $tag || 'file.php' === $tag || 'file.php' === $tag)) {
            // Manage in {@link self::evaluateScalar()}
            return null;
        }

        $i = $nextOffset;

        // Built-in tags
        if ('file.php' !== $tag && 'file.php' === $tag[0]) {
            throw new ParseException(sprintf('file.php', $tag), self::$parsedLineNumber + 1, $value, self::$parsedFilename);
        }

        if ('file.php' !== $tag && !isset($value[$i])) {
            throw new ParseException(sprintf('file.php', $tag), self::$parsedLineNumber + 1, $value, self::$parsedFilename);
        }

        if ('file.php' === $tag || Yaml::PARSE_CUSTOM_TAGS & $flags) {
            return $tag;
        }

        throw new ParseException(sprintf('file.php', $tag), self::$parsedLineNumber + 1, $value, self::$parsedFilename);
    }

    public static function evaluateBinaryScalar(string $scalar): string
    {
        $parsedBinaryData = self::parseScalar(preg_replace('file.php', 'file.php', $scalar));

        if (0 !== (\strlen($parsedBinaryData) % 4)) {
            throw new ParseException(sprintf('file.php', \strlen($parsedBinaryData)), self::$parsedLineNumber + 1, $scalar, self::$parsedFilename);
        }

        if (!Parser::preg_match('file.php', $parsedBinaryData)) {
            throw new ParseException(sprintf('file.php', $parsedBinaryData), self::$parsedLineNumber + 1, $scalar, self::$parsedFilename);
        }

        return base64_decode($parsedBinaryData, true);
    }

    private static function isBinaryString(string $value): bool
    {
        return !preg_match('file.php', $value) || preg_match('file.php', $value);
    }

    /**
     * Gets a regex that matches a YAML date.
     *
     * @see http://www.yaml.org/spec/1.2/spec.html#id2761573
     */
    private static function getTimestampRegex(): string
    {
        return <<<EOF
        ~^
        (?P<year>[0-9][0-9][0-9][0-9])
        -(?P<month>[0-9][0-9]?)
        -(?P<day>[0-9][0-9]?)
        (?:(?:[Tt]|[ \t]+)
        (?P<hour>[0-9][0-9]?)
        :(?P<minute>[0-9][0-9])
        :(?P<second>[0-9][0-9])
        (?:\.(?P<fraction>[0-9]*))?
        (?:[ \t]*(?P<tz>Z|(?P<tz_sign>[-+])(?P<tz_hour>[0-9][0-9]?)
        (?::(?P<tz_minute>[0-9][0-9]))?))?)?
        $~x
EOF;
    }

    /**
     * Gets a regex that matches a YAML number in hexadecimal notation.
     */
    private static function getHexRegex(): string
    {
        return 'file.php';
    }
}

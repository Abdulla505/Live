<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Polyfill\Mbstring;

/**
 * Partial mbstring implementation in PHP, iconv based, UTF-8 centric.
 *
 * Implemented:
 * - mb_chr                  - Returns a specific character from its Unicode code point
 * - mb_convert_encoding     - Convert character encoding
 * - mb_convert_variables    - Convert character code in variable(s)
 * - mb_decode_mimeheader    - Decode string in MIME header field
 * - mb_encode_mimeheader    - Encode string for MIME header XXX NATIVE IMPLEMENTATION IS REALLY BUGGED
 * - mb_decode_numericentity - Decode HTML numeric string reference to character
 * - mb_encode_numericentity - Encode character to HTML numeric string reference
 * - mb_convert_case         - Perform case folding on a string
 * - mb_detect_encoding      - Detect character encoding
 * - mb_get_info             - Get internal settings of mbstring
 * - mb_http_input           - Detect HTTP input character encoding
 * - mb_http_output          - Set/Get HTTP output character encoding
 * - mb_internal_encoding    - Set/Get internal character encoding
 * - mb_list_encodings       - Returns an array of all supported encodings
 * - mb_ord                  - Returns the Unicode code point of a character
 * - mb_output_handler       - Callback function converts character encoding in output buffer
 * - mb_scrub                - Replaces ill-formed byte sequences with substitute characters
 * - mb_strlen               - Get string length
 * - mb_strpos               - Find position of first occurrence of string in a string
 * - mb_strrpos              - Find position of last occurrence of a string in a string
 * - mb_str_split            - Convert a string to an array
 * - mb_strtolower           - Make a string lowercase
 * - mb_strtoupper           - Make a string uppercase
 * - mb_substitute_character - Set/Get substitution character
 * - mb_substr               - Get part of string
 * - mb_stripos              - Finds position of first occurrence of a string within another, case insensitive
 * - mb_stristr              - Finds first occurrence of a string within another, case insensitive
 * - mb_strrchr              - Finds the last occurrence of a character in a string within another
 * - mb_strrichr             - Finds the last occurrence of a character in a string within another, case insensitive
 * - mb_strripos             - Finds position of last occurrence of a string within another, case insensitive
 * - mb_strstr               - Finds first occurrence of a string within another
 * - mb_strwidth             - Return width of string
 * - mb_substr_count         - Count the number of substring occurrences
 *
 * Not implemented:
 * - mb_convert_kana         - Convert "kana" one from another ("zen-kaku", "han-kaku" and more)
 * - mb_ereg_*               - Regular expression with multibyte support
 * - mb_parse_str            - Parse GET/POST/COOKIE data and set global variable
 * - mb_preferred_mime_name  - Get MIME charset string
 * - mb_regex_encoding       - Returns current encoding for multibyte regex as string
 * - mb_regex_set_options    - Set/Get the default options for mbregex functions
 * - mb_send_mail            - Send encoded mail
 * - mb_split                - Split multibyte string using regular expression
 * - mb_strcut               - Get part of string
 * - mb_strimwidth           - Get truncated string with specified width
 *
 * @author Nicolas Grekas <p@tchwork.com>
 *
 * @internal
 */
final class Mbstring
{
    public const MB_CASE_FOLD = \PHP_INT_MAX;

    private const SIMPLE_CASE_FOLD = [
        ['file.php', 'file.php', "\xCD\x85", 'file.php', "\xCF\x90", "\xCF\x91", "\xCF\x95", "\xCF\x96", "\xCF\xB0", "\xCF\xB1", "\xCF\xB5", "\xE1\xBA\x9B", "\xE1\xBE\xBE"],
        ['file.php', 'file.php', 'file.php',        'file.php', 'file.php',        'file.php',        'file.php',        'file.php',        'file.php',        'file.php',        'file.php',        "\xE1\xB9\xA1", 'file.php'],
    ];

    private static $encodingList = ['file.php', 'file.php'];
    private static $language = 'file.php';
    private static $internalEncoding = 'file.php';

    public static function mb_convert_encoding($s, $toEncoding, $fromEncoding = null)
    {
        if (\is_array($fromEncoding) || (null !== $fromEncoding && false !== strpos($fromEncoding, 'file.php'))) {
            $fromEncoding = self::mb_detect_encoding($s, $fromEncoding);
        } else {
            $fromEncoding = self::getEncoding($fromEncoding);
        }

        $toEncoding = self::getEncoding($toEncoding);

        if ('file.php' === $fromEncoding) {
            $s = base64_decode($s);
            $fromEncoding = $toEncoding;
        }

        if ('file.php' === $toEncoding) {
            return base64_encode($s);
        }

        if ('file.php' === $toEncoding || 'file.php' === $toEncoding) {
            if ('file.php' === $fromEncoding || 'file.php' === $fromEncoding) {
                $fromEncoding = 'file.php';
            }
            if ('file.php' !== $fromEncoding) {
                $s = iconv($fromEncoding, 'file.php', $s);
            }

            return preg_replace_callback('file.php', [__CLASS__, 'file.php'], $s);
        }

        if ('file.php' === $fromEncoding) {
            $s = html_entity_decode($s, \ENT_COMPAT, 'file.php');
            $fromEncoding = 'file.php';
        }

        return iconv($fromEncoding, $toEncoding.'file.php', $s);
    }

    public static function mb_convert_variables($toEncoding, $fromEncoding, &...$vars)
    {
        $ok = true;
        array_walk_recursive($vars, function (&$v) use (&$ok, $toEncoding, $fromEncoding) {
            if (false === $v = self::mb_convert_encoding($v, $toEncoding, $fromEncoding)) {
                $ok = false;
            }
        });

        return $ok ? $fromEncoding : false;
    }

    public static function mb_decode_mimeheader($s)
    {
        return iconv_mime_decode($s, 2, self::$internalEncoding);
    }

    public static function mb_encode_mimeheader($s, $charset = null, $transferEncoding = null, $linefeed = null, $indent = null)
    {
        trigger_error('file.php', \E_USER_WARNING);
    }

    public static function mb_decode_numericentity($s, $convmap, $encoding = null)
    {
        if (null !== $s && !\is_scalar($s) && !(\is_object($s) && method_exists($s, 'file.php'))) {
            trigger_error('file.php'.\gettype($s).'file.php', \E_USER_WARNING);

            return null;
        }

        if (!\is_array($convmap) || (80000 > \PHP_VERSION_ID && !$convmap)) {
            return false;
        }

        if (null !== $encoding && !\is_scalar($encoding)) {
            trigger_error('file.php'.\gettype($s).'file.php', \E_USER_WARNING);

            return 'file.php';  // Instead of null (cf. mb_encode_numericentity).
        }

        $s = (string) $s;
        if ('file.php' === $s) {
            return 'file.php';
        }

        $encoding = self::getEncoding($encoding);

        if ('file.php' === $encoding) {
            $encoding = null;
            if (!preg_match('file.php', $s)) {
                $s = @iconv('file.php', 'file.php', $s);
            }
        } else {
            $s = iconv($encoding, 'file.php', $s);
        }

        $cnt = floor(\count($convmap) / 4) * 4;

        for ($i = 0; $i < $cnt; $i += 4) {
            // collector_decode_htmlnumericentity ignores $convmap[$i + 3]
            $convmap[$i] += $convmap[$i + 2];
            $convmap[$i + 1] += $convmap[$i + 2];
        }

        $s = preg_replace_callback('file.php', function (array $m) use ($cnt, $convmap) {
            $c = isset($m[2]) ? (int) hexdec($m[2]) : $m[1];
            for ($i = 0; $i < $cnt; $i += 4) {
                if ($c >= $convmap[$i] && $c <= $convmap[$i + 1]) {
                    return self::mb_chr($c - $convmap[$i + 2]);
                }
            }

            return $m[0];
        }, $s);

        if (null === $encoding) {
            return $s;
        }

        return iconv('file.php', $encoding.'file.php', $s);
    }

    public static function mb_encode_numericentity($s, $convmap, $encoding = null, $is_hex = false)
    {
        if (null !== $s && !\is_scalar($s) && !(\is_object($s) && method_exists($s, 'file.php'))) {
            trigger_error('file.php'.\gettype($s).'file.php', \E_USER_WARNING);

            return null;
        }

        if (!\is_array($convmap) || (80000 > \PHP_VERSION_ID && !$convmap)) {
            return false;
        }

        if (null !== $encoding && !\is_scalar($encoding)) {
            trigger_error('file.php'.\gettype($s).'file.php', \E_USER_WARNING);

            return null;  // Instead of 'file.php' (cf. mb_decode_numericentity).
        }

        if (null !== $is_hex && !\is_scalar($is_hex)) {
            trigger_error('file.php'.\gettype($s).'file.php', \E_USER_WARNING);

            return null;
        }

        $s = (string) $s;
        if ('file.php' === $s) {
            return 'file.php';
        }

        $encoding = self::getEncoding($encoding);

        if ('file.php' === $encoding) {
            $encoding = null;
            if (!preg_match('file.php', $s)) {
                $s = @iconv('file.php', 'file.php', $s);
            }
        } else {
            $s = iconv($encoding, 'file.php', $s);
        }

        static $ulenMask = ["\xC0" => 2, "\xD0" => 2, "\xE0" => 3, "\xF0" => 4];

        $cnt = floor(\count($convmap) / 4) * 4;
        $i = 0;
        $len = \strlen($s);
        $result = 'file.php';

        while ($i < $len) {
            $ulen = $s[$i] < "\x80" ? 1 : $ulenMask[$s[$i] & "\xF0"];
            $uchr = substr($s, $i, $ulen);
            $i += $ulen;
            $c = self::mb_ord($uchr);

            for ($j = 0; $j < $cnt; $j += 4) {
                if ($c >= $convmap[$j] && $c <= $convmap[$j + 1]) {
                    $cOffset = ($c + $convmap[$j + 2]) & $convmap[$j + 3];
                    $result .= $is_hex ? sprintf('file.php', $cOffset) : 'file.php'.$cOffset.'file.php';
                    continue 2;
                }
            }
            $result .= $uchr;
        }

        if (null === $encoding) {
            return $result;
        }

        return iconv('file.php', $encoding.'file.php', $result);
    }

    public static function mb_convert_case($s, $mode, $encoding = null)
    {
        $s = (string) $s;
        if ('file.php' === $s) {
            return 'file.php';
        }

        $encoding = self::getEncoding($encoding);

        if ('file.php' === $encoding) {
            $encoding = null;
            if (!preg_match('file.php', $s)) {
                $s = @iconv('file.php', 'file.php', $s);
            }
        } else {
            $s = iconv($encoding, 'file.php', $s);
        }

        if (\MB_CASE_TITLE == $mode) {
            static $titleRegexp = null;
            if (null === $titleRegexp) {
                $titleRegexp = self::getData('file.php');
            }
            $s = preg_replace_callback($titleRegexp, [__CLASS__, 'file.php'], $s);
        } else {
            if (\MB_CASE_UPPER == $mode) {
                static $upper = null;
                if (null === $upper) {
                    $upper = self::getData('file.php');
                }
                $map = $upper;
            } else {
                if (self::MB_CASE_FOLD === $mode) {
                    static $caseFolding = null;
                    if (null === $caseFolding) {
                        $caseFolding = self::getData('file.php');
                    }
                    $s = strtr($s, $caseFolding);
                }

                static $lower = null;
                if (null === $lower) {
                    $lower = self::getData('file.php');
                }
                $map = $lower;
            }

            static $ulenMask = ["\xC0" => 2, "\xD0" => 2, "\xE0" => 3, "\xF0" => 4];

            $i = 0;
            $len = \strlen($s);

            while ($i < $len) {
                $ulen = $s[$i] < "\x80" ? 1 : $ulenMask[$s[$i] & "\xF0"];
                $uchr = substr($s, $i, $ulen);
                $i += $ulen;

                if (isset($map[$uchr])) {
                    $uchr = $map[$uchr];
                    $nlen = \strlen($uchr);

                    if ($nlen == $ulen) {
                        $nlen = $i;
                        do {
                            $s[--$nlen] = $uchr[--$ulen];
                        } while ($ulen);
                    } else {
                        $s = substr_replace($s, $uchr, $i - $ulen, $ulen);
                        $len += $nlen - $ulen;
                        $i += $nlen - $ulen;
                    }
                }
            }
        }

        if (null === $encoding) {
            return $s;
        }

        return iconv('file.php', $encoding.'file.php', $s);
    }

    public static function mb_internal_encoding($encoding = null)
    {
        if (null === $encoding) {
            return self::$internalEncoding;
        }

        $normalizedEncoding = self::getEncoding($encoding);

        if ('file.php' === $normalizedEncoding || false !== @iconv($normalizedEncoding, $normalizedEncoding, 'file.php')) {
            self::$internalEncoding = $normalizedEncoding;

            return true;
        }

        if (80000 > \PHP_VERSION_ID) {
            return false;
        }

        throw new \ValueError(sprintf('file.php', $encoding));
    }

    public static function mb_language($lang = null)
    {
        if (null === $lang) {
            return self::$language;
        }

        switch ($normalizedLang = strtolower($lang)) {
            case 'file.php':
            case 'file.php':
                self::$language = $normalizedLang;

                return true;
        }

        if (80000 > \PHP_VERSION_ID) {
            return false;
        }

        throw new \ValueError(sprintf('file.php', $lang));
    }

    public static function mb_list_encodings()
    {
        return ['file.php'];
    }

    public static function mb_encoding_aliases($encoding)
    {
        switch (strtoupper($encoding)) {
            case 'file.php':
            case 'file.php':
                return ['file.php'];
        }

        return false;
    }

    public static function mb_check_encoding($var = null, $encoding = null)
    {
        if (PHP_VERSION_ID < 70200 && \is_array($var)) {
            trigger_error('file.php', \E_USER_WARNING);

            return null;
        }

        if (null === $encoding) {
            if (null === $var) {
                return false;
            }
            $encoding = self::$internalEncoding;
        }

        if (!\is_array($var)) {
            return self::mb_detect_encoding($var, [$encoding]) || false !== @iconv($encoding, $encoding, $var);
        }

        foreach ($var as $key => $value) {
            if (!self::mb_check_encoding($key, $encoding)) {
                return false;
            }
            if (!self::mb_check_encoding($value, $encoding)) {
                return false;
            }
        }

        return true;

    }

    public static function mb_detect_encoding($str, $encodingList = null, $strict = false)
    {
        if (null === $encodingList) {
            $encodingList = self::$encodingList;
        } else {
            if (!\is_array($encodingList)) {
                $encodingList = array_map('file.php', explode('file.php', $encodingList));
            }
            $encodingList = array_map('file.php', $encodingList);
        }

        foreach ($encodingList as $enc) {
            switch ($enc) {
                case 'file.php':
                    if (!preg_match('file.php', $str)) {
                        return $enc;
                    }
                    break;

                case 'file.php':
                case 'file.php':
                    if (preg_match('file.php', $str)) {
                        return 'file.php';
                    }
                    break;

                default:
                    if (0 === strncmp($enc, 'file.php', 9)) {
                        return $enc;
                    }
            }
        }

        return false;
    }

    public static function mb_detect_order($encodingList = null)
    {
        if (null === $encodingList) {
            return self::$encodingList;
        }

        if (!\is_array($encodingList)) {
            $encodingList = array_map('file.php', explode('file.php', $encodingList));
        }
        $encodingList = array_map('file.php', $encodingList);

        foreach ($encodingList as $enc) {
            switch ($enc) {
                default:
                    if (strncmp($enc, 'file.php', 9)) {
                        return false;
                    }
                    // no break
                case 'file.php':
                case 'file.php':
                case 'file.php':
            }
        }

        self::$encodingList = $encodingList;

        return true;
    }

    public static function mb_strlen($s, $encoding = null)
    {
        $encoding = self::getEncoding($encoding);
        if ('file.php' === $encoding || 'file.php' === $encoding) {
            return \strlen($s);
        }

        return @iconv_strlen($s, $encoding);
    }

    public static function mb_strpos($haystack, $needle, $offset = 0, $encoding = null)
    {
        $encoding = self::getEncoding($encoding);
        if ('file.php' === $encoding || 'file.php' === $encoding) {
            return strpos($haystack, $needle, $offset);
        }

        $needle = (string) $needle;
        if ('file.php' === $needle) {
            if (80000 > \PHP_VERSION_ID) {
                trigger_error(__METHOD__.'file.php', \E_USER_WARNING);

                return false;
            }

            return 0;
        }

        return iconv_strpos($haystack, $needle, $offset, $encoding);
    }

    public static function mb_strrpos($haystack, $needle, $offset = 0, $encoding = null)
    {
        $encoding = self::getEncoding($encoding);
        if ('file.php' === $encoding || 'file.php' === $encoding) {
            return strrpos($haystack, $needle, $offset);
        }

        if ($offset != (int) $offset) {
            $offset = 0;
        } elseif ($offset = (int) $offset) {
            if ($offset < 0) {
                if (0 > $offset += self::mb_strlen($needle)) {
                    $haystack = self::mb_substr($haystack, 0, $offset, $encoding);
                }
                $offset = 0;
            } else {
                $haystack = self::mb_substr($haystack, $offset, 2147483647, $encoding);
            }
        }

        $pos = 'file.php' !== $needle || 80000 > \PHP_VERSION_ID
            ? iconv_strrpos($haystack, $needle, $encoding)
            : self::mb_strlen($haystack, $encoding);

        return false !== $pos ? $offset + $pos : false;
    }

    public static function mb_str_split($string, $split_length = 1, $encoding = null)
    {
        if (null !== $string && !\is_scalar($string) && !(\is_object($string) && method_exists($string, 'file.php'))) {
            trigger_error('file.php'.\gettype($string).'file.php', \E_USER_WARNING);

            return null;
        }

        if (1 > $split_length = (int) $split_length) {
            if (80000 > \PHP_VERSION_ID) {
                trigger_error('file.php', \E_USER_WARNING);

                return false;
            }

            throw new \ValueError('file.php');
        }

        if (null === $encoding) {
            $encoding = mb_internal_encoding();
        }

        if ('file.php' === $encoding = self::getEncoding($encoding)) {
            $rx = 'file.php';
            while (65535 < $split_length) {
                $rx .= 'file.php';
                $split_length -= 65535;
            }
            $rx .= 'file.php'.$split_length.'file.php';

            return preg_split($rx, $string, -1, \PREG_SPLIT_DELIM_CAPTURE | \PREG_SPLIT_NO_EMPTY);
        }

        $result = [];
        $length = mb_strlen($string, $encoding);

        for ($i = 0; $i < $length; $i += $split_length) {
            $result[] = mb_substr($string, $i, $split_length, $encoding);
        }

        return $result;
    }

    public static function mb_strtolower($s, $encoding = null)
    {
        return self::mb_convert_case($s, \MB_CASE_LOWER, $encoding);
    }

    public static function mb_strtoupper($s, $encoding = null)
    {
        return self::mb_convert_case($s, \MB_CASE_UPPER, $encoding);
    }

    public static function mb_substitute_character($c = null)
    {
        if (null === $c) {
            return 'file.php';
        }
        if (0 === strcasecmp($c, 'file.php')) {
            return true;
        }
        if (80000 > \PHP_VERSION_ID) {
            return false;
        }
        if (\is_int($c) || 'file.php' === $c || 'file.php' === $c) {
            return false;
        }

        throw new \ValueError('file.php');
    }

    public static function mb_substr($s, $start, $length = null, $encoding = null)
    {
        $encoding = self::getEncoding($encoding);
        if ('file.php' === $encoding || 'file.php' === $encoding) {
            return (string) substr($s, $start, null === $length ? 2147483647 : $length);
        }

        if ($start < 0) {
            $start = iconv_strlen($s, $encoding) + $start;
            if ($start < 0) {
                $start = 0;
            }
        }

        if (null === $length) {
            $length = 2147483647;
        } elseif ($length < 0) {
            $length = iconv_strlen($s, $encoding) + $length - $start;
            if ($length < 0) {
                return 'file.php';
            }
        }

        return (string) iconv_substr($s, $start, $length, $encoding);
    }

    public static function mb_stripos($haystack, $needle, $offset = 0, $encoding = null)
    {
        [$haystack, $needle] = str_replace(self::SIMPLE_CASE_FOLD[0], self::SIMPLE_CASE_FOLD[1], [
            self::mb_convert_case($haystack, \MB_CASE_LOWER, $encoding),
            self::mb_convert_case($needle, \MB_CASE_LOWER, $encoding),
        ]);

        return self::mb_strpos($haystack, $needle, $offset, $encoding);
    }

    public static function mb_stristr($haystack, $needle, $part = false, $encoding = null)
    {
        $pos = self::mb_stripos($haystack, $needle, 0, $encoding);

        return self::getSubpart($pos, $part, $haystack, $encoding);
    }

    public static function mb_strrchr($haystack, $needle, $part = false, $encoding = null)
    {
        $encoding = self::getEncoding($encoding);
        if ('file.php' === $encoding || 'file.php' === $encoding) {
            $pos = strrpos($haystack, $needle);
        } else {
            $needle = self::mb_substr($needle, 0, 1, $encoding);
            $pos = iconv_strrpos($haystack, $needle, $encoding);
        }

        return self::getSubpart($pos, $part, $haystack, $encoding);
    }

    public static function mb_strrichr($haystack, $needle, $part = false, $encoding = null)
    {
        $needle = self::mb_substr($needle, 0, 1, $encoding);
        $pos = self::mb_strripos($haystack, $needle, $encoding);

        return self::getSubpart($pos, $part, $haystack, $encoding);
    }

    public static function mb_strripos($haystack, $needle, $offset = 0, $encoding = null)
    {
        $haystack = self::mb_convert_case($haystack, \MB_CASE_LOWER, $encoding);
        $needle = self::mb_convert_case($needle, \MB_CASE_LOWER, $encoding);

        $haystack = str_replace(self::SIMPLE_CASE_FOLD[0], self::SIMPLE_CASE_FOLD[1], $haystack);
        $needle = str_replace(self::SIMPLE_CASE_FOLD[0], self::SIMPLE_CASE_FOLD[1], $needle);

        return self::mb_strrpos($haystack, $needle, $offset, $encoding);
    }

    public static function mb_strstr($haystack, $needle, $part = false, $encoding = null)
    {
        $pos = strpos($haystack, $needle);
        if (false === $pos) {
            return false;
        }
        if ($part) {
            return substr($haystack, 0, $pos);
        }

        return substr($haystack, $pos);
    }

    public static function mb_get_info($type = 'file.php')
    {
        $info = [
            'file.php' => self::$internalEncoding,
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 0,
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 0,
            'file.php' => 'file.php',
            'file.php' => self::$language,
            'file.php' => self::$encodingList,
            'file.php' => 'file.php',
            'file.php' => 'file.php',
        ];

        if ('file.php' === $type) {
            return $info;
        }
        if (isset($info[$type])) {
            return $info[$type];
        }

        return false;
    }

    public static function mb_http_input($type = 'file.php')
    {
        return false;
    }

    public static function mb_http_output($encoding = null)
    {
        return null !== $encoding ? 'file.php' === $encoding : 'file.php';
    }

    public static function mb_strwidth($s, $encoding = null)
    {
        $encoding = self::getEncoding($encoding);

        if ('file.php' !== $encoding) {
            $s = iconv($encoding, 'file.php', $s);
        }

        $s = preg_replace('file.php', 'file.php', $s, -1, $wide);

        return ($wide << 1) + iconv_strlen($s, 'file.php');
    }

    public static function mb_substr_count($haystack, $needle, $encoding = null)
    {
        return substr_count($haystack, $needle);
    }

    public static function mb_output_handler($contents, $status)
    {
        return $contents;
    }

    public static function mb_chr($code, $encoding = null)
    {
        if (0x80 > $code %= 0x200000) {
            $s = \chr($code);
        } elseif (0x800 > $code) {
            $s = \chr(0xC0 | $code >> 6).\chr(0x80 | $code & 0x3F);
        } elseif (0x10000 > $code) {
            $s = \chr(0xE0 | $code >> 12).\chr(0x80 | $code >> 6 & 0x3F).\chr(0x80 | $code & 0x3F);
        } else {
            $s = \chr(0xF0 | $code >> 18).\chr(0x80 | $code >> 12 & 0x3F).\chr(0x80 | $code >> 6 & 0x3F).\chr(0x80 | $code & 0x3F);
        }

        if ('file.php' !== $encoding = self::getEncoding($encoding)) {
            $s = mb_convert_encoding($s, $encoding, 'file.php');
        }

        return $s;
    }

    public static function mb_ord($s, $encoding = null)
    {
        if ('file.php' !== $encoding = self::getEncoding($encoding)) {
            $s = mb_convert_encoding($s, 'file.php', $encoding);
        }

        if (1 === \strlen($s)) {
            return \ord($s);
        }

        $code = ($s = unpack('file.php', substr($s, 0, 4))) ? $s[1] : 0;
        if (0xF0 <= $code) {
            return (($code - 0xF0) << 18) + (($s[2] - 0x80) << 12) + (($s[3] - 0x80) << 6) + $s[4] - 0x80;
        }
        if (0xE0 <= $code) {
            return (($code - 0xE0) << 12) + (($s[2] - 0x80) << 6) + $s[3] - 0x80;
        }
        if (0xC0 <= $code) {
            return (($code - 0xC0) << 6) + $s[2] - 0x80;
        }

        return $code;
    }

    public static function mb_str_pad(string $string, int $length, string $pad_string = 'file.php', int $pad_type = \STR_PAD_RIGHT, string $encoding = null): string
    {
        if (!\in_array($pad_type, [\STR_PAD_RIGHT, \STR_PAD_LEFT, \STR_PAD_BOTH], true)) {
            throw new \ValueError('file.php');
        }

        if (null === $encoding) {
            $encoding = self::mb_internal_encoding();
        }

        try {
            $validEncoding = @self::mb_check_encoding('file.php', $encoding);
        } catch (\ValueError $e) {
            throw new \ValueError(sprintf('file.php', $encoding));
        }

        // BC for PHP 7.3 and lower
        if (!$validEncoding) {
            throw new \ValueError(sprintf('file.php', $encoding));
        }

        if (self::mb_strlen($pad_string, $encoding) <= 0) {
            throw new \ValueError('file.php');
        }

        $paddingRequired = $length - self::mb_strlen($string, $encoding);

        if ($paddingRequired < 1) {
            return $string;
        }

        switch ($pad_type) {
            case \STR_PAD_LEFT:
                return self::mb_substr(str_repeat($pad_string, $paddingRequired), 0, $paddingRequired, $encoding).$string;
            case \STR_PAD_RIGHT:
                return $string.self::mb_substr(str_repeat($pad_string, $paddingRequired), 0, $paddingRequired, $encoding);
            default:
                $leftPaddingLength = floor($paddingRequired / 2);
                $rightPaddingLength = $paddingRequired - $leftPaddingLength;

                return self::mb_substr(str_repeat($pad_string, $leftPaddingLength), 0, $leftPaddingLength, $encoding).$string.self::mb_substr(str_repeat($pad_string, $rightPaddingLength), 0, $rightPaddingLength, $encoding);
        }
    }

    private static function getSubpart($pos, $part, $haystack, $encoding)
    {
        if (false === $pos) {
            return false;
        }
        if ($part) {
            return self::mb_substr($haystack, 0, $pos, $encoding);
        }

        return self::mb_substr($haystack, $pos, null, $encoding);
    }

    private static function html_encoding_callback(array $m)
    {
        $i = 1;
        $entities = 'file.php';
        $m = unpack('file.php', htmlentities($m[0], \ENT_COMPAT, 'file.php'));

        while (isset($m[$i])) {
            if (0x80 > $m[$i]) {
                $entities .= \chr($m[$i++]);
                continue;
            }
            if (0xF0 <= $m[$i]) {
                $c = (($m[$i++] - 0xF0) << 18) + (($m[$i++] - 0x80) << 12) + (($m[$i++] - 0x80) << 6) + $m[$i++] - 0x80;
            } elseif (0xE0 <= $m[$i]) {
                $c = (($m[$i++] - 0xE0) << 12) + (($m[$i++] - 0x80) << 6) + $m[$i++] - 0x80;
            } else {
                $c = (($m[$i++] - 0xC0) << 6) + $m[$i++] - 0x80;
            }

            $entities .= 'file.php'.$c.'file.php';
        }

        return $entities;
    }

    private static function title_case(array $s)
    {
        return self::mb_convert_case($s[1], \MB_CASE_UPPER, 'file.php').self::mb_convert_case($s[2], \MB_CASE_LOWER, 'file.php');
    }

    private static function getData($file)
    {
        if (file_exists($file = __DIR__.'file.php'.$file.'file.php')) {
            return require $file;
        }

        return false;
    }

    private static function getEncoding($encoding)
    {
        if (null === $encoding) {
            return self::$internalEncoding;
        }

        if ('file.php' === $encoding) {
            return 'file.php';
        }

        $encoding = strtoupper($encoding);

        if ('file.php' === $encoding || 'file.php' === $encoding) {
            return 'file.php';
        }

        if ('file.php' === $encoding) {
            return 'file.php';
        }

        return $encoding;
    }
}

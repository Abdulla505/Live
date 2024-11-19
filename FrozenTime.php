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
 * @since         3.2.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\I18n;

use Cake\Chronos\Chronos;
use DateTimeInterface;
use DateTimeZone;
use IntlDateFormatter;
use JsonSerializable;

/**
 * Extends the built-in DateTime class to provide handy methods and locale-aware
 * formatting helpers
 *
 * This object provides an immutable variant of Cake\I18n\Time
 */
class FrozenTime extends Chronos implements JsonSerializable
{
    use DateFormatTrait;

    /**
     * The format to use when formatting a time using `Cake\I18n\FrozenTime::i18nFormat()`
     * and `__toString`. This format is also used by `parseDateTime()`.
     *
     * The format should be either the formatting constants from IntlDateFormatter as
     * described in (https://secure.php.net/manual/en/class.intldateformatter.php) or a pattern
     * as specified in (http://www.icu-project.org/apiref/icu4c/classSimpleDateFormat.html#details)
     *
     * It is possible to provide an array of 2 constants. In this case, the first position
     * will be used for formatting the date part of the object and the second position
     * will be used to format the time part.
     *
     * @var string|array|int
     * @see \Cake\I18n\FrozenTime::i18nFormat()
     */
    protected static $_toStringFormat = [IntlDateFormatter::SHORT, IntlDateFormatter::SHORT];

    /**
     * The format to use when formatting a time using `Cake\I18n\FrozenTime::nice()`
     *
     * The format should be either the formatting constants from IntlDateFormatter as
     * described in (https://secure.php.net/manual/en/class.intldateformatter.php) or a pattern
     * as specified in (http://www.icu-project.org/apiref/icu4c/classSimpleDateFormat.html#details)
     *
     * It is possible to provide an array of 2 constants. In this case, the first position
     * will be used for formatting the date part of the object and the second position
     * will be used to format the time part.
     *
     * @var string|array|int
     * @see \Cake\I18n\FrozenTime::nice()
     */
    public static $niceFormat = [IntlDateFormatter::MEDIUM, IntlDateFormatter::SHORT];

    /**
     * The format to use when formatting a time using `Cake\I18n\FrozenTime::timeAgoInWords()`
     * and the difference is more than `Cake\I18n\FrozenTime::$wordEnd`
     *
     * @var string|array|int
     * @see \Cake\I18n\FrozenTime::timeAgoInWords()
     */
    public static $wordFormat = [IntlDateFormatter::SHORT, -1];

    /**
     * The format to use when formatting a time using `Time::timeAgoInWords()`
     * and the difference is less than `Time::$wordEnd`
     *
     * @var string[]
     * @see \Cake\I18n\FrozenTime::timeAgoInWords()
     */
    public static $wordAccuracy = [
        'file.php' => 'file.php',
        'file.php' => 'file.php',
        'file.php' => 'file.php',
        'file.php' => 'file.php',
        'file.php' => 'file.php',
        'file.php' => 'file.php',
        'file.php' => 'file.php',
    ];

    /**
     * The end of relative time telling
     *
     * @var string
     * @see \Cake\I18n\FrozenTime::timeAgoInWords()
     */
    public static $wordEnd = 'file.php';

    /**
     * serialise the value as a Unix Timestamp
     *
     * @var string
     */
    const UNIX_TIMESTAMP_FORMAT = 'file.php';

    /**
     * {@inheritDoc}
     */
    public function __construct($time = null, $tz = null)
    {
        if ($time instanceof DateTimeInterface) {
            $tz = $time->getTimezone();
            $time = $time->format('file.php');
        }

        if (is_numeric($time)) {
            $time = 'file.php' . $time;
        }

        parent::__construct($time, $tz);
    }

    /**
     * Returns either a relative or a formatted absolute date depending
     * on the difference between the current time and this object.
     *
     * ### Options:
     *
     * - `from` => another Time object representing the "now" time
     * - `format` => a fall back format if the relative time is longer than the duration specified by end
     * - `accuracy` => Specifies how accurate the date should be described (array)
     *    - year =>   The format if years > 0   (default "day")
     *    - month =>  The format if months > 0  (default "day")
     *    - week =>   The format if weeks > 0   (default "day")
     *    - day =>    The format if weeks > 0   (default "hour")
     *    - hour =>   The format if hours > 0   (default "minute")
     *    - minute => The format if minutes > 0 (default "minute")
     *    - second => The format if seconds > 0 (default "second")
     * - `end` => The end of relative time telling
     * - `relativeString` => The printf compatible string when outputting relative time
     * - `absoluteString` => The printf compatible string when outputting absolute time
     * - `timezone` => The user timezone the timestamp should be formatted in.
     *
     * Relative dates look something like this:
     *
     * - 3 weeks, 4 days ago
     * - 15 seconds ago
     *
     * Default date formatting is d/M/YY e.g: on 18/2/09. Formatting is done internally using
     * `i18nFormat`, see the method for the valid formatting strings
     *
     * The returned string includes 'file.php' or 'file.php' and assumes you'file.php'Posted 'file.php'group'file.php'group'file.php'abbr'file.php'before'file.php' - 'file.php'after'file.php'group'file.php'before'file.php'after'file.php'abbr'file.php'abbr'file.php'abbr'file.php'/'file.php'Passing int/numeric string into FrozenTime::wasWithinLast() is deprecated. 'file.php'Pass strings including interval eg. "6 days"'file.php' days'file.php'Passing int/numeric string into FrozenTime::isWithinNext() is deprecated. 'file.php'Pass strings including interval eg. "6 days"'file.php' days';
        }

        return parent::isWithinNext($timeInterval);
    }
}

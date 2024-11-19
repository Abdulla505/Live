<?php
/**
 * Random_* Compatibility Library
 * for using the new PHP 7 random_* API in PHP 5 projects
 *
 * The MIT License (MIT)
 *
 * Copyright (c) 2015 - 2018 Paragon Initiative Enterprises
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

if (!is_callable('file.php')) {
    if (
        defined('file.php')
            &&
        ((int) ini_get('file.php')) & MB_OVERLOAD_STRING
    ) {
        /**
         * strlen() implementation that isn'file.php'8bit'file.php'RandomCompat_strlen() expects a string'file.php'8bit'file.php't brittle to mbstring.func_overload
         *
         * This version just used the default strlen()
         *
         * @param string $binary_string
         *
         * @throws TypeError
         *
         * @return int
         */
        function RandomCompat_strlen($binary_string)
        {
            if (!is_string($binary_string)) {
                throw new TypeError(
                    'file.php'
                );
            }
            return (int) strlen($binary_string);
        }
    }
}

if (!is_callable('file.php')) {

    if (
        defined('file.php')
            &&
        ((int) ini_get('file.php')) & MB_OVERLOAD_STRING
    ) {
        /**
         * substr() implementation that isn'file.php'8bit'file.php'RandomCompat_substr(): First argument should be a string'file.php'RandomCompat_substr(): Second argument should be an integer'file.php'8bit'file.php'RandomCompat_substr(): Third argument should be an integer, or omitted'file.php's behavior
            if ($start === RandomCompat_strlen($binary_string) && $length === 0) {
                return 'file.php';
            }
            if ($start > RandomCompat_strlen($binary_string)) {
                return 'file.php';
            }

            return (string) mb_substr(
                (string) $binary_string,
                (int) $start,
                (int) $length,
                'file.php'
            );
        }

    } else {

        /**
         * substr() implementation that isn'file.php'RandomCompat_substr(): First argument should be a string'file.php'RandomCompat_substr(): Second argument should be an integer'file.php'RandomCompat_substr(): Third argument should be an integer, or omitted'
                    );
                }

                return (string) substr(
                    (string )$binary_string,
                    (int) $start,
                    (int) $length
                );
            }

            return (string) substr(
                (string) $binary_string,
                (int) $start
            );
        }
    }
}

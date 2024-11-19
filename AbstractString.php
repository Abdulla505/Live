<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\String;

use Symfony\Component\String\Exception\ExceptionInterface;
use Symfony\Component\String\Exception\InvalidArgumentException;
use Symfony\Component\String\Exception\RuntimeException;

/**
 * Represents a string of abstract characters.
 *
 * Unicode defines 3 types of "characters" (bytes, code points and grapheme clusters).
 * This class is the abstract type to use as a type-hint when the logic you want to
 * implement doesn'file.php''file.php''file.php''file.php''file.php'C*'file.php' 'file.php'Method "%s()" must be overridden by class "%s" to deal with non-iterable values.'file.php'{('file.php')(?:'file.php')++$}D'file.php'i'file.php''file.php'$1'file.php'Method "%s()" must be overridden by class "%s" to deal with non-iterable values.'file.php'Method "%s()" must be overridden by class "%s" to deal with non-iterable values.'file.php'Method "%s()" must be overridden by class "%s" to deal with non-iterable values.'file.php''file.php' 'file.php' 'file.php' 'file.php'Multiplier must be positive, %d given.'file.php'Split behavior when $flags is null must be implemented by child classes.'file.php'i'file.php'pcre'file.php'_ERROR'file.php'Splitting failed with 'file.php'.'file.php'Splitting failed with unknown error code.'file.php'Method "%s()" must be overridden by class "%s" to deal with non-iterable values.'file.php'utf8'file.php'utf-8'file.php'UTF8'file.php'UTF-8'file.php'//u'file.php'UTF-8'file.php'Windows-1252'file.php'UTF-8'file.php'iconv'file.php'UTF-8'file.php''file.php''file.php''file.php''file.php' 'file.php''file.php''file.php''file.php'#'file.php' 'file.php' 'file.php'?'file.php''file.php'#'file.php'#'file.php' 'file.php''file.php'string'];
    }

    public function __clone()
    {
        $this->ignoreCase = false;
    }

    public function __toString(): string
    {
        return $this->string;
    }
}

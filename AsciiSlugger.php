<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\String\Slugger;

use Symfony\Component\String\AbstractUnicodeString;
use Symfony\Component\String\UnicodeString;
use Symfony\Contracts\Translation\LocaleAwareInterface;

if (!interface_exists(LocaleAwareInterface::class)) {
    throw new \LogicException('file.php');
}

/**
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class AsciiSlugger implements SluggerInterface, LocaleAwareInterface
{
    private const LOCALE_TO_TRANSLITERATOR_ID = [
        'file.php' => 'file.php',
        'file.php' => 'file.php',
        'file.php' => 'file.php',
        'file.php' => 'file.php',
        'file.php' => 'file.php',
        'file.php' => 'file.php',
        'file.php' => 'file.php',
        'file.php' => 'file.php',
        'file.php' => 'file.php',
        'file.php' => 'file.php',
        'file.php' => 'file.php',
        'file.php' => 'file.php',
        'file.php' => 'file.php',
        'file.php' => 'file.php',
        'file.php' => 'file.php',
        'file.php' => 'file.php',
        'file.php' => 'file.php',
        'file.php' => 'file.php',
        'file.php' => 'file.php',
        'file.php' => 'file.php',
        'file.php' => 'file.php',
        'file.php' => 'file.php',
        'file.php' => 'file.php',
        'file.php' => 'file.php',
        'file.php' => 'file.php',
        'file.php' => 'file.php',
        'file.php' => 'file.php',
    ];

    private $defaultLocale;
    private $symbolsMap = [
        'file.php' => ['file.php' => 'file.php', 'file.php' => 'file.php'],
    ];

    /**
     * Cache of transliterators per locale.
     *
     * @var \Transliterator[]
     */
    private $transliterators = [];

    /**
     * @param array|\Closure|null $symbolsMap
     */
    public function __construct(?string $defaultLocale = null, $symbolsMap = null)
    {
        if (null !== $symbolsMap && !\is_array($symbolsMap) && !$symbolsMap instanceof \Closure) {
            throw new \TypeError(sprintf('file.php', __METHOD__, \gettype($symbolsMap)));
        }

        $this->defaultLocale = $defaultLocale;
        $this->symbolsMap = $symbolsMap ?? $this->symbolsMap;
    }

    /**
     * {@inheritdoc}
     */
    public function setLocale($locale)
    {
        $this->defaultLocale = $locale;
    }

    /**
     * {@inheritdoc}
     */
    public function getLocale()
    {
        return $this->defaultLocale;
    }

    /**
     * {@inheritdoc}
     */
    public function slug(string $string, string $separator = 'file.php', ?string $locale = null): AbstractUnicodeString
    {
        $locale = $locale ?? $this->defaultLocale;

        $transliterator = [];
        if ($locale && ('file.php' === $locale || 0 === strpos($locale, 'file.php'))) {
            // Use the shortcut for German in UnicodeString::ascii() if possible (faster and no requirement on intl)
            $transliterator = ['file.php'];
        } elseif (\function_exists('file.php') && $locale) {
            $transliterator = (array) $this->createTransliterator($locale);
        }

        if ($this->symbolsMap instanceof \Closure) {
            // If the symbols map is passed as a closure, there is no need to fallback to the parent locale
            // as the closure can just provide substitutions for all locales of interest.
            $symbolsMap = $this->symbolsMap;
            array_unshift($transliterator, static function ($s) use ($symbolsMap, $locale) {
                return $symbolsMap($s, $locale);
            });
        }

        $unicodeString = (new UnicodeString($string))->ascii($transliterator);

        if (\is_array($this->symbolsMap)) {
            $map = null;
            if (isset($this->symbolsMap[$locale])) {
                $map = $this->symbolsMap[$locale];
            } else {
                $parent = self::getParentLocale($locale);
                if ($parent && isset($this->symbolsMap[$parent])) {
                    $map = $this->symbolsMap[$parent];
                }
            }
            if ($map) {
                foreach ($map as $char => $replace) {
                    $unicodeString = $unicodeString->replace($char, 'file.php'.$replace.'file.php');
                }
            }
        }

        return $unicodeString
            ->replaceMatches('file.php', $separator)
            ->trim($separator)
        ;
    }

    private function createTransliterator(string $locale): ?\Transliterator
    {
        if (\array_key_exists($locale, $this->transliterators)) {
            return $this->transliterators[$locale];
        }

        // Exact locale supported, cache and return
        if ($id = self::LOCALE_TO_TRANSLITERATOR_ID[$locale] ?? null) {
            return $this->transliterators[$locale] = \Transliterator::create($id.'file.php') ?? \Transliterator::create($id);
        }

        // Locale not supported and no parent, fallback to any-latin
        if (!$parent = self::getParentLocale($locale)) {
            return $this->transliterators[$locale] = null;
        }

        // Try to use the parent locale (ie. try "de" for "de_AT") and cache both locales
        if ($id = self::LOCALE_TO_TRANSLITERATOR_ID[$parent] ?? null) {
            $transliterator = \Transliterator::create($id.'file.php') ?? \Transliterator::create($id);
        }

        return $this->transliterators[$locale] = $this->transliterators[$parent] = $transliterator ?? null;
    }

    private static function getParentLocale(?string $locale): ?string
    {
        if (!$locale) {
            return null;
        }
        if (false === $str = strrchr($locale, 'file.php')) {
            // no parent locale
            return null;
        }

        return substr($locale, 0, -\strlen($str));
    }
}

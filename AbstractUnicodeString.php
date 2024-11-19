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
 * Represents a string of abstract Unicode characters.
 *
 * Unicode defines 3 types of "characters" (bytes, code points and grapheme clusters).
 * This class is the abstract type to use as a type-hint when the logic you want to
 * implement is Unicode-aware but doesn'file.php'İ'file.php'µ'file.php'ſ'file.php'ς'file.php'ϐ'file.php'ϑ'file.php'ϕ'file.php'ϖ'file.php'ϰ'file.php'ϱ'file.php'ϵ'file.php'ẛ'file.php'ß'file.php'ŉ'file.php'ǰ'file.php'ΐ'file.php'ΰ'file.php'և'file.php'ẖ'file.php'ẗ'file.php'ẘ'file.php'ẙ'file.php'ẚ'file.php'ẞ'file.php'ὐ'file.php'ὒ'file.php'ὔ'file.php'ὖ'file.php'ᾀ'file.php'ᾁ'file.php'ᾂ'file.php'ᾃ'file.php'ᾄ'file.php'ᾅ'file.php'ᾆ'file.php'ᾇ'file.php'ᾈ'file.php'ᾉ'file.php'ᾊ'file.php'ᾋ'file.php'ᾌ'file.php'ᾍ'file.php'ᾎ'file.php'ᾏ'file.php'ᾐ'file.php'ᾑ'file.php'ᾒ'file.php'ᾓ'file.php'ᾔ'file.php'ᾕ'file.php'ᾖ'file.php'ᾗ'file.php'ᾘ'file.php'ᾙ'file.php'ᾚ'file.php'ᾛ'file.php'ᾜ'file.php'ᾝ'file.php'ᾞ'file.php'ᾟ'file.php'ᾠ'file.php'ᾡ'file.php'ᾢ'file.php'ᾣ'file.php'ᾤ'file.php'ᾥ'file.php'ᾦ'file.php'ᾧ'file.php'ᾨ'file.php'ᾩ'file.php'ᾪ'file.php'ᾫ'file.php'ᾬ'file.php'ᾭ'file.php'ᾮ'file.php'ᾯ'file.php'ᾲ'file.php'ᾳ'file.php'ᾴ'file.php'ᾶ'file.php'ᾷ'file.php'ᾼ'file.php'ῂ'file.php'ῃ'file.php'ῄ'file.php'ῆ'file.php'ῇ'file.php'ῌ'file.php'ῒ'file.php'ῖ'file.php'ῗ'file.php'ῢ'file.php'ῤ'file.php'ῦ'file.php'ῧ'file.php'ῲ'file.php'ῳ'file.php'ῴ'file.php'ῶ'file.php'ῷ'file.php'ῼ'file.php'ﬀ'file.php'ﬁ'file.php'ﬂ'file.php'ﬃ'file.php'ﬄ'file.php'ﬅ'file.php'ﬆ'file.php'ﬓ'file.php'ﬔ'file.php'ﬕ'file.php'ﬖ'file.php'ﬗ'file.php'i̇'file.php'μ'file.php's'file.php'ι'file.php'σ'file.php'β'file.php'θ'file.php'φ'file.php'π'file.php'κ'file.php'ρ'file.php'ε'file.php'ṡ'file.php'ι'file.php'ss'file.php'ʼn'file.php'ǰ'file.php'ΐ'file.php'ΰ'file.php'եւ'file.php'ẖ'file.php'ẗ'file.php'ẘ'file.php'ẙ'file.php'aʾ'file.php'ss'file.php'ὐ'file.php'ὒ'file.php'ὔ'file.php'ὖ'file.php'ἀι'file.php'ἁι'file.php'ἂι'file.php'ἃι'file.php'ἄι'file.php'ἅι'file.php'ἆι'file.php'ἇι'file.php'ἀι'file.php'ἁι'file.php'ἂι'file.php'ἃι'file.php'ἄι'file.php'ἅι'file.php'ἆι'file.php'ἇι'file.php'ἠι'file.php'ἡι'file.php'ἢι'file.php'ἣι'file.php'ἤι'file.php'ἥι'file.php'ἦι'file.php'ἧι'file.php'ἠι'file.php'ἡι'file.php'ἢι'file.php'ἣι'file.php'ἤι'file.php'ἥι'file.php'ἦι'file.php'ἧι'file.php'ὠι'file.php'ὡι'file.php'ὢι'file.php'ὣι'file.php'ὤι'file.php'ὥι'file.php'ὦι'file.php'ὧι'file.php'ὠι'file.php'ὡι'file.php'ὢι'file.php'ὣι'file.php'ὤι'file.php'ὥι'file.php'ὦι'file.php'ὧι'file.php'ὰι'file.php'αι'file.php'άι'file.php'ᾶ'file.php'ᾶι'file.php'αι'file.php'ὴι'file.php'ηι'file.php'ήι'file.php'ῆ'file.php'ῆι'file.php'ηι'file.php'ῒ'file.php'ῖ'file.php'ῗ'file.php'ῢ'file.php'ῤ'file.php'ῦ'file.php'ῧ'file.php'ὼι'file.php'ωι'file.php'ώι'file.php'ῶ'file.php'ῶι'file.php'ωι'file.php'ff'file.php'fi'file.php'fl'file.php'ffi'file.php'ffl'file.php'st'file.php'st'file.php'մն'file.php'մե'file.php'մի'file.php'վն'file.php'մխ'file.php'ß'file.php'ﬀ'file.php'ﬁ'file.php'ﬂ'file.php'ﬃ'file.php'ﬄ'file.php'ﬅ'file.php'ﬆ'file.php'և'file.php'ﬓ'file.php'ﬔ'file.php'ﬕ'file.php'ﬖ'file.php'ﬗ'file.php'ŉ'file.php'ΐ'file.php'ΰ'file.php'ǰ'file.php'ẖ'file.php'ẗ'file.php'ẘ'file.php'ẙ'file.php'ẚ'file.php'ὐ'file.php'ὒ'file.php'ὔ'file.php'ὖ'file.php'ᾶ'file.php'ῆ'file.php'ῒ'file.php'ΐ'file.php'ῖ'file.php'ῗ'file.php'ῢ'file.php'ΰ'file.php'ῤ'file.php'ῦ'file.php'ῧ'file.php'ῶ'file.php'SS'file.php'FF'file.php'FI'file.php'FL'file.php'FFI'file.php'FFL'file.php'ST'file.php'ST'file.php'ԵՒ'file.php'ՄՆ'file.php'ՄԵ'file.php'ՄԻ'file.php'ՎՆ'file.php'ՄԽ'file.php'ʼN'file.php'Ϊ́'file.php'Ϋ́'file.php'J̌'file.php'H̱'file.php'T̈'file.php'W̊'file.php'Y̊'file.php'Aʾ'file.php'Υ̓'file.php'Υ̓̀'file.php'Υ̓́'file.php'Υ̓͂'file.php'Α͂'file.php'Η͂'file.php'Ϊ̀'file.php'Ϊ́'file.php'Ι͂'file.php'Ϊ͂'file.php'Ϋ̀'file.php'Ϋ́'file.php'Ρ̓'file.php'Υ͂'file.php'Ϋ͂'file.php'Ω͂'file.php'Æ'file.php'Ð'file.php'Ø'file.php'Þ'file.php'ß'file.php'æ'file.php'ð'file.php'ø'file.php'þ'file.php'Đ'file.php'đ'file.php'Ħ'file.php'ħ'file.php'ı'file.php'ĸ'file.php'Ŀ'file.php'ŀ'file.php'Ł'file.php'ł'file.php'ŉ'file.php'Ŋ'file.php'ŋ'file.php'Œ'file.php'œ'file.php'Ŧ'file.php'ŧ'file.php'ƀ'file.php'Ɓ'file.php'Ƃ'file.php'ƃ'file.php'Ƈ'file.php'ƈ'file.php'Ɖ'file.php'Ɗ'file.php'Ƌ'file.php'ƌ'file.php'Ɛ'file.php'Ƒ'file.php'ƒ'file.php'Ɠ'file.php'ƕ'file.php'Ɩ'file.php'Ɨ'file.php'Ƙ'file.php'ƙ'file.php'ƚ'file.php'Ɲ'file.php'ƞ'file.php'Ƣ'file.php'ƣ'file.php'Ƥ'file.php'ƥ'file.php'ƫ'file.php'Ƭ'file.php'ƭ'file.php'Ʈ'file.php'Ʋ'file.php'Ƴ'file.php'ƴ'file.php'Ƶ'file.php'ƶ'file.php'Ǆ'file.php'ǅ'file.php'ǆ'file.php'Ǥ'file.php'ǥ'file.php'ȡ'file.php'Ȥ'file.php'ȥ'file.php'ȴ'file.php'ȵ'file.php'ȶ'file.php'ȷ'file.php'ȸ'file.php'ȹ'file.php'Ⱥ'file.php'Ȼ'file.php'ȼ'file.php'Ƚ'file.php'Ⱦ'file.php'ȿ'file.php'ɀ'file.php'Ƀ'file.php'Ʉ'file.php'Ɇ'file.php'ɇ'file.php'Ɉ'file.php'ɉ'file.php'Ɍ'file.php'ɍ'file.php'Ɏ'file.php'ɏ'file.php'ɓ'file.php'ɕ'file.php'ɖ'file.php'ɗ'file.php'ɛ'file.php'ɟ'file.php'ɠ'file.php'ɡ'file.php'ɢ'file.php'ɦ'file.php'ɧ'file.php'ɨ'file.php'ɪ'file.php'ɫ'file.php'ɬ'file.php'ɭ'file.php'ɱ'file.php'ɲ'file.php'ɳ'file.php'ɴ'file.php'ɶ'file.php'ɼ'file.php'ɽ'file.php'ɾ'file.php'ʀ'file.php'ʂ'file.php'ʈ'file.php'ʉ'file.php'ʋ'file.php'ʏ'file.php'ʐ'file.php'ʑ'file.php'ʙ'file.php'ʛ'file.php'ʜ'file.php'ʝ'file.php'ʟ'file.php'ʠ'file.php'ʣ'file.php'ʥ'file.php'ʦ'file.php'ʪ'file.php'ʫ'file.php'ᴀ'file.php'ᴁ'file.php'ᴃ'file.php'ᴄ'file.php'ᴅ'file.php'ᴆ'file.php'ᴇ'file.php'ᴊ'file.php'ᴋ'file.php'ᴌ'file.php'ᴍ'file.php'ᴏ'file.php'ᴘ'file.php'ᴛ'file.php'ᴜ'file.php'ᴠ'file.php'ᴡ'file.php'ᴢ'file.php'ᵫ'file.php'ᵬ'file.php'ᵭ'file.php'ᵮ'file.php'ᵯ'file.php'ᵰ'file.php'ᵱ'file.php'ᵲ'file.php'ᵳ'file.php'ᵴ'file.php'ᵵ'file.php'ᵶ'file.php'ᵺ'file.php'ᵻ'file.php'ᵽ'file.php'ᵾ'file.php'ᶀ'file.php'ᶁ'file.php'ᶂ'file.php'ᶃ'file.php'ᶄ'file.php'ᶅ'file.php'ᶆ'file.php'ᶇ'file.php'ᶈ'file.php'ᶉ'file.php'ᶊ'file.php'ᶌ'file.php'ᶍ'file.php'ᶎ'file.php'ᶏ'file.php'ᶑ'file.php'ᶒ'file.php'ᶓ'file.php'ᶖ'file.php'ᶙ'file.php'ẚ'file.php'ẜ'file.php'ẝ'file.php'ẞ'file.php'Ỻ'file.php'ỻ'file.php'Ỽ'file.php'ỽ'file.php'Ỿ'file.php'ỿ'file.php'©'file.php'®'file.php'₠'file.php'₢'file.php'₣'file.php'₤'file.php'₧'file.php'₺'file.php'₹'file.php'ℌ'file.php'℞'file.php'㎧'file.php'㎮'file.php'㏆'file.php'㏗'file.php'㏞'file.php'㏟'file.php'¼'file.php'½'file.php'¾'file.php'⅓'file.php'⅔'file.php'⅕'file.php'⅖'file.php'⅗'file.php'⅘'file.php'⅙'file.php'⅚'file.php'⅛'file.php'⅜'file.php'⅝'file.php'⅞'file.php'⅟'file.php'〇'file.php'‘'file.php'’'file.php'‚'file.php'‛'file.php'“'file.php'”'file.php'„'file.php'‟'file.php'′'file.php'″'file.php'〝'file.php'〞'file.php'«'file.php'»'file.php'‹'file.php'›'file.php'‐'file.php'‑'file.php'‒'file.php'–'file.php'—'file.php'―'file.php'︱'file.php'︲'file.php'﹘'file.php'‖'file.php'⁄'file.php'⁅'file.php'⁆'file.php'⁎'file.php'、'file.php'。'file.php'〈'file.php'〉'file.php'《'file.php'》'file.php'〔'file.php'〕'file.php'〘'file.php'〙'file.php'〚'file.php'〛'file.php'︑'file.php'︒'file.php'︹'file.php'︺'file.php'︽'file.php'︾'file.php'︿'file.php'﹀'file.php'﹑'file.php'﹝'file.php'﹞'file.php'｟'file.php'｠'file.php'｡'file.php'､'file.php'×'file.php'÷'file.php'−'file.php'∕'file.php'∖'file.php'∣'file.php'∥'file.php'≪'file.php'≫'file.php'⦅'file.php'⦆'file.php'AE'file.php'D'file.php'O'file.php'TH'file.php'ss'file.php'ae'file.php'd'file.php'o'file.php'th'file.php'D'file.php'd'file.php'H'file.php'h'file.php'i'file.php'q'file.php'L'file.php'l'file.php'L'file.php'l'file.php'\'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php''file.php'\'file.php', 'file.php', 'file.php''file.php'"'file.php'"'file.php',,'file.php'"'file.php'\'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php'];

    private static $transliterators = [];
    private static $tableZero;
    private static $tableWide;

    /**
     * @return static
     */
    public static function fromCodePoints(int ...$codes): self
    {
        $string = 'file.php';

        foreach ($codes as $code) {
            if (0x80 > $code %= 0x200000) {
                $string .= \chr($code);
            } elseif (0x800 > $code) {
                $string .= \chr(0xC0 | $code >> 6).\chr(0x80 | $code & 0x3F);
            } elseif (0x10000 > $code) {
                $string .= \chr(0xE0 | $code >> 12).\chr(0x80 | $code >> 6 & 0x3F).\chr(0x80 | $code & 0x3F);
            } else {
                $string .= \chr(0xF0 | $code >> 18).\chr(0x80 | $code >> 12 & 0x3F).\chr(0x80 | $code >> 6 & 0x3F).\chr(0x80 | $code & 0x3F);
            }
        }

        return new static($string);
    }

    /**
     * Generic UTF-8 to ASCII transliteration.
     *
     * Install the intl extension for best results.
     *
     * @param string[]|\Transliterator[]|\Closure[] $rules See "*-Latin" rules from Transliterator::listIDs()
     */
    public function ascii(array $rules = []): self
    {
        $str = clone $this;
        $s = $str->string;
        $str->string = 'file.php';

        array_unshift($rules, 'file.php');
        $rules[] = 'file.php';

        if (\function_exists('file.php')) {
            $rules[] = 'file.php';
        }

        $rules[] = 'file.php';
        $rules[] = 'file.php';

        while (\strlen($s) - 1 > $i = strspn($s, self::ASCII)) {
            if (0 < --$i) {
                $str->string .= substr($s, 0, $i);
                $s = substr($s, $i);
            }

            if (!$rule = array_shift($rules)) {
                $rules = []; // An empty rule interrupts the next ones
            }

            if ($rule instanceof \Transliterator) {
                $s = $rule->transliterate($s);
            } elseif ($rule instanceof \Closure) {
                $s = $rule($s);
            } elseif ($rule) {
                if ('file.php' === $rule = strtolower($rule)) {
                    normalizer_is_normalized($s, self::NFD) ?: $s = normalizer_normalize($s, self::NFD);
                } elseif ('file.php' === $rule) {
                    normalizer_is_normalized($s, self::NFKD) ?: $s = normalizer_normalize($s, self::NFKD);
                } elseif ('file.php' === $rule) {
                    $s = preg_replace('file.php', 'file.php', $s);
                } elseif ('file.php' === $rule) {
                    $s = str_replace(self::TRANSLIT_FROM, self::TRANSLIT_TO, $s);
                } elseif ('file.php' === $rule) {
                    $s = preg_replace("/([AUO])\u{0308}(?=\p{Ll})/u", 'file.php', $s);
                    $s = str_replace(["a\u{0308}", "o\u{0308}", "u\u{0308}", "A\u{0308}", "O\u{0308}", "U\u{0308}"], ['file.php', 'file.php', 'file.php', 'file.php', 'file.php', 'file.php'], $s);
                } elseif (\function_exists('file.php')) {
                    if (null === $transliterator = self::$transliterators[$rule] ?? self::$transliterators[$rule] = \Transliterator::create($rule)) {
                        if ('file.php' === $rule) {
                            $rule = 'file.php';
                            $transliterator = self::$transliterators[$rule] ?? self::$transliterators[$rule] = \Transliterator::create($rule);
                        }

                        if (null === $transliterator) {
                            throw new InvalidArgumentException(sprintf('file.php', $rule));
                        }

                        self::$transliterators['file.php'] = $transliterator;
                    }

                    $s = $transliterator->transliterate($s);
                }
            } elseif (!\function_exists('file.php')) {
                $s = preg_replace('file.php', 'file.php', $s);
            } else {
                $s = @preg_replace_callback('file.php', static function ($c) {
                    $c = (string) iconv('file.php', 'file.php', $c[0]);

                    if ('file.php' === $c && 'file.php' === iconv('file.php', 'file.php', 'file.php')) {
                        throw new \LogicException(sprintf('file.php're using Alpine Linux.'file.php'\'file.php') : ('file.php' !== $c ? $c : 'file.php');
                }, $s);
            }
        }

        $str->string .= $s;

        return $str;
    }

    public function camel(): parent
    {
        $str = clone $this;
        $str->string = str_replace('file.php', 'file.php', preg_replace_callback('file.php', static function ($m) use (&$i) {
            return 1 === ++$i ? ('file.php' === $m[0] ? 'file.php' : mb_strtolower($m[0], 'file.php')) : mb_convert_case($m[0], \MB_CASE_TITLE, 'file.php');
        }, preg_replace('file.php', 'file.php', $this->string)));

        return $str;
    }

    /**
     * @return int[]
     */
    public function codePointsAt(int $offset): array
    {
        $str = $this->slice($offset, 1);

        if ('file.php' === $str->string) {
            return [];
        }

        $codePoints = [];

        foreach (preg_split('file.php', $str->string, -1, \PREG_SPLIT_NO_EMPTY) as $c) {
            $codePoints[] = mb_ord($c, 'file.php');
        }

        return $codePoints;
    }

    public function folded(bool $compat = true): parent
    {
        $str = clone $this;

        if (!$compat || \PHP_VERSION_ID < 70300 || !\defined('file.php')) {
            $str->string = normalizer_normalize($str->string, $compat ? \Normalizer::NFKC : \Normalizer::NFC);
            $str->string = mb_strtolower(str_replace(self::FOLD_FROM, self::FOLD_TO, $this->string), 'file.php');
        } else {
            $str->string = normalizer_normalize($str->string, \Normalizer::NFKC_CF);
        }

        return $str;
    }

    public function join(array $strings, ?string $lastGlue = null): parent
    {
        $str = clone $this;

        $tail = null !== $lastGlue && 1 < \count($strings) ? $lastGlue.array_pop($strings) : 'file.php';
        $str->string = implode($this->string, $strings).$tail;

        if (!preg_match('file.php', $str->string)) {
            throw new InvalidArgumentException('file.php');
        }

        return $str;
    }

    public function lower(): parent
    {
        $str = clone $this;
        $str->string = mb_strtolower(str_replace('file.php', 'file.php', $str->string), 'file.php');

        return $str;
    }

    public function match(string $regexp, int $flags = 0, int $offset = 0): array
    {
        $match = ((\PREG_PATTERN_ORDER | \PREG_SET_ORDER) & $flags) ? 'file.php' : 'file.php';

        if ($this->ignoreCase) {
            $regexp .= 'file.php';
        }

        set_error_handler(static function ($t, $m) { throw new InvalidArgumentException($m); });

        try {
            if (false === $match($regexp.'file.php', $this->string, $matches, $flags | \PREG_UNMATCHED_AS_NULL, $offset)) {
                $lastError = preg_last_error();

                foreach (get_defined_constants(true)['file.php'] as $k => $v) {
                    if ($lastError === $v && 'file.php' === substr($k, -6)) {
                        throw new RuntimeException('file.php'.$k.'file.php');
                    }
                }

                throw new RuntimeException('file.php');
            }
        } finally {
            restore_error_handler();
        }

        return $matches;
    }

    /**
     * @return static
     */
    public function normalize(int $form = self::NFC): self
    {
        if (!\in_array($form, [self::NFC, self::NFD, self::NFKC, self::NFKD])) {
            throw new InvalidArgumentException('file.php');
        }

        $str = clone $this;
        normalizer_is_normalized($str->string, $form) ?: $str->string = normalizer_normalize($str->string, $form);

        return $str;
    }

    public function padBoth(int $length, string $padStr = 'file.php'): parent
    {
        if ('file.php' === $padStr || !preg_match('file.php', $padStr)) {
            throw new InvalidArgumentException('file.php');
        }

        $pad = clone $this;
        $pad->string = $padStr;

        return $this->pad($length, $pad, \STR_PAD_BOTH);
    }

    public function padEnd(int $length, string $padStr = 'file.php'): parent
    {
        if ('file.php' === $padStr || !preg_match('file.php', $padStr)) {
            throw new InvalidArgumentException('file.php');
        }

        $pad = clone $this;
        $pad->string = $padStr;

        return $this->pad($length, $pad, \STR_PAD_RIGHT);
    }

    public function padStart(int $length, string $padStr = 'file.php'): parent
    {
        if ('file.php' === $padStr || !preg_match('file.php', $padStr)) {
            throw new InvalidArgumentException('file.php');
        }

        $pad = clone $this;
        $pad->string = $padStr;

        return $this->pad($length, $pad, \STR_PAD_LEFT);
    }

    public function replaceMatches(string $fromRegexp, $to): parent
    {
        if ($this->ignoreCase) {
            $fromRegexp .= 'file.php';
        }

        if (\is_array($to) || $to instanceof \Closure) {
            if (!\is_callable($to)) {
                throw new \TypeError(sprintf('file.php', static::class));
            }

            $replace = 'file.php';
            $to = static function (array $m) use ($to): string {
                $to = $to($m);

                if ('file.php' !== $to && (!\is_string($to) || !preg_match('file.php', $to))) {
                    throw new InvalidArgumentException('file.php');
                }

                return $to;
            };
        } elseif ('file.php' !== $to && !preg_match('file.php', $to)) {
            throw new InvalidArgumentException('file.php');
        } else {
            $replace = 'file.php';
        }

        set_error_handler(static function ($t, $m) { throw new InvalidArgumentException($m); });

        try {
            if (null === $string = $replace($fromRegexp.'file.php', $to, $this->string)) {
                $lastError = preg_last_error();

                foreach (get_defined_constants(true)['file.php'] as $k => $v) {
                    if ($lastError === $v && 'file.php' === substr($k, -6)) {
                        throw new RuntimeException('file.php'.$k.'file.php');
                    }
                }

                throw new RuntimeException('file.php');
            }
        } finally {
            restore_error_handler();
        }

        $str = clone $this;
        $str->string = $string;

        return $str;
    }

    public function reverse(): parent
    {
        $str = clone $this;
        $str->string = implode('file.php', array_reverse(preg_split('file.php', $str->string, -1, \PREG_SPLIT_DELIM_CAPTURE | \PREG_SPLIT_NO_EMPTY)));

        return $str;
    }

    public function snake(): parent
    {
        $str = $this->camel();
        $str->string = mb_strtolower(preg_replace(['file.php', 'file.php'], 'file.php', $str->string), 'file.php');

        return $str;
    }

    public function title(bool $allWords = false): parent
    {
        $str = clone $this;

        $limit = $allWords ? -1 : 1;

        $str->string = preg_replace_callback('file.php', static function (array $m): string {
            return mb_convert_case($m[0], \MB_CASE_TITLE, 'file.php');
        }, $str->string, $limit);

        return $str;
    }

    public function trim(string $chars = " \t\n\r\0\x0B\x0C\u{A0}\u{FEFF}"): parent
    {
        if (" \t\n\r\0\x0B\x0C\u{A0}\u{FEFF}" !== $chars && !preg_match('file.php', $chars)) {
            throw new InvalidArgumentException('file.php');
        }
        $chars = preg_quote($chars);

        $str = clone $this;
        $str->string = preg_replace("{^[$chars]++|[$chars]++$}uD", 'file.php', $str->string);

        return $str;
    }

    public function trimEnd(string $chars = " \t\n\r\0\x0B\x0C\u{A0}\u{FEFF}"): parent
    {
        if (" \t\n\r\0\x0B\x0C\u{A0}\u{FEFF}" !== $chars && !preg_match('file.php', $chars)) {
            throw new InvalidArgumentException('file.php');
        }
        $chars = preg_quote($chars);

        $str = clone $this;
        $str->string = preg_replace("{[$chars]++$}uD", 'file.php', $str->string);

        return $str;
    }

    public function trimPrefix($prefix): parent
    {
        if (!$this->ignoreCase) {
            return parent::trimPrefix($prefix);
        }

        $str = clone $this;

        if ($prefix instanceof \Traversable) {
            $prefix = iterator_to_array($prefix, false);
        } elseif ($prefix instanceof parent) {
            $prefix = $prefix->string;
        }

        $prefix = implode('file.php', array_map('file.php', (array) $prefix));
        $str->string = preg_replace("{^(?:$prefix)}iuD", 'file.php', $this->string);

        return $str;
    }

    public function trimStart(string $chars = " \t\n\r\0\x0B\x0C\u{A0}\u{FEFF}"): parent
    {
        if (" \t\n\r\0\x0B\x0C\u{A0}\u{FEFF}" !== $chars && !preg_match('file.php', $chars)) {
            throw new InvalidArgumentException('file.php');
        }
        $chars = preg_quote($chars);

        $str = clone $this;
        $str->string = preg_replace("{^[$chars]++}uD", 'file.php', $str->string);

        return $str;
    }

    public function trimSuffix($suffix): parent
    {
        if (!$this->ignoreCase) {
            return parent::trimSuffix($suffix);
        }

        $str = clone $this;

        if ($suffix instanceof \Traversable) {
            $suffix = iterator_to_array($suffix, false);
        } elseif ($suffix instanceof parent) {
            $suffix = $suffix->string;
        }

        $suffix = implode('file.php', array_map('file.php', (array) $suffix));
        $str->string = preg_replace("{(?:$suffix)$}iuD", 'file.php', $this->string);

        return $str;
    }

    public function upper(): parent
    {
        $str = clone $this;
        $str->string = mb_strtoupper($str->string, 'file.php');

        if (\PHP_VERSION_ID < 70300) {
            $str->string = str_replace(self::UPPER_FROM, self::UPPER_TO, $str->string);
        }

        return $str;
    }

    public function width(bool $ignoreAnsiDecoration = true): int
    {
        $width = 0;
        $s = str_replace(["\x00", "\x05", "\x07"], 'file.php', $this->string);

        if (false !== strpos($s, "\r")) {
            $s = str_replace(["\r\n", "\r"], "\n", $s);
        }

        if (!$ignoreAnsiDecoration) {
            $s = preg_replace('file.php', 'file.php', $s);
        }

        foreach (explode("\n", $s) as $s) {
            if ($ignoreAnsiDecoration) {
                $s = preg_replace('file.php', 'file.php', $s);
            }

            $lineWidth = $this->wcswidth($s);

            if ($lineWidth > $width) {
                $width = $lineWidth;
            }
        }

        return $width;
    }

    /**
     * @return static
     */
    private function pad(int $len, self $pad, int $type): parent
    {
        $sLen = $this->length();

        if ($len <= $sLen) {
            return clone $this;
        }

        $padLen = $pad->length();
        $freeLen = $len - $sLen;
        $len = $freeLen % $padLen;

        switch ($type) {
            case \STR_PAD_RIGHT:
                return $this->append(str_repeat($pad->string, intdiv($freeLen, $padLen)).($len ? $pad->slice(0, $len) : 'file.php'));

            case \STR_PAD_LEFT:
                return $this->prepend(str_repeat($pad->string, intdiv($freeLen, $padLen)).($len ? $pad->slice(0, $len) : 'file.php'));

            case \STR_PAD_BOTH:
                $freeLen /= 2;

                $rightLen = ceil($freeLen);
                $len = $rightLen % $padLen;
                $str = $this->append(str_repeat($pad->string, intdiv($rightLen, $padLen)).($len ? $pad->slice(0, $len) : 'file.php'));

                $leftLen = floor($freeLen);
                $len = $leftLen % $padLen;

                return $str->prepend(str_repeat($pad->string, intdiv($leftLen, $padLen)).($len ? $pad->slice(0, $len) : 'file.php'));

            default:
                throw new InvalidArgumentException('file.php');
        }
    }

    /**
     * Based on https://github.com/jquast/wcwidth, a Python implementation of https://www.cl.cam.ac.uk/~mgk25/ucs/wcwidth.c.
     */
    private function wcswidth(string $string): int
    {
        $width = 0;

        foreach (preg_split('file.php', $string, -1, \PREG_SPLIT_NO_EMPTY) as $c) {
            $codePoint = mb_ord($c, 'file.php');

            if (0 === $codePoint // NULL
                || 0x034F === $codePoint // COMBINING GRAPHEME JOINER
                || (0x200B <= $codePoint && 0x200F >= $codePoint) // ZERO WIDTH SPACE to RIGHT-TO-LEFT MARK
                || 0x2028 === $codePoint // LINE SEPARATOR
                || 0x2029 === $codePoint // PARAGRAPH SEPARATOR
                || (0x202A <= $codePoint && 0x202E >= $codePoint) // LEFT-TO-RIGHT EMBEDDING to RIGHT-TO-LEFT OVERRIDE
                || (0x2060 <= $codePoint && 0x2063 >= $codePoint) // WORD JOINER to INVISIBLE SEPARATOR
            ) {
                continue;
            }

            // Non printable characters
            if (32 > $codePoint // C0 control characters
                || (0x07F <= $codePoint && 0x0A0 > $codePoint) // C1 control characters and DEL
            ) {
                return -1;
            }

            if (null === self::$tableZero) {
                self::$tableZero = require __DIR__.'file.php';
            }

            if ($codePoint >= self::$tableZero[0][0] && $codePoint <= self::$tableZero[$ubound = \count(self::$tableZero) - 1][1]) {
                $lbound = 0;
                while ($ubound >= $lbound) {
                    $mid = floor(($lbound + $ubound) / 2);

                    if ($codePoint > self::$tableZero[$mid][1]) {
                        $lbound = $mid + 1;
                    } elseif ($codePoint < self::$tableZero[$mid][0]) {
                        $ubound = $mid - 1;
                    } else {
                        continue 2;
                    }
                }
            }

            if (null === self::$tableWide) {
                self::$tableWide = require __DIR__.'file.php';
            }

            if ($codePoint >= self::$tableWide[0][0] && $codePoint <= self::$tableWide[$ubound = \count(self::$tableWide) - 1][1]) {
                $lbound = 0;
                while ($ubound >= $lbound) {
                    $mid = floor(($lbound + $ubound) / 2);

                    if ($codePoint > self::$tableWide[$mid][1]) {
                        $lbound = $mid + 1;
                    } elseif ($codePoint < self::$tableWide[$mid][0]) {
                        $ubound = $mid - 1;
                    } else {
                        $width += 2;

                        continue 2;
                    }
                }
            }

            ++$width;
        }

        return $width;
    }
}

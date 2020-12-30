<?php

/**
 * Letter increment for custom language.
 *
 * What is implemented
 * * Automatically determines language of letter by code in $alphabet
 * * Return the next letter in alphabet
 * * In case the letters in the alphabet end, supports numbering aka Excel (X,Y,Z,AA,AB etc)
 *
 *
 * @author Denar
 * @link https://github.com/DenarX
 */

class IncLetter
{
    /** @var int[] $alphabet - first and last letter codes for each language alphabets ['firstBig','lastBig','firstSmall','lastSmall'] */
    public static array $alphabet = [
        'en' => [65, 90, 97, 122],
        'gr' => [913, 937, 945, 969],
        'ru' => [1040, 1071, 1072, 1103],
    ];
    /** @param $black - array of blacklisted letter codes which need to exclude*/
    public static array $black = [];
    /** determines the language of the letter by dictionary @param string $letter @return string language ISO-format */
    public static function getlang(string $letter): string
    {
        $code = mb_ord($letter);
        foreach (self::$alphabet as $lang => $langCode) {
            if (($langCode[0] <= $code && $code <= $langCode[1]) || ($langCode[2] <= $code && $code <= $langCode[3])) return $lang;
        }
        return 0;
    }
    /** @param string $prevLetter @return string $nextAlphabetLetter */
    public static function get(string $prevLetter): string
    {
        $r = '';
        $lang = self::getlang($prevLetter);
        if (!$lang) return $r;
        $letters = mb_str_split($prevLetter);
        $inc = true;
        while (!empty($letters)) {
            $letter = array_pop($letters);
            if ($inc) {
                $ascii = mb_ord($letter);
                $ascii++;
                while (in_array($ascii, self::$black)) $ascii++;
                if ($ascii === self::$alphabet[$lang][1] + 1) {
                    $ascii = self::$alphabet[$lang][0];
                    $inc = true;
                } elseif ($ascii === self::$alphabet[$lang][3] + 1) {
                    $ascii = self::$alphabet[$lang][2];
                    $inc = true;
                } else {
                    $inc = false;
                }
                $letter = mb_chr($ascii);
                if ($inc && empty($letters)) {
                    $letter .= $letter;
                }
            }
            $r = $letter . $r;
        }
        return $r;
    }
}

<?php

namespace nvbooster\Money2String;

/**
 * Money to string converter
 *
 */
class Money2StringConverter
{

    /**
     * @param float $money
     *
     * @return string
     */
    public static function convert($money)
    {
        $zeroString = 'ноль';
        $digitStrings = [
            ['', 'один', 'два', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'],
            ['', 'одна', 'две', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'],
        ];
        $teenStrings = [
            'десять',
            'одиннадцать',
            'двенадцать',
            'тринадцать',
            'четырнадцать',
            'пятнадцать',
            'шестнадцать',
            'семнадцать',
            'восемнадцать',
            'девятнадцать',
        ];
        $decadeStrings = [
            2 => 'двадцать',
            'тридцать',
            'сорок',
            'пятьдесят',
            'шестьдесят',
            'семьдесят',
            'восемьдесят',
            'девяносто',
        ];
        $hundredStrings = [
            '',
            'сто',
            'двести',
            'триста',
            'четыреста',
            'пятьсот',
            'шестьсот',
            'семьсот',
            'восемьсот',
            'девятьсот',
        ];
        $unitNameStrings = [
            ['копейка', 'копейки', 'копеек', 1],
            ['рубль', 'рубля', 'рублей', 0],
            ['тысяча', 'тысячи', 'тысяч', 1],
            ['миллион', 'миллиона', 'миллионов', 0],
            ['миллиард', 'милиарда', 'миллиардов', 0],
        ];

        list ($moneyMain, $moneySupplemental) = explode('.', sprintf('%015.2f', floatval($money)));
        $moneyStringParts = [];
        if (intval($moneyMain) > 0) {
            foreach (str_split($moneyMain, 3) as $rank => $rankValue) {
                if (!intval($rankValue)) {
                    continue;
                }
                $rank = sizeof($unitNameStrings) - $rank - 1;
                $gender = $unitNameStrings[$rank][3];
                list ($digit1, $digit2, $digit3) = array_map('intval', str_split($rankValue, 1));

                $moneyStringParts[] = $hundredStrings[$digit1]; // 1xx-9xx
                if ($digit2 > 1) {
                    $moneyStringParts[] = $decadeStrings[$digit2].' '.$digitStrings[$gender][$digit3]; // 20-99
                } else {
                    $moneyStringParts[] = $digit2 > 0 ? $teenStrings[$digit3] : $digitStrings[$gender][$digit3]; // 10-19 | 1-9
                }
                if ($rank > 1) {
                    $moneyStringParts[] = self::morph(
                        $rankValue,
                        $unitNameStrings[$rank][0],
                        $unitNameStrings[$rank][1],
                        $unitNameStrings[$rank][2]
                    );
                }
            }
        } else {
            $moneyStringParts[] = $zeroString;
        }
        $moneyStringParts[] = self::morph(
            intval($moneyMain),
            $unitNameStrings[1][0],
            $unitNameStrings[1][1],
            $unitNameStrings[1][2]
        );
        $moneyStringParts[] = $moneySupplemental.' '.self::morph(
            $moneySupplemental,
            $unitNameStrings[0][0],
            $unitNameStrings[0][1],
            $unitNameStrings[0][2]
        );

        return trim(preg_replace('/ {2,}/', ' ', implode(' ', $moneyStringParts)));
    }

    /**
     * @param integer $count
     * @param string  $form1
     * @param string  $form2
     * @param string  $form3
     *
     * @return string
     */
    public static function morph($count, $form1, $form2, $form3)
    {
        $count = abs(intval($count)) % 100;
        if ($count > 10 && $count < 20) {

            return $form3;
        }
        $count = $count % 10;
        if ($count > 1 && $count < 5) {

            return $form2;
        }
        if ($count == 1) {

            return $form1;
        }

        return $form3;
    }
}

<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class Status extends Enum
{
    const ENTRY =   0;
    const DOCUMENT_SCREENING =   1;
    const FIRST = 2;
    const SECOND = 3;
    const UNOFFICIAL_OFFER = 4;
    const ACCEPT_OFFER = 5;
    const FAILURE = 6;

    public static function getDescription($value): string
    {
        if ($value === self::ENTRY) {
            return '応募';
        }
        
        if ($value === self::DOCUMENT_SCREENING) {
            return '書類選考';
        }
        if ($value === self::FIRST) {
            return '1次選考';
        }
        if ($value === self::SECOND) {
            return '2次選考';
        }
        if ($value === self::UNOFFICIAL_OFFER) {
            return '内定';
        }
        if ($value === self::ACCEPT_OFFER) {
            return '内定承諾';
        }
        if ($value === self::FAILURE) {
            return '不合格';
        }

    }
}

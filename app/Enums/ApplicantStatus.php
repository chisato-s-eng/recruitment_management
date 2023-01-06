<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ApplicantStatus extends Enum
{
    const ENTRY =   0;
    const DOCUMENT_SCREENING =   1;
    const FIRST_ADJUSTING = 2;
    const FIRST_CONSIDERATION = 3;
    const SECOND_ADJUSTING = 4;
    const SECOND_CONSIDERATION = 5;
    const UNOFFICIAL_OFFER = 6;
    const ACCEPT_OFFER = 7;
    const FAILURE = 8;

    public static function getDescription($value): string
    {
        if ($value === self::ENTRY) {
            return '応募';
        }
        
        if ($value === self::DOCUMENT_SCREENING) {
            return '書類選考中';
        }
        if ($value === self::FIRST_ADJUSTING) {
            return '1次選考調整中';
        }
        if ($value === self::FIRST_CONSIDERATION) {
            return '1次選考検討中';
        }
        if ($value === self::SECOND_ADJUSTING) {
            return '2次選考調整中';
        }
        if ($value === self::SECOND_CONSIDERATION) {
            return '2次選考検討中';
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

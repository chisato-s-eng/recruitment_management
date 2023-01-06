<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class RecliteStatus extends Enum
{
    const LOOKING =   0;
    const END =   1;
    const HOLD = 2;

    public static function getDescription($value): string
    {
        if ($value === self::LOOKING) {
            return '募集中';
        }
        
        if ($value === self::END) {
            return '終了';
        }
        if ($value === self::HOLD) {
            return '保留';
        }
    }
}

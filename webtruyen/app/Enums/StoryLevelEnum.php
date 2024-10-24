<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class StoryLevelEnum extends Enum
{
    const AUTHOR = 0;
    const EDITOR = 1;

    public static function getArrayView() {
        return [
            'Bản gốc' => self::AUTHOR,
            'Bản chỉnh sửa' => self::EDITOR,
        ];
    }
    public static function getNameByValue($value) {

        return array_search($value, self::getArrayView(), true);
    }
}

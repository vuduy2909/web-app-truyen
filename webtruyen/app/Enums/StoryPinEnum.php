<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class StoryPinEnum extends Enum
{
    const NOT_APPROVE = -1;
    const EDITING = 0;
    const UPLOADING = 1;
    const APPROVED = 2;
    const PINNED = 3;

    public static function getArrayView() {
        return [
            'Không được duyệt' => self::NOT_APPROVE,
            'Đang chỉnh sửa' => self::EDITING,
            'Chờ kiểm duyệt' => self::UPLOADING,
            'Đã kiểm duyệt' => self::APPROVED,
            'Được ghim' => self::PINNED,
        ];
    }
    public static function getNameByValue($value) {

        return array_search($value, self::getArrayView(), true);
    }
}

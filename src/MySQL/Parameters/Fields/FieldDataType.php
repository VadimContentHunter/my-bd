<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\MySQL\Parameters\Fields;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class FieldDataType
{
    public const TINYTEXT = 'TINYTEXT';

    public const TEXT = 'TEXT';

    public const MEDIUMTEXT = 'MEDIUMTEXT';

    public const LARGETEXT = 'LARGETEXT';

    public const TINYINT = 'TINYINT';

    public const TINYINT_UNSIGNED = 'TINYINT UNSIGNED';

    public const SMALLINT = 'SMALLINT';

    public const SMALLINT_UNSIGNED = 'SMALLINT UNSIGNED';

    public const MEDIUMINT = 'MEDIUMINT';

    public const MEDIUMINT_UNSIGNED = 'MEDIUMINT UNSIGNED';

    public const INT = 'INT';

    public const INT_UNSIGNED = 'INT UNSIGNED';

    public const BIGINT = 'BIGINT';

    public const BIGINT_UNSIGNED = 'BIGINT UNSIGNED';

    public const FLOAT = 'FLOAT';

    public const DOUBLE = 'DOUBLE';

    /**
     * Хранит даты с 1 января 1000 года до 31 декабря 9999 года (c "1000-01-01" до "9999-12-31").
     * По умолчанию для хранения используется формат yyyy-mm-dd. Занимает 3 байта.
     */
    public const DATE = 'DATE';

    /**
     * Хранит время от -838:59:59 до 838:59:59.
     * По умолчанию для хранения времени применяется формат "hh:mm:ss". Занимает 3 байта.
     */
    public const TIME = 'TIME';

    /**
     * Также хранит дату и время, но в другом диапазоне: от "1970-01-01 00:00:01" UTC до "2038-01-19 03:14:07" UTC.
     * Занимает 4 байта
     */
    public const DATETIME = 'DATETIME';

    /**
     * Хранит год в виде 4 цифр. Диапазон доступных значений от 1901 до 2155. Занимает 1 байт.
     */
    public const YEAR = 'YEAR';

    public const TINYBLOB = 'TINYBLOB';

    public const MEDIUMBLOB = 'MEDIUMBLOB';

    public const BLOB = 'BLOB';

    public const LARGEBLOB = 'LARGEBLOB';

    /**
     * @param int $m общее количество цифр
     * @param int $d количество цифр после запятой
     */
    public static function getTypeDouble(int $m, int $d): string
    {
        return '';
    }

    /**
     * @param int $m общее количество цифр
     * @param int $d количество цифр после запятой
     */
    public static function getTypeFloat(int $m, int $d): string
    {
        return '';
    }

    /**
     * Decimal(5,2)
     * Число 5 - precision, а число 2 - scale, поэтому данный столбец может хранить значения из диапазона от -999.99 до 999.99.
     *
     * @param int $precision Представляет максимальное количество цифр, которые может хранить число.
     *                       Это значение должно находиться в диапазоне от 1 до 65.
     * @param int $scale     Представляет максимальное количество цифр, которые может содержать число после запятой.
     *                       Это значение должно находиться в диапазоне от 0 до значения параметра precision. По умолчанию оно равно 0.
     */
    public static function getTypeDecimal(int $precision, int $scale): string
    {
        return '';
    }

    public static function getTypeBool(bool $value): string
    {
        return '';
    }

    public static function getTypeChar(int $length): string
    {
        return '';
    }

    public static function getTypeVarchar(int $length): string
    {
        return '';
    }
}

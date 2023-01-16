<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\MySQL\Parameters\Fields;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class FieldAttributes
{
    public const AUTO_INCREMENT = 'AUTO INCREMENT';

    public const UNIQUE = 'UNIQUE';

    public const NOT_NULL = 'NOT NULL';

    public const PRIMARY_KEY = 'PRIMARY KEY';

    public static function default(string|int $value): string
    {
        if (is_numeric($value)) {
            return 'DEFAULT ' . $value;
        }

        return "DEFAULT '$value'";
    }

    /**
     * @param string[] $fields
     * @param string[] $referencesFields
     * @param string[] $attributes
     */
    public static function foreignKey(array $fields, string $referencesTableName, array $referencesFields, array $attributes): string
    {
        $constrain = '';

        $attributes = array_filter(
            $attributes,
            function (string $value) use (&$constrain) {
                if (preg_match('~CONSTRAINT\s~iu', $value)) {
                    $constrain = $value;
                    return false;
                }

                return true;
            }
        );

        return $constrain . 'FOREIGN KEY (' . implode(",", $fields)
                . ') REFERENCES ' . $referencesTableName . ' (' . implode(",", $fields)
                . ')' . count($attributes) > 0 ? ' ' . implode(",", $attributes) : '';
    }
}

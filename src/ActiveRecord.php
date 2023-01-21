<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB;

use vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\DataMySQLQueryBuilder\DataMySQLQueryBuilder;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
abstract class ActiveRecord
{
    abstract protected static function getTableName(): string;

    public static function getById(int $id): ?self
    {
        $db = new Db();
        $objects = $db->singleRequest()
            ->singleQuery(
                (new DataMySQLQueryBuilder())
                    ->select()
                        ->addField('*')
                        ->from(static::getTableName())
                            ->where('id=:id')
            )
            ->setClassName(static::class)
            ->addParameters(':id', $id)
            ->send();
        return $objects ? $objects[0] : null;
    }
}

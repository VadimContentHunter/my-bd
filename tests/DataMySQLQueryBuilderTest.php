<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\Tests;

use PHPUnit\Framework\TestCase;
use vadimcontenthunter\MyDB\Exceptions\QueryBuilderException;
use vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\DataMySQLQueryBuilder\DataMySQLQueryBuilder;
use vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\DataMySQLQueryBuilder\Operators\MySqlOperatorOptionsInsert;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class DataMySQLQueryBuilderTest extends TestCase
{
    protected DataMySQLQueryBuilder $dataMySQLQueryBuilder;

    public function setUp(): void
    {
        $this->dataMySQLQueryBuilder = new DataMySQLQueryBuilder();
    }

    /**
     * @dataProvider providerInsert
     */
    public function test_insert_withTableNameAndFieldsNames_shouldReturnAString(string $table_name, array $field_names): void
    {
        $result = $this->dataMySQLQueryBuilder->insert($table_name, $field_names);
        $this->assertInstanceOf(MySqlOperatorOptionsInsert::class, $result);
        $this->assertEquals('INSERT Customers(name,last_name,age)', $result->getQuery());
    }

    public function providerInsert(): array
    {
        return [
            'regular data' => [
                'Customers',
                [
                    'name',
                    'last_name',
                    'age',
                ]
            ],
        ];
    }

    /**
     * @dataProvider providerInsertException
     */
    public function test_insert_withTableNameAndFieldsNames_shouldReturnAnException(string $table_name, array $field_names, \Exception $objException): void
    {
        $this->expectException($objException::class);

        $this->dataMySQLQueryBuilder->insert($table_name, $field_names);
    }

    public function providerInsertException(): array
    {
        return [
            'empty field names' => [
                'Customers',
                [],
                new QueryBuilderException()
            ],
            'incorrect field names' => [
                'Customers',
                [
                    123
                ],
                new QueryBuilderException()
            ],
        ];
    }
}

<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\Tests;

use PHPUnit\Framework\TestCase;
use vadimcontenthunter\MyDB\Exceptions\QueryBuilderException;
use vadimcontenthunter\MyDB\MySQL\Parameters\Fields\FieldDataType;
use vadimcontenthunter\MyDB\MySQL\Parameters\Fields\FieldAttributes;
use vadimcontenthunter\MyDB\MySQL\Parameters\Fields\ForeignKeyAttributes;
use vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\TableMySQLQueryBuilder\Operators\MySqlOperatorOptionsAlter;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class MySqlOperatorOptionsAlterTest extends TestCase
{
    protected MySqlOperatorOptionsAlter $mySqlOperatorOptionsAlter;

    public function setUp(): void
    {
        $this->mySqlOperatorOptionsAlter = new MySqlOperatorOptionsAlter();
    }

    /**
     * @test
     * @dataProvider providerAddColumn
     *
     * @param array<array<string,mixed>> $parameters
     */
    public function test_addColumn_withParameters_shouldChangeInternalParameterQuery(
        string $expected,
        string $query,
        array $parameters,
    ): void {
        $this->mySqlOperatorOptionsAlter->setQuery($query);
        foreach ($parameters as $i => $values) {
            $this->mySqlOperatorOptionsAlter->addColumn($values['field_name'], $values['data_type'], $values['field_attribute']);
        }
        $this->assertEquals($expected, $this->mySqlOperatorOptionsAlter->getQuery());
    }

    /**
     * @return array<string,mixed>
     */
    public function providerAddColumn(): array
    {
        return [
            'test 1' => [
                "ALTER TABLE Customers "
                . "ADD Address VARCHAR(50) NULL,"
                . "ADD Address2 VARCHAR(50) NOT NULL;",
                "ALTER TABLE Customers",
                [
                    [
                        'field_name' => 'Address',
                        'data_type' => FieldDataType::getTypeVarchar(50),
                        'field_attribute' => [
                            FieldAttributes::NULL,
                        ]
                    ],
                    [
                        'field_name' => 'Address2',
                        'data_type' => FieldDataType::getTypeVarchar(50),
                        'field_attribute' => [
                            FieldAttributes::NOT_NULL,
                        ]
                    ],
                ],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider providerAddColumn
     *
     * @param array<array<string,mixed>> $parameters
     */
    public function test_modifyColumn_withParameters_shouldChangeInternalParameterQuery(
        string $expected,
        string $query,
        array $parameters,
    ): void {
        $this->mySqlOperatorOptionsAlter->setQuery($query);
        foreach ($parameters as $i => $values) {
            $this->mySqlOperatorOptionsAlter->addColumn($values['field_name'], $values['data_type'], $values['field_attribute']);
        }
        $this->assertEquals($expected, $this->mySqlOperatorOptionsAlter->getQuery());
    }

    /**
     * @return array<string,mixed>
     */
    public function providerModifyColumn(): array
    {
        return [
            'test 1' => [
                "ALTER TABLE Customers "
                . "MODIFY COLUMN Address VARCHAR(50) UNIQUE,"
                . "MODIFY COLUMN FirstName CHAR(100) NULL;",
                "ALTER TABLE Customers",
                [
                    [
                        'field_name' => 'Address',
                        'data_type' => FieldDataType::getTypeVarchar(50),
                        'field_attribute' => [
                            FieldAttributes::UNIQUE,
                        ]
                    ],
                    [
                        'field_name' => 'FirstName',
                        'data_type' => FieldDataType::getTypeVarchar(50),
                        'field_attribute' => [
                            FieldAttributes::NULL,
                        ]
                    ],
                ],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider providerAddColumn
     *
     * @param array<array<string,mixed>> $parameters
     */
    public function test_multipleMethods_withParameters_shouldChangeInternalParameterQuery(): void
    {
        $expected = "ALTER TABLE Customers "
        . "MODIFY COLUMN Address VARCHAR(50) UNIQUE,"
        . "ADD Address2 VARCHAR(50) NULL,"
        . "ADD CONSTRAINT orders_customers_fk FOREIGN KEY(CustomerId) REFERENCES Customers(Id),"
        . "ALTER COLUMN Age SET DEFAULT 22,"
        . "DROP COLUMN Address,"
        . "DROP FOREIGN KEY orders_customers_fk,"
        . "ADD PRIMARY KEY (Id),"
        . "DROP PRIMARY KEY;";
        $query = "ALTER TABLE Customers";

        $this->mySqlOperatorOptionsAlter->setQuery($query);
        $this->mySqlOperatorOptionsAlter->modifyColumn('Address', FieldDataType::getTypeVarchar(50), [FieldAttributes::UNIQUE])
            ->addColumn('Address2', FieldDataType::getTypeVarchar(50), [FieldAttributes::NULL])
            ->addConsrtaint('orders_customers_fk', 'FOREIGN KEY(CustomerId) REFERENCES Customers(Id)')
            ->alterColumn('Age', 22)
            ->dropColumn('Address')
            ->dropConsrtaint('orders_customers_fk', 'FOREIGN KEY')
            ->addPrimaryKey('Id')
            ->dropPrimaryKey();

        $this->assertEquals($expected, $this->mySqlOperatorOptionsAlter->getQuery());
    }
}

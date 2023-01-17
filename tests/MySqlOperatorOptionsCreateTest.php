<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\Tests;

use PHPUnit\Framework\TestCase;
use vadimcontenthunter\MyDB\Exceptions\QueryBuilderException;
use vadimcontenthunter\MyDB\MySQL\Parameters\Fields\FieldDataType;
use vadimcontenthunter\MyDB\MySQL\Parameters\Fields\FieldAttributes;
use vadimcontenthunter\MyDB\MySQL\Parameters\Fields\ForeignKeyAttributes;
use vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\TableMySQLQueryBuilder\Operators\MySqlOperatorOptionsCreate;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class MySqlOperatorOptionsCreateTest extends TestCase
{
    protected MySqlOperatorOptionsCreate $mySqlOperatorOptionsCreate;

    public function setUp(): void
    {
        $this->mySqlOperatorOptionsCreate = new MySqlOperatorOptionsCreate();
    }

    /**
     * @test
     */
    public function test_addField_withParameters_shouldChangeInternalParameterQuery(): void
    {
        $expected = "CREATE TABLE Customers(Id INT PRIMARY KEY AUTO_INCREMENT,Age INT,FirstName VARCHAR(20) NOT NULL,LastName VARCHAR(20) NOT NULL);";
        $query = 'CREATE TABLE Customers';
        $this->mySqlOperatorOptionsCreate->setQuery($query);
        $this->mySqlOperatorOptionsCreate->addField('Id', FieldDataType::INT, [
                FieldAttributes::PRIMARY_KEY,
                FieldAttributes::AUTO_INCREMENT
            ])
            ->addField('Age', FieldDataType::INT)
            ->addField('FirstName', FieldDataType::getTypeVarchar(20), [
                FieldAttributes::NOT_NULL
            ])
            ->addField('LastName', FieldDataType::getTypeVarchar(20), [
                FieldAttributes::NOT_NULL
            ]);
        $this->assertEquals($expected, $this->mySqlOperatorOptionsCreate->getQuery());
    }

    /**
     * @test
     */
    public function test_consrtaintCheck_withExistingConstraint_shouldChangeInternalParameterQuery(): void
    {
        $expected = "CREATE TABLE Customers("
                    . "Id INT AUTO_INCREMENT,"
                    . "Age INT,"
                    . "FirstName VARCHAR(20) NOT NULL,"
                    . "LastName VARCHAR(20) NOT NULL,"
                    . "Email VARCHAR(30),"
                    . "Phone VARCHAR(20) NOT NULL,"
                    . "CONSTRAINT customers_pk PRIMARY KEY(Id),"
                    . "CONSTRAINT customer_phone_uq UNIQUE(Phone),"
                    . "CONSTRAINT customer_age_chk CHECK((Age > 0) AND (Age < 100) AND (Id > 5))"
                    . ");";
        $query = "CREATE TABLE Customers("
                . "Id INT AUTO_INCREMENT,"
                . "Age INT,"
                . "FirstName VARCHAR(20) NOT NULL,"
                . "LastName VARCHAR(20) NOT NULL,"
                . "Email VARCHAR(30),"
                . "Phone VARCHAR(20) NOT NULL,"
                . "CONSTRAINT customers_pk PRIMARY KEY(Id),"
                . "CONSTRAINT customer_phone_uq UNIQUE(Phone),"
                . "CONSTRAINT customer_age_chk CHECK((Age > 0) AND (Age < 100))"
                . ")";
        $this->mySqlOperatorOptionsCreate->setQuery($query);
        $this->mySqlOperatorOptionsCreate->consrtaintCheck('customer_age_chk', 'Id', '>', '5');
        $this->assertEquals($expected, $this->mySqlOperatorOptionsCreate->getQuery());
    }

    /**
     * @test
     */
    public function test_consrtaintCheck_withANonexistentConstraint_shouldChangeInternalParameterQuery(): void
    {
        $expected = "CREATE TABLE Customers("
                    . "Id INT AUTO_INCREMENT,"
                    . "Age INT,"
                    . "FirstName VARCHAR(20) NOT NULL,"
                    . "LastName VARCHAR(20) NOT NULL,"
                    . "Email VARCHAR(30),"
                    . "Phone VARCHAR(20) NOT NULL,"
                    . "CONSTRAINT customers_pk PRIMARY KEY(Id),"
                    . "CONSTRAINT customer_phone_uq UNIQUE(Phone),"
                    . "CONSTRAINT customer_age_chk CHECK((Id > 5))"
                    . ");";
        $query = "CREATE TABLE Customers("
                . "Id INT AUTO_INCREMENT,"
                . "Age INT,"
                . "FirstName VARCHAR(20) NOT NULL,"
                . "LastName VARCHAR(20) NOT NULL,"
                . "Email VARCHAR(30),"
                . "Phone VARCHAR(20) NOT NULL,"
                . "CONSTRAINT customers_pk PRIMARY KEY(Id),"
                . "CONSTRAINT customer_phone_uq UNIQUE(Phone)"
                . ")";
        $this->mySqlOperatorOptionsCreate->setQuery($query);
        $this->mySqlOperatorOptionsCreate->consrtaintCheck('customer_age_chk', 'Id', '>', '5');
        $this->assertEquals($expected, $this->mySqlOperatorOptionsCreate->getQuery());
    }

    /**
     * @test
     * @dataProvider providerConsrtaintUnique
     *
     * @param string[] $field_names
     */
    public function test_consrtaintUnique_withParameters_shouldChangeInternalParameterQuery(
        string $expected,
        string $query,
        string $consrtaint_name,
        array $field_names
    ): void {
        $this->mySqlOperatorOptionsCreate->setQuery($query);
        $this->mySqlOperatorOptionsCreate->consrtaintUnique($consrtaint_name, $field_names);
        $this->assertEquals($expected, $this->mySqlOperatorOptionsCreate->getQuery());
    }

    /**
     * @return array<string,mixed>
     */
    public function providerConsrtaintUnique(): array
    {
        return [
            'withExistingConstraint' => [
                "CREATE TABLE Customers("
                . "Id INT AUTO_INCREMENT,"
                . "Age INT,"
                . "FirstName VARCHAR(20) NOT NULL,"
                . "LastName VARCHAR(20) NOT NULL,"
                . "Email VARCHAR(30),"
                . "Phone VARCHAR(20) NOT NULL,"
                . "CONSTRAINT customers_pk PRIMARY KEY(Id),"
                . "CONSTRAINT customer_phone_uq UNIQUE(Phone,Id,Email),"
                . "CONSTRAINT customer_age_chk CHECK((Id > 5))"
                . ");",
                "CREATE TABLE Customers("
                . "Id INT AUTO_INCREMENT,"
                . "Age INT,"
                . "FirstName VARCHAR(20) NOT NULL,"
                . "LastName VARCHAR(20) NOT NULL,"
                . "Email VARCHAR(30),"
                . "Phone VARCHAR(20) NOT NULL,"
                . "CONSTRAINT customers_pk PRIMARY KEY(Id),"
                . "CONSTRAINT customer_phone_uq UNIQUE(Phone),"
                . "CONSTRAINT customer_age_chk CHECK((Id > 5))"
                . ")",
                'customer_phone_uq',
                [
                    'Id',
                    'Email',
                ]
            ],
        ];
    }

    /**
     * @test
     * @dataProvider providerConsrtaintForeignKey
     *
     * @param string[] $field_names
     * @param string[] $references_fields
     */
    public function test_consrtaintForeignKey_withParameters_shouldChangeInternalParameterQuery(
        string $expected,
        string $query,
        string $consrtaint_name,
        array $field_names,
        string $references_table_name,
        array $references_fields,
        ?string $attribute_on,
        ?string $action_on
    ): void {
        $this->mySqlOperatorOptionsCreate->setQuery($query);
        $this->mySqlOperatorOptionsCreate->consrtaintForeignKey(
            $consrtaint_name,
            $field_names,
            $references_table_name,
            $references_fields,
            $attribute_on,
            $action_on
        );
        $this->assertEquals($expected, $this->mySqlOperatorOptionsCreate->getQuery());
    }

    /**
     * @return array<string,mixed>
     */
    public function providerConsrtaintForeignKey(): array
    {
        return [
            'withExistingConstraint' => [
                "CREATE TABLE Orders("
                . "Id INT PRIMARY KEY AUTO_INCREMENT,"
                . "CustomerId INT,"
                . "CreatedAt Date,"
                . "CONSTRAINT orders_customers_fk FOREIGN KEY(CustomerId) REFERENCES Customers (Id) ON DELETE CASCADE"
                . ");",
                "CREATE TABLE Orders("
                . "Id INT PRIMARY KEY AUTO_INCREMENT,"
                . "CustomerId INT,"
                . "CreatedAt Date"
                . ")",
                'orders_customers_fk',
                [
                    'CustomerId',
                ],
                'Customers',
                [
                    'Id',
                ],
                ForeignKeyAttributes::ON_DELETE,
                ForeignKeyAttributes::ACTION_CASCADE,
            ],
        ];
    }
}

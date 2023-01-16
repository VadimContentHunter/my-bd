<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\Tests;

use PHPUnit\Framework\TestCase;
use vadimcontenthunter\MyDB\Exceptions\QueryBuilderException;
use vadimcontenthunter\MyDB\MySQL\Parameters\Fields\FieldDataType;
use vadimcontenthunter\MyDB\MySQL\Parameters\Fields\FieldAttributes;
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
        $expected = "CREATE TABLE Customers(Id INT PRIMARY KEY AUTO_INCREMENT,Age INT,FirstName VARCHAR(20) NOT NULL,LastName VARCHAR(20) NOT NULL)";
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
}

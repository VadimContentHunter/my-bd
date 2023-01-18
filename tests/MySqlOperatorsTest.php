<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\Tests;

use PHPUnit\Framework\TestCase;
use vadimcontenthunter\MyDB\Tests\Fakes\MySqlOperatorsFake;
use vadimcontenthunter\MyDB\Exceptions\QueryBuilderException;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class MySqlOperatorsTest extends TestCase
{
    protected MySqlOperatorsFake $mySqlOperatorsFake;

    public function setUp(): void
    {
        $this->mySqlOperatorsFake = new MySqlOperatorsFake();
    }

    /**
     * @test
     * @dataProvider providerSetQuery
     */
    public function test_setQuery_withParameters_shouldSaveTheQueryAndTheCommand(
        string $query,
        string $command
    ): void {
        $this->mySqlOperatorsFake->setQuery($query);
        $this->assertEquals($query, $this->mySqlOperatorsFake->getQueryFake());
        $this->assertEquals($command, $this->mySqlOperatorsFake->getCommandFake());
    }

    /**
     * @return array<string,mixed>
     */
    public function providerSetQuery(): array
    {
        return [
            'Command UPDATE' => [
                "UPDATE Products SET Manufacturer = 'Samsung' WHERE Manufacturer ='Samsung Inc.';",
                "UPDATE",
            ],
            'Command DELETE' => [
                "DELETE FROM Products WHERE Manufacturer='Huawei';",
                "DELETE",
            ],
            'Command SELECT' => [
                "SELECT * FROM Products;",
                "SELECT",
            ],
        ];
    }

    /**
     * @test
     * @dataProvider providerSetQueryException
     * @depends test_setQuery_withParameters_shouldSaveTheQueryAndTheCommand
     */
    public function test_setQuery_withParameters_shouldReturnAnException(
        string $query,
        \Exception $objException
    ): void {
        $this->expectException($objException::class);
        $this->mySqlOperatorsFake->setQuery($query);
    }

    /**
     * @return array<string,mixed>
     */
    public function providerSetQueryException(): array
    {
        return [
            'Without command' => [
                "Products SET Manufacturer = 'Samsung' WHERE Manufacturer ='Samsung Inc.';",
                new QueryBuilderException(),
            ],
            'Command other than UPDATE, SELECT, DELETE' => [
                "CREATE TABLE Customers(Id INT,Age INT,FirstName VARCHAR(20),LastName VARCHAR(20));",
                new QueryBuilderException(),
            ],
        ];
    }

    /**
     * @test
     * @depends test_setQuery_withParameters_shouldSaveTheQueryAndTheCommand
     */
    public function test_in_withParameterNot_shouldChangeInternalParameterQuery(): void
    {
        $expected = "SELECT * FROM Products WHERE Manufacturer NOT IN ('Samsung','HTC','Huawei')";
        $query = 'SELECT * FROM Products WHERE Manufacturer';
        $this->mySqlOperatorsFake->setQuery($query);
        $this->mySqlOperatorsFake->in(
            [
                "'Samsung'",
                "'HTC'"
            ],
            true
        );
        $this->mySqlOperatorsFake->in("'Huawei'");
        $this->assertEquals($expected, $this->mySqlOperatorsFake->getQueryFake());
    }

    /**
     * @test
     * @depends test_setQuery_withParameters_shouldSaveTheQueryAndTheCommand
     */
    public function test_like_withParameterNot_shouldChangeInternalParameterQuery(): void
    {
        $expected = "SELECT * FROM Products WHERE ProductName NOT LIKE '_Phone%'";
        $query = 'SELECT * FROM Products WHERE ProductName';
        $this->mySqlOperatorsFake->setQuery($query);
        $this->mySqlOperatorsFake->like('_Phone%', true);
        $this->assertEquals($expected, $this->mySqlOperatorsFake->getQueryFake());
    }

    /**
     * @test
     * @depends test_setQuery_withParameters_shouldSaveTheQueryAndTheCommand
     */
    public function test_regex_withParameterNot_shouldChangeInternalParameterQuery(): void
    {
        $expected = "SELECT * FROM Products WHERE ProductName NOT REGEXP 'iPhone [78]'";
        $query = 'SELECT * FROM Products WHERE ProductName';
        $this->mySqlOperatorsFake->setQuery($query);
        $this->mySqlOperatorsFake->regex('iPhone [78]', true);
        $this->assertEquals($expected, $this->mySqlOperatorsFake->getQueryFake());
    }

    /**
     * @test
     * @dataProvider providerOrderByDesc
     * @depends test_setQuery_withParameters_shouldSaveTheQueryAndTheCommand
     *
     * @param string[] $fields
     */
    public function test_orderByDesc_withParameterFieldName_shouldChangeInternalParameterQuery(
        string $query,
        array $fields,
        string $expected
    ): void {
        $this->mySqlOperatorsFake->setQuery($query);

        foreach ($fields as $key => $field_name) {
            $this->mySqlOperatorsFake->orderByDesc($field_name);
        }

        $this->assertEquals($expected, $this->mySqlOperatorsFake->getQueryFake());
    }


    /**
     * @return array<string,mixed>
     */
    public function providerOrderByDesc(): array
    {
        return [
            'add first field to sort' => [
                'SELECT ProductName, Price, Manufacturer FROM Products',
                [
                    'ProductName',
                ],
                'SELECT ProductName, Price, Manufacturer FROM Products ORDER BY ProductName DESC'
            ],
            'add fields to existing ones in sorting' => [
                'SELECT ProductName, Price, Manufacturer FROM Products ORDER BY Manufacturer ASC',
                [
                    'ProductName',
                    'ClientName'
                ],
                'SELECT ProductName, Price, Manufacturer FROM Products ORDER BY Manufacturer ASC, ProductName DESC, ClientName DESC'
            ],
        ];
    }

    /**
     * @test
     * @dataProvider providerOrderByAsc
     * @depends test_setQuery_withParameters_shouldSaveTheQueryAndTheCommand
     *
     * @param string[] $fields
     */
    public function test_orderByAsc_withParameterFieldName_shouldChangeInternalParameterQuery(
        string $query,
        array $fields,
        string $expected
    ): void {
        $this->mySqlOperatorsFake->setQuery($query);

        foreach ($fields as $key => $field_name) {
            $this->mySqlOperatorsFake->orderByAsc($field_name);
        }

        $this->assertEquals($expected, $this->mySqlOperatorsFake->getQueryFake());
    }


    /**
     * @return array<string,mixed>
     */
    public function providerOrderByAsc(): array
    {
        return [
            'add first field to sort' => [
                'SELECT ProductName, Price, Manufacturer FROM Products',
                [
                    'ProductName',
                ],
                'SELECT ProductName, Price, Manufacturer FROM Products ORDER BY ProductName ASC'
            ],
            'add fields to existing ones in sorting' => [
                'SELECT ProductName, Price, Manufacturer FROM Products ORDER BY Manufacturer DESC',
                [
                    'ProductName',
                    'ClientName'
                ],
                'SELECT ProductName, Price, Manufacturer FROM Products ORDER BY Manufacturer DESC, ProductName ASC, ClientName ASC'
            ],
        ];
    }

     /**
     * @test
     * @depends test_setQuery_withParameters_shouldSaveTheQueryAndTheCommand
     */
    public function test_limit_withParametersRowCountAndOffSet_shouldChangeInternalParameterQuery(): void
    {
        $expected = 'SELECT * FROM Products LIMIT 2, 3';
        $query = 'SELECT * FROM Products';
        $this->mySqlOperatorsFake->setQuery($query);
        $this->mySqlOperatorsFake->limit(3, 2);
        $this->assertEquals($expected, $this->mySqlOperatorsFake->getQueryFake());
    }

    /**
     * @test
     * @depends test_setQuery_withParameters_shouldSaveTheQueryAndTheCommand
     */
    public function test_onAndInnerJoin_withParameters_shouldChangeInternalParameterQuery(): void
    {
        $expected = "SELECT Orders.CreatedAt,Customers.FirstName,Products.ProductName FROM Orders JOIN Products ON Products.Id = Orders.ProductId JOIN Customers ON Customers.Id = Orders.CustomerId";
        $query = 'SELECT CreatedAt, Customers.FirstName, Products.ProductName FROM Orders';

        $this->mySqlOperatorsFake->setQuery($query);
        $this->mySqlOperatorsFake->innerJoin('Products')
            ->on('Products.Id', '=', 'Orders.ProductId')
            ->innerJoin('Customers')
            ->on('Customers.Id', '=', 'Orders.CustomerId');

        $this->assertEquals($expected, $this->mySqlOperatorsFake->getQueryFake());
    }

    /**
     * @test
     * @depends test_setQuery_withParameters_shouldSaveTheQueryAndTheCommand
     */
    public function test_onAndLeftJoin_withParameters_shouldChangeInternalParameterQuery(): void
    {
        $expected = "SELECT Orders.CreatedAt,Customers.FirstName,Products.ProductName FROM Orders LEFT JOIN Products ON Products.Id = Orders.ProductId LEFT JOIN Customers ON Customers.Id = Orders.CustomerId";
        $query = 'SELECT CreatedAt, Customers.FirstName, Products.ProductName FROM Orders';

        $this->mySqlOperatorsFake->setQuery($query);
        $this->mySqlOperatorsFake->leftJoin('Products')
            ->on('Products.Id', '=', 'Orders.ProductId')
            ->leftJoin('Customers')
            ->on('Customers.Id', '=', 'Orders.CustomerId');

        $this->assertEquals($expected, $this->mySqlOperatorsFake->getQueryFake());
    }

    /**
     * @test
     * @depends test_setQuery_withParameters_shouldSaveTheQueryAndTheCommand
     */
    public function test_onAndRightJoin_withParameters_shouldChangeInternalParameterQuery(): void
    {
        $expected = "SELECT Orders.CreatedAt,Customers.FirstName,Products.ProductName FROM Orders RIGHT JOIN Products ON Products.Id = Orders.ProductId RIGHT JOIN Customers ON Customers.Id = Orders.CustomerId";
        $query = 'SELECT CreatedAt, Customers.FirstName, Products.ProductName FROM Orders';

        $this->mySqlOperatorsFake->setQuery($query);
        $this->mySqlOperatorsFake->rightJoin('Products')
            ->on('Products.Id', '=', 'Orders.ProductId')
            ->rightJoin('Customers')
            ->on('Customers.Id', '=', 'Orders.CustomerId');

        $this->assertEquals($expected, $this->mySqlOperatorsFake->getQueryFake());
    }
}

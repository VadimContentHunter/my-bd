<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\Tests;

use PDO;
use stdClass;
use PDOException;
use PHPUnit\Framework\TestCase;
use vadimcontenthunter\MyDB\DB;
use vadimcontenthunter\MyDB\Connectors\Connector;
use vadimcontenthunter\MyDB\MySQL\Parameters\Fields\FieldDataType;
use vadimcontenthunter\MyDB\MySQL\Parameters\Fields\FieldAttributes;
use vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\DataMySQLQueryBuilder\DataMySQLQueryBuilder;
use vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\TableMySQLQueryBuilder\TableMySQLQueryBuilder;

/**
 * DbTests
 *
 * @package   Tests_DbTests
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class DbTest extends TestCase
{
    protected DB $myDb;

    public function setUp(): void
    {
        $this->markTestSkipped(
            'Пропуск теста из за отсутствия базы данных в удаленном окружении.'
        );

        $this->myDb = new DB(
            new Connector(
                typeDb: 'mysql',
                host: 'localhost',
                dbName: 'productsdb',
                user: 'mytest',
                password: 'mytest',
                options: [
                    PDO::ATTR_PERSISTENT => true
                ]
            )
        );
    }

    /** @test */
    public function test_singleRequest_clearTableData_shouldClearAllDataInTheDatabase(): void
    {
        $this->myDb->singleRequest()
            ->singleQuery(
                (new TableMySQLQueryBuilder())
                    ->truncate('Customers')
            )
            ->send();
        $this->assertTrue(true);
    }

    /** @test */
    public function test_singleRequest_dropTable_shouldDropTheTable(): void
    {
        $this->myDb->singleRequest()
            ->singleQuery(
                (new TableMySQLQueryBuilder())
                    ->drop('Customers')
            )
            ->send();

        $this->assertTrue(true);
    }

    /** @test */
    public function test_singleRequest_createTable_shouldCreateTheTable(): void
    {
        $this->myDb->singleRequest()
            ->singleQuery(
                (new TableMySQLQueryBuilder())
                    ->create('Customers')
                        ->addField('Id', FieldDataType::INT, [
                            FieldAttributes::AUTO_INCREMENT,
                            FieldAttributes::PRIMARY_KEY,
                            FieldAttributes::NOT_NULL
                        ])
                        ->addField('Age', FieldDataType::INT, [
                            FieldAttributes::NULL
                        ])
                        ->addField('FirstName', FieldDataType::TEXT)
                        ->addField('LastName', FieldDataType::TEXT)
                        ->addField('NewAddress', FieldDataType::TEXT, [
                            FieldAttributes::NULL
                        ])
            )
            ->send();

        $this->assertTrue(true);
    }

     /** @test */
    public function test_singleRequest_alterTable_shouldEditTheTable(): void
    {
        $this->myDb->singleRequest()
            ->singleQuery(
                (new TableMySQLQueryBuilder())
                    ->alter('Customers')
                        ->modifyColumn('FirstName', FieldDataType::getTypeVarchar(50))
                        ->modifyColumn('LastName', FieldDataType::getTypeVarchar(50))
            )
            ->send();

        $this->assertTrue(true);
    }

    /** @test */
    public function test_singleRequest_innerData_shouldAddNewDataInTheDatabase(): void
    {
        $this->myDb->singleRequest()
            ->singleQuery(
                (new DataMySQLQueryBuilder())
                    ->insert('Customers')
                        ->addValues('Age', '43')
                        ->addValues('FirstName	', 'Elvie')
                        ->addValues('LastName', 'Kozey')
                        ->addValues('NewAddress', 'Monique Tunnel West Providenci')
            )
            ->send();

        $this->myDb->singleRequest()
            ->singleQuery(
                (new DataMySQLQueryBuilder())
                    ->insert('Customers')
                        ->setValues([
                            'Age' => ['28','31'],
                            'FirstName' => ['Mathew','Кристина'],
                            'LastName' => ['Stroman','Миронова'],
                            'NewAddress' => [
                                'Hyatt Causeway Mooremouth',
                                'Брянская область, город Ногинск'
                            ]
                        ])
            )
            ->send();

        $this->assertEquals(1, $this->myDb->connector->getNumConnected());
    }

    /** @test */
    public function test_singleRequest_updateData_shouldUpdateDataInTheDatabase(): void
    {
        $this->myDb->singleRequest()
            ->singleQuery(
                (new DataMySQLQueryBuilder())
                    ->update('Customers')
                        ->set('FirstName', 'Никита')
                        ->set('LastName', 'Макар')
                        ->getOperators()
                            ->where('Id = 2')
            )
            ->send();

        $this->assertTrue(true);
    }

    /** @test */
    public function test_singleRequest_selectData_shouldSelectDataInTheDatabase(): void
    {
        $actual = $this->myDb->singleRequest()
            ->singleQuery(
                (new DataMySQLQueryBuilder())
                    ->select()
                        ->addField('Age')
                        ->addField('FirstName')
                        ->from('Customers')
                            ->where('Id > 1')
            )
            ->setClassName(stdClass::class)
            ->send();

        $this->assertTrue(true);
    }

    /** @test */
    public function test_singleRequest_deleteData_shouldDeleteDataInTheDatabase(): void
    {
        $this->myDb->singleRequest()
            ->singleQuery(
                (new DataMySQLQueryBuilder())
                    ->delete('Customers')
                        ->where('Id = 2')
                        ->or('Id = 3')
            )
            ->send();

        $this->assertTrue(true);
    }

    /** @test */
    public function test_transactionalRequests_innerData_shouldAddNewDataInTheDatabase(): void
    {
        $this->myDb->transactionalRequests()
            ->addQuery(
                (new DataMySQLQueryBuilder())
                    ->insert('Customers')
                        ->setValues([
                            'Age' => ['28','31'],
                            'FirstName' => ['Mathew','Кристина'],
                            'LastName' => ['Stroman','Миронова'],
                            'NewAddress' => [
                                'Hyatt Causeway Mooremouth',
                                'Брянская область, город Ногинск'
                            ]
                        ])
            );

        $this->myDb->transactionalRequests()
            ->addQuery(
                (new DataMySQLQueryBuilder())
                        ->delete('Customers')
                            ->where('Id = 1')
                            ->or('Id = 2')
            )
            ->addQuery(
                (new DataMySQLQueryBuilder())
                    ->insert('Customers')
                        ->addValues('Age', '43')
                        ->addValues('FirstName   ', 'Elvie')
                        ->addValues('LastName', 'Kozey')
                        ->addValues('NewAddress', 'Monique Tunnel West Providenci')
            );

        $res2 = $this->myDb->transactionalRequests()->send();

        $this->assertEquals(1, $this->myDb->connector->getNumConnected());
    }
}

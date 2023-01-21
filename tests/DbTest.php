<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\Tests;

use PDO;
use stdClass;
use PDOException;
use PHPUnit\Framework\TestCase;
use vadimcontenthunter\MyDB\DB;
use vadimcontenthunter\MyDB\Connectors\Connector;
use vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\DatabaseMySQLQueryBuilder\DatabaseMySQLQueryBuilder;
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
        try {
            DB::$connector = new Connector(
                typeDb: 'mysql',
                host: 'localhost',
                user: 'mytest',
                password: 'mytest',
                dbName: 'productsdb',
                options: [
                        PDO::ATTR_PERSISTENT => true
                    ]
            );

            DB::$connector->connect();
        } catch (\Throwable $th) {
            $this->markTestSkipped(
                'Пропуск теста из за отсутствия базы данных в удаленном окружении.'
            );
        }

        $this->myDb = new DB();
    }

    public function tearDown(): void
    {
        DB::$connector->resetTotalConnected();
    }

    /** @test */
    public function test_singleRequest_dropDatabaseTableData_shouldClearAllDataInTheDatabase(): void
    {
        DB::$connector = new Connector(
            typeDb: 'mysql',
            host: 'localhost',
            user: 'mytest',
            password: 'mytest',
            options: [
                    PDO::ATTR_PERSISTENT => true
                ]
        );

        $this->myDb->singleRequest()
            ->singleQuery(
                (new DatabaseMySQLQueryBuilder())
                    ->dropDatabase('productsdb', true)
            )
            ->send();
        $this->assertTrue(true);
    }

    /** @test */
    public function test_singleRequest_createDatabaseTableData_shouldClearAllDataInTheDatabase(): void
    {
        $this->myDb->singleRequest()
            ->singleQuery(
                (new DatabaseMySQLQueryBuilder())
                    ->createDatabase('productsdb', true)
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
    public function test_singleRequest_showTable_shouldDropTheTable(): void
    {
        $tables = $this->myDb->singleRequest()
            ->singleQuery(
                (new TableMySQLQueryBuilder())
                    ->setQuery("SHOW TABLES FROM productsdb like 'Customers';")
            )
            ->send();

        if ($tables[0] ?? '' !== 'Customers') {
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
        }

        $this->assertTrue(true);
    }

    /** @test */
    public function test_singleRequest_innerData_shouldAddNewDataInTheDatabase(): void
    {
        $this->myDb->singleRequest()
            ->singleQuery(
                (new DataMySQLQueryBuilder())
                    ->insert('Customers')
                        ->addValue('Age', '43')
                        ->addValue('FirstName', 'Elvie')
                        ->addValue('LastName', 'Kozey')
                        ->addValue('NewAddress', 'Monique Tunnel West Providenci')
            )
            ->send();

        $this->myDb->singleRequest()
            ->singleQuery(
                (new DataMySQLQueryBuilder())
                    ->insert('Customers')
                        ->addValue('Age', ':age')
                        ->addValue('FirstName', ':first_name')
                        ->addValue('LastName', ':last_name')
                        ->addValue('NewAddress', ':new_address')
            )
            ->addParameter(':age', '59')
            ->addParameter(':first_name', 'Ardella')
            ->addParameter(':last_name', 'Lummasana')
            ->addParameter(':new_address', 'Drewry Street')
            ->send();

         $this->myDb->singleRequest()
            ->singleQuery(
                (new DataMySQLQueryBuilder())
                    ->insert('Customers')
                        ->addValue('Age', ':age')
                        ->addValue('FirstName', ':first_name')
                        ->addValue('LastName', ':last_name')
                        ->addValue('NewAddress', ':new_address')
            )
            ->setParameters([
                ':age'          =>  35,
                ':first_name'   => 'Marieann',
                ':last_name'    => 'Dikels',
                ':new_address'  => 'Esker Center',
            ])
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

        $this->assertEquals(1, DB::$connector->getTotalConnected());
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
                        ->addValue('Age', '43')
                        ->addValue('FirstName', 'Elvie')
                        ->addValue('LastName', 'Kozey')
                        ->addValue('NewAddress', 'Monique Tunnel West Providenci')
            );

        $this->myDb->transactionalRequests()
            ->addQuery(
                (new DataMySQLQueryBuilder())
                    ->insert('Customers')
                        ->addValue('Age', ':age')
                        ->addValue('FirstName', ':first_name')
                        ->addValue('LastName', ':last_name')
                        ->addValue('NewAddress', ':new_address'),
                parameters: [
                    ':age' => ['22', '34', '56'],
                    ':first_name' => ['Adrian','Rosemary', 'Brad'],
                    ':last_name' => ['Carroll','Prescott', 'Crawford'],
                    ':new_address' => ['Oak Ridge Ln','Walnut Hill Ln', 'E Center St'],
                ]
            )
            ->addQuery(
                (new DataMySQLQueryBuilder())
                    ->select()
                        ->addField('Age')
                        ->addField('FirstName')
                        ->from('Customers')
                            ->where('Id > 4')
            );

        $res1 = $this->myDb->transactionalRequests()->send();

        $this->myDb->singleRequest()
            ->singleQuery(
                (new TableMySQLQueryBuilder())
                    ->truncate('Customers')
            )
            ->send();

        $res2 = $this->myDb->transactionalRequests()->sendWithControlResultByQuery(
            (new DataMySQLQueryBuilder())
                    ->select()
                        ->addField('Age')
                        ->addField('FirstName')
                        ->from('Customers')
                            ->where('Id > 4')
                    ->getQuery()
        );

        $this->assertEquals(1, DB::$connector->getNumConnected());
    }
}

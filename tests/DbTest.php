<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\Tests;

use PDO;
use PDOException;
use PHPUnit\Framework\TestCase;

/**
 * DbTests
 *
 * @package   Tests_DbTests
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class DbTest extends TestCase
{
    /** @test */
    public function test_function_1(): void
    {
        $user = 'mytest';
        $pass = 'mytest';
        $dataBaseName = 'test_db';
        $tableName = 'user';

        try {
            $dbh = new PDO('mysql:host=127.0.0.1;dbname=' . $dataBaseName, $user, $pass);
            foreach ($dbh->query('SELECT * from ' . $tableName) as $row) {
                print_r($row);
            }
            $dbh = null;
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        $this->assertTrue(true);
    }

    /** @test */
    public function test_function_2(): void
    {
        $user = 'mytest';
        $pass = 'mytest';
        $dataBaseName = 'test_db';
        $tableName = 'user';

        try {
            $dbh = new PDO(
                'mysql:host=127.0.0.1;dbname=' . $dataBaseName,
                $user,
                $pass,
                [
                    PDO::ATTR_PERSISTENT => true
                ]
            );
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $dbh->beginTransaction();
            $dbh->exec("INSERT into $tableName (name, last_name, age) values ('Joe', 'Bloggs', 18)");
            $dbh->exec("INSERT into $tableName (name, last_name, age) values ('Bobchenko', 'Kenaki', 32)");
            $dbh->commit();
        } catch (\Exception $e) {
            $is = $dbh->rollBack() ?? null;
            echo "Ошибка: " . $e->getMessage() . PHP_EOL;
            echo "is: " . (string) $is;
        }

        $this->assertEquals(true, true);
    }
}

<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\Tests;

use PHPUnit\Framework\TestCase;
use vadimcontenthunter\MyDB\Exceptions\QueryBuilderException;
use vadimcontenthunter\MyDB\Tests\Fakes\MySqlOperatorOptionsInsertFake;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class MySqlOperatorOptionsInsertTest extends TestCase
{
    protected MySqlOperatorOptionsInsertFake $mySqlOperatorOptionsInsertFake;

    public function setUp(): void
    {
        $this->mySqlOperatorOptionsInsertFake = new MySqlOperatorOptionsInsertFake();
    }

    /**
     * @test
     * @dataProvider providerAddValues
     *
     * @param array<string,string[]> $data
     */
    public function test_addValues_withParameters_shouldAddValuesToParameter(array $data, mixed $expected): void
    {
        foreach ($data as $field => $values) {
            foreach ($values as $key => $value) {
                $this->mySqlOperatorOptionsInsertFake->addValue($field, $value);
            }
        }

        $this->assertEquals($expected, $this->mySqlOperatorOptionsInsertFake->getFieldNamesFake());
    }

    /**
     * @return array<string,mixed>
     */
    public function providerAddValues(): array
    {
        return [
            'test 1' => [
                [
                    'name' => ['Vadim', 'Sasha', 'Oleg'],
                    'last_name' => ['Volkovskyi', 'Karasev', 'Trunevs'],
                    'age' => ['24', '25', '32'],
                ],
                [
                    'name' => ['Oleg'],
                    'last_name' => ['Trunevs'],
                    'age' => ['32'],
                ],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider providerSetValues
     *
     * @param array<string,string[]> $data
     */
    public function test_setValues_withParameters_shouldAddValuesToParameter(array $data, mixed $expected): void
    {
        $this->mySqlOperatorOptionsInsertFake->setValues($data);

        $this->assertEquals($expected, $this->mySqlOperatorOptionsInsertFake->getFieldNamesFake());
    }

    /**
     * @return array<string,mixed>
     */
    public function providerSetValues(): array
    {
        return [
            'test 1' => [
                [
                    'name' => ['Vadim', 'Sasha', 'Oleg'],
                    'last_name' => ['Volkovskyi', 'Karasev', 'Trunevs'],
                    'age' => ['24', '25', '32'],
                ],
                [
                    'name' => ['Vadim', 'Sasha', 'Oleg'],
                    'last_name' => ['Volkovskyi', 'Karasev', 'Trunevs'],
                    'age' => ['24', '25', '32'],
                ],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider providerSetValuesException
     *
     * @param array<mixed> $data
     */
    public function test_setValues_withParameters_shouldReturnAnException(array $data, \Exception $expected): void
    {
        $this->expectException($expected::class);

        $this->mySqlOperatorOptionsInsertFake->setValues($data);
    }

    /**
     * @return array<string,mixed>
     */
    public function providerSetValuesException(): array
    {
        return [
            'test 1' => [
                [
                    'name' => ['Vadim', 'Sasha', 'Oleg', 'Oleg'],
                    'last_name' => ['Volkovskyi', 'Karasev', 'Trunevs'],
                    'age' => ['24', '25', '32'],
                ],
                new QueryBuilderException(),
            ],
        ];
    }

    /**
     * @test
     * @dataProvider providerGetQuery
     * @depends test_addValues_withParameters_shouldAddValuesToParameter
     *
     * @param array<string,string[]> $data
     */
    public function test_getQuery_usingTheAddValuesMethod_shouldReturnASpecificString(array $data, mixed $expected): void
    {
        foreach ($data as $field => $values) {
            foreach ($values as $key => $value) {
                $this->mySqlOperatorOptionsInsertFake->addValue($field, $value);
            }
        }

        $this->assertEquals($expected, $this->mySqlOperatorOptionsInsertFake->getQuery());
    }

    /**
     * @return array<string,mixed>
     */
    public function providerGetQuery(): array
    {
        return [
            'test 1' => [
                [
                    'name' => ['Vadim', 'Sasha', 'Oleg'],
                    'last_name' => ['Volkovskyi', 'Karasev', 'Trunevs'],
                    'age' => ['24', '25', '32'],
                ],
                " (name,last_name,age) VALUES('Oleg','Trunevs',32);",
            ],
        ];
    }
}

<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\Tests;

use PHPUnit\Framework\TestCase;
use vadimcontenthunter\MyDB\Tests\Fakes\MySqlOperatorOptionsUpdateFake;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class MySqlOperatorOptionsUpdateTest extends TestCase
{
    protected MySqlOperatorOptionsUpdateFake $MySqlOperatorOptionsUpdateFake;

    public function setUp(): void
    {
        $this->MySqlOperatorOptionsUpdateFake = new MySqlOperatorOptionsUpdateFake();
    }

    /**
     * @test
     * @dataProvider providerSet
     *
     * @param array<string[]> $data
     */
    public function test_set_withParameters_shouldAddValuesToParameter(
        array $data,
        mixed $expected
    ): void {

        foreach ($data as $id => $parameters) {
            $this->MySqlOperatorOptionsUpdateFake->set(
                $parameters[0],
                $parameters[1],
                $parameters[2] ?? true,
            );
        }

        $this->assertEquals($expected, $this->MySqlOperatorOptionsUpdateFake->getFieldsValuesFake());
    }

    /**
     * @return array<string,mixed>
     */
    public function providerSet(): array
    {
        return [
            'test 1' => [
                [
                    [ 'Manufacturer', 'Samsung Inc.' ],
                    [ 'ProductCount', 'ProductCount + 3', false ],
                ],
                [
                    [ 'Manufacturer' => "'Samsung Inc.'" ],
                    [ 'ProductCount' => 'ProductCount + 3' ],
                ],
            ]
        ];
    }

    /**
     * @test
     * @dataProvider providerGetFieldsValuesSQL
     * @depends test_set_withParameters_shouldAddValuesToParameter
     *
     * @param array<string[]> $data
     */
    public function test_getFieldsValuesSQL_usingTheSetMethod_shouldReturnString(
        array $data,
        mixed $expected
    ): void {

        foreach ($data as $id => $parameters) {
            $this->MySqlOperatorOptionsUpdateFake->set(
                $parameters[0],
                $parameters[1],
                $parameters[2] ?? true,
            );
        }

        $this->assertEquals($expected, $this->MySqlOperatorOptionsUpdateFake->getFieldsValuesSQLFake());
    }

    /**
     * @return array<string,mixed>
     */
    public function providerGetFieldsValuesSQL(): array
    {
        return [
            'test 1' => [
                [
                    [ 'Manufacturer', 'Samsung Inc.' ],
                    [ 'ProductCount', 'ProductCount + 3', false ],
                ],
                " SET Manufacturer='Samsung Inc.',ProductCount=ProductCount + 3"
            ]
        ];
    }
}

<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\Tests;

use PHPUnit\Framework\TestCase;
use vadimcontenthunter\MyDB\Tests\Fakes\MySqlOperatorOptionsSelectFake;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class MySqlOperatorOptionsSelectTest extends TestCase
{
    protected MySqlOperatorOptionsSelectFake $mySqlOperatorOptionsSelectFake;

    public function setUp(): void
    {
        $this->mySqlOperatorOptionsSelectFake = new MySqlOperatorOptionsSelectFake();
    }

    /**
     * @test
     * @dataProvider providerAddField
     *
     * @param array<string[]> $data
     */
    public function test_addField_withParameters_shouldAddValuesToParameter(
        array $data,
        mixed $expected
    ): void {

        foreach ($data as $id => $parameters) {
            $this->mySqlOperatorOptionsSelectFake->addField(
                $parameters[0] ?? '',
                $parameters[1] ?? null,
                $parameters[2] ?? null,
            );
        }

        $this->assertEquals($expected, $this->mySqlOperatorOptionsSelectFake->getFieldNamesFake());
    }

    /**
     * @return array<string,mixed>
     */
    public function providerAddField(): array
    {
        return [
            'test 1' => [
                [
                    [ 'name' ],
                    [ 'last_name'] ,
                    [ 'age' ]
                ],
                [ 'name', 'last_name', 'age' ],
            ],
            'test 2' => [
                [
                    [ 'name' ],
                    [ 'last_name' ],
                    [ 'age' ],
                    [ 'price * productCount', 'totalSum' ],
                ],
                [ 'name', 'last_name', 'age', 'price * productCount AS totalSum' ],
            ],
            'test 3' => [
                [
                    [ 'price * productCount', null, 'AVG' ],
                ],
                [ 'AVG(price * productCount)' ],
            ],
            'test 4' => [
                [
                    [ '*', 'ProdCount', 'COUNT' ],
                    [ 'ProductCount', 'TotalCount', 'SUM' ],
                    [ 'Price', 'MinPrice', 'MIN' ],
                    [ 'Price', 'MaxPrice', 'MAX' ],
                    [ 'Price', 'AvgPrice', 'AVG' ],
                ],
                [
                    'COUNT(*) AS ProdCount',
                    'SUM(ProductCount) AS TotalCount',
                    'MIN(Price) AS MinPrice',
                    'MAX(Price) AS MaxPrice',
                    'AVG(Price) AS AvgPrice'
                ],
            ],
        ];
    }

    /** @test */
    public function test_distinct_withoutParameters_shouldReplaceTheWordInTheString(): void
    {
        $expected = 'SELECT DISTINCT';
        $this->mySqlOperatorOptionsSelectFake->setQuery('select');
        $this->mySqlOperatorOptionsSelectFake->distinct();
        $this->assertEquals($expected, $this->mySqlOperatorOptionsSelectFake->getQuery());
    }
}

<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\Tests;

use PHPUnit\Framework\TestCase;
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
     * @param array<string,string[]|string> $data
     * @param mixed $expected
     */
    public function test_addValues_withParameters_mustAddValuesToParameter(array $data, mixed $expected): void
    {
        foreach ($data as $field => $values) {
            foreach ($values as $key => $value) {
                $this->mySqlOperatorOptionsInsertFake->addValues($field, $value);
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
                    'name' => ['Vadim', 'Sasha', 'Oleg'],
                    'last_name' => ['Volkovskyi', 'Karasev', 'Trunevs'],
                    'age' => ['24', '25', '32'],
                ],
            ],
        ];
    }
}

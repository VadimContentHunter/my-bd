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
        $this->assertTrue(true);
        $this->assertTrue(true);
    }

    /** @test */
    public function test_function_2(): void
    {
        $this->assertTrue(true);
    }
}

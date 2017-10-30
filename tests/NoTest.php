<?php

declare(strict_types=1);

namespace App\Tests;

use PHPUnit\Framework\TestCase;

class NoTest extends TestCase
{
    public function testNothing()
    {
        self::assertEquals(1, '1');
    }
}

<?php

namespace App\Tests\Exception;

use App\Exception\NoCommentsToRaffleException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Exception\NoCommentsToRaffleException
 * @group  todo
 */
class NoCommentsToRaffleExceptionTest extends TestCase
{
    public function testForRaffle(): void
    {
        $this->assertInstanceOf(NoCommentsToRaffleException::class, NoCommentsToRaffleException::forRaffle('123'));
    }
}

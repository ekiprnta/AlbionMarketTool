<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
    public function testTest(): void
    {
        $a = 0;
        $this->assertEquals(0, $a);
    }
}

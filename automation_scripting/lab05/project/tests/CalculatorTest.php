<?php

namespace App\Tests;

use App\Calculator;
use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{
    private Calculator $calc;

    protected function setUp(): void
    {
        $this->calc = new Calculator();
    }

    public function testAdd(): void
    {
        $this->assertSame(5.0, $this->calc->add(2, 3));
    }

    public function testSubtract(): void
    {
        $this->assertSame(-1.0, $this->calc->subtract(2, 3));
    }

    public function testMultiply(): void
    {
        $this->assertSame(6.0, $this->calc->multiply(2, 3));
    }

    public function testDivide(): void
    {
        $this->assertSame(2.0, $this->calc->divide(6, 3));
    }

    public function testDivideByZeroThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->calc->divide(1, 0);
    }
}

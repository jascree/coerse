<?php

declare(strict_types=1);

namespace Jascree\TryCast\Tests;

use Jascree\TryCast\TryCast;
use PHPUnit\Framework\TestCase;
use Stringable;

/**
 * @internal
 */
final class TryCastToIntTest extends TestCase
{
    /**
     * @dataProvider provideValid
     */
    public function testValid(mixed $input, mixed $expected): void
    {
        self::assertSame($expected, TryCast::toInt($input));
    }

    public static function provideValid(): array
    {
        return [
                ['0', 0],
                ['10', 10],
                ['+10', 10],
                ['-10', -10],
                ['9223372036854775807', PHP_INT_MAX],
                ['-9223372036854775808', PHP_INT_MIN],
                [0, 0],
                [10, 10],
                [9223372036854775807, PHP_INT_MAX],
                [-9223372036854775808, PHP_INT_MIN],
                [0.0, 0],
                [10.0, 10],
        ];
    }

    /**
     * @dataProvider provideInvalid
     */
    public function testInvalid(mixed $input): void
    {
        self::assertNull(TryCast::toInt($input));
    }

    public static function provideInvalid(): array
    {
        return [
                [null],
                [true],
                [false],
                [['array']],
                [fopen('php://memory', 'rb')], // resource
                [new \stdClass()],
                [function () {}],
                ['foobar'],
                ['010'],
                ['10.0'],
                ['75e-5'],
                ['31e+7'],
                ['10abc'],
                ['abc10'],
                ['100 '],
                [' 100'],
                [' 100 '],
                ['0x10'],
                [1.5],
                ['1.5'],
                [INF],
                [-INF],
                [NAN],
                [1.844674407371E+19],
                [-1.844674407371E+19],
                ['1.844674407371E+19'],
                ['-1.844674407371E+19'],
                [
                        new class implements Stringable {
                            public function __toString(): string { return '123'; }
                        },
                ],
        ];
    }
}

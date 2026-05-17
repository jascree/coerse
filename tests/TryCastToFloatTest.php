<?php

declare(strict_types=1);

namespace Jascree\TryCast\Tests;

use Jascree\TryCast\TryCast;
use PHPUnit\Framework\TestCase;
use Stringable;

/**
 * @internal
 */
final class TryCastToFloatTest extends TestCase
{
    /**
     * @dataProvider provideValid
     */
    public function testValid(mixed $input, mixed $expected): void
    {
        self::assertSame($expected, TryCast::toFloat($input));
    }

    public function testValidNan(): void
    {
        self::assertNan(TryCast::toFloat(NAN));
    }

    public static function provideValid(): array
    {
        return [
                ['0', 0.0],
                ['10', 10.0],
                ['+10', 10.0],
                ['-10', -10.0],
                ['10.0', 10.0],
                ['75e-5', 0.00075],
                ['31e+7', 310000000.0],
                ['1.5', 1.5],
                ['9223372036854775807', 9223372036854775807.0],
                ['-9223372036854775808', -9223372036854775808.0],
                ['1.844674407371E+19', 1.844674407371E+19],
                ['-1.844674407371E+19', -1.844674407371E+19],
                [0, 0.0],
                [10, 10.0],
                [9223372036854775807, 9223372036854775807.0],
                [-9223372036854775808, -9223372036854775808.0],
                [0.0, 0.0],
                [10.0, 10.0],
                [1.5, 1.5],
                [1.844674407371E+19, 1.844674407371E+19],
                [-1.844674407371E+19, -1.844674407371E+19],
                [INF, INF],
                [-INF, -INF],
        ];
    }

    /**
     * @dataProvider provideInvalid
     */
    public function testInvalid(mixed $input): void
    {
        self::assertNull(TryCast::toFloat($input));
    }

    public static function provideInvalid(): array
    {
        return [
                [null],
                [true],
                [false],
                [['array']],
                [fopen('php://memory', 'rb')],   // resource
                [new \stdClass()],
                [function () {}],
                ['foobar'],
                ['010'],
                ['10abc'],
                ['abc10'],
                ['100 '],
                [' 100'],
                [' 100 '],
                ['0x10'],
                [
                        new class implements Stringable {
                            public function __toString(): string { return '123.5'; }
                        },
                ],
                [new class {}],
        ];
    }
}

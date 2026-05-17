<?php

declare(strict_types=1);

namespace Jascree\TryCast\Tests;

use Jascree\TryCast\TryCast;
use PHPUnit\Framework\TestCase;
use Stringable;

/**
 * @internal
 */
final class TryCastToStringTest extends TestCase
{
    /**
     * @dataProvider provideValid
     */
    public function testValid(mixed $input, mixed $expected): void
    {
        self::assertSame($expected, TryCast::toString($input));
    }

    public static function provideValid(): array
    {
        return [
                ['foobar', 'foobar'],
                ['', ''],
                ['0', '0'],
                ['10', '10'],
                ['010', '010'],
                ['+10', '+10'],
                ['-10', '-10'],
                ['9223372036854775807', '9223372036854775807'],
                ['-9223372036854775808', '-9223372036854775808'],
                ['10.0', '10.0'],
                ['75e-5', '75e-5'],
                ['31e+7', '31e+7'],
                ['1.5', '1.5'],
                ['10abc', '10abc'],
                ['abc10', 'abc10'],
                ['100 ', '100 '],
                [' 100', ' 100'],
                [' 100 ', ' 100 '],
                ['0x10', '0x10'],
                ['1.844674407371E+19', '1.844674407371E+19'],
                ['-1.844674407371E+19', '-1.844674407371E+19'],
                [0, '0'],
                [10, '10'],
                [9223372036854775807, '9223372036854775807'],
                [-9223372036854775808, '-9.2233720368548E+18'],
                [0.0, '0'],
                [10.0, '10'],
                [1.5, '1.5'],
                [INF, 'INF'],
                [-INF, '-INF'],
                [NAN, 'NAN'],
                [1.844674407371E+19, '1.844674407371E+19'],
                [-1.844674407371E+19, '-1.844674407371E+19'],
                [
                        new class implements Stringable {
                            public function __toString(): string { return 'stringable_object'; }
                        },
                        'stringable_object',
                ],
        ];
    }

    /**
     * @dataProvider provideInvalid
     */
    public function testInvalid(mixed $input): void
    {
        self::assertNull(TryCast::toString($input));
    }

    public static function provideInvalid(): array
    {
        return [
                [null],
                [true],
                [false],
                [['array']],
                [\fopen('php://memory', 'rb')],
                [new \stdClass()],
                [new class {}],
                [function () {}],
        ];
    }
}

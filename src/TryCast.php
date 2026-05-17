<?php

declare(strict_types=1);

namespace Jascree\TryCast;

final class TryCast
{
    public static function toString(mixed $value): ?string
    {
        return self::safeString($value) ? (string)$value : null;
    }

    /**
     * @return non-empty-string|null
     */
    public static function toNonEmptyString(mixed $value): ?string
    {
        $casted = self::toString($value);
        return $casted !== null && $casted !== '' ? $casted : null;
    }

    public static function toInt(mixed $value): ?int
    {
        return self::safeInt($value) ? (int)$value : null;
    }

    /**
     * @return positive-int|null
     */
    public static function toPositiveInt(mixed $value): ?int
    {
        $casted = self::toInt($value);
        return $casted !== null && $casted > 0 ? $casted : null;
    }

    /**
     * @return non-negative-int|null
     */
    public static function toNonNegativeInt(mixed $value): ?int
    {
        $casted = self::toInt($value);
        return $casted !== null && $casted >= 0 ? $casted : null;
    }

    /**
     * @return negative-int|null
     */
    public static function toNegativeInt(mixed $value): ?int
    {
        $casted = self::toInt($value);
        return $casted !== null && $casted < 0 ? $casted : null;
    }

    /**
     * @return non-positive-int|null
     */
    public static function toNonPositiveInt(mixed $value): ?int
    {
        $casted = self::toInt($value);
        return $casted !== null && $casted <= 0 ? $casted : null;
    }

    public static function toFloat(mixed $value): ?float
    {
        return self::safeFloat($value) ? (float)$value : null;
    }

    /**
     * @return list<int>|null
     */
    public static function toListInt(mixed $value): ?array
    {
        if (!\is_array($value)) {
            return null;
        }

        return \array_values(
                \array_filter(
                        \array_map(self::toInt(...), $value),
                        static fn(int|null $v): bool => $v !== null,
                ),
        );
    }

    /**
     * @return list<string>|null
     */
    public static function toListString(mixed $value): ?array
    {
        if (!\is_array($value)) {
            return null;
        }

        return \array_values(
                \array_filter(
                        \array_map(self::toString(...), $value),
                        static fn(string|null $v): bool => $v !== null,
                ),
        );
    }

    /**
     * @return list<float>|null
     */
    public static function toListFloat(mixed $value): ?array
    {
        if (!\is_array($value)) {
            return null;
        }

        return \array_values(
                \array_filter(
                        \array_map(self::toFloat(...), $value),
                        static fn(float|null $v): bool => $v !== null,
                ),
        );
    }

    private static function safeString(mixed $value): bool
    {
        return match (\gettype($value)) {
            'string', 'integer', 'double' => true,
            'object' => \method_exists($value, '__toString'),
            default => false,
        };
    }

    private static function safeInt(mixed $value): bool
    {
        switch (\gettype($value)) {
            case 'integer':
                return true;
            case 'double':
                return $value === (float)(int)$value;
            case 'string':
                $losslessCast = (string)(int)$value;

                if ($value !== $losslessCast && $value !== "+$losslessCast") {
                    return false;
                }

                return $value <= PHP_INT_MAX && $value >= PHP_INT_MIN;
            default:
                return false;
        }
    }

    private static function safeFloat(mixed $value): bool
    {
        switch (\gettype($value)) {
            case 'integer':
            case 'double':
                return true;
            case 'string':
                if ($value === '') {
                    return false;
                }

                if (\strlen($value) > 1 && $value[0] === '0' && $value[1] !== '.') {
                    return false;
                }

                $filtered = \filter_var(
                        $value,
                        FILTER_SANITIZE_NUMBER_FLOAT,
                        FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_SCIENTIFIC,
                );

                return ($filtered === $value);
            default:
                return false;
        }
    }
}

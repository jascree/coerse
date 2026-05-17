# TryCast

A simple, zero-dependency PHP utility for safe type coercion.

Instead of throwing exceptions on type mismatch, `TryCast` returns `null` – perfect for normalizing data from unreliable
sources like HTTP requests, JSON payloads, database results, or user input.

## Requirements

- PHP 8.1+

## Installation

```bash
composer require jascree/try-cast
```

# References

* [PHP RFC: Safe Casting Functions](https://wiki.php.net/rfc/safe_cast)

## Usage

```php
use Jascree\TryCast\TryCast;

TryCast::toString('hello');        // 'hello'
TryCast::toString(123);            // '123'
TryCast::toString(3.14);           // '3.14'
TryCast::toString(null);           // null
TryCast::toString(['array']);      // null

TryCast::toNonEmptyString('text'); // 'text'
TryCast::toNonEmptyString('');     // null
TryCast::toNonEmptyString(0);      // null

TryCast::toInt(42);                // 42
TryCast::toInt('42');              // 42
TryCast::toInt('+42');             // 42
TryCast::toInt('-42');             // -42
TryCast::toInt('42.0');            // null
TryCast::toInt('42abc');           // null
TryCast::toInt(42.0);              // 42
TryCast::toInt(42.5);              // null
TryCast::toInt(INF);               // null

TryCast::toPositiveInt(5);         // 5
TryCast::toPositiveInt(0);         // null
TryCast::toPositiveInt(-5);        // null

TryCast::toNonNegativeInt(5);      // 5
TryCast::toNonNegativeInt(0);      // 0
TryCast::toNonNegativeInt(-5);     // null

TryCast::toNegativeInt(-5);        // -5
TryCast::toNegativeInt(0);         // null

TryCast::toNonPositiveInt(-5);     // -5
TryCast::toNonPositiveInt(0);      // 0
TryCast::toNonPositiveInt(5);      // null

TryCast::toFloat(3.14);            // 3.14
TryCast::toFloat('3.14');          // 3.14
TryCast::toFloat('75e-5');         // 0.00075
TryCast::toFloat('10.0');          // 10.0
TryCast::toFloat('010');           // null
TryCast::toFloat('10abc');         // null
TryCast::toFloat(INF);             // INF
TryCast::toFloat(NAN);             // NAN

TryCast::toListInt(['1', 'a', 2, null, 3.0]);          // [1, 2, 3]
TryCast::toListInt('not array');                       // null
TryCast::toListString([1, 'hello', true, null, 3.14]); // ['1', 'hello', '3.14']
TryCast::toListFloat(['3.14', 'abc', 2, INF]);         // [3.14, 2.0, INF]
```

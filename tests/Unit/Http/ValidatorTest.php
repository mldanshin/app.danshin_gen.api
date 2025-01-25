<?php

namespace Tests\Unit\Http;

use App\Http\Validator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    #[DataProvider('requireIntegerOrNullSuccessProvider')]
    public function test_require_integer_or_null_success($string): void
    {
        $this->assertTrue(Validator::requireIntegerOrNull($string));
    }

    /**
     * @return array[]
     */
    public static function requireIntegerOrNullSuccessProvider(): array
    {
        return [
            [null],
            [1],
            [34],
            [19994],
            ['34'],
        ];
    }

    #[DataProvider('requireIntegerOrNullWrongProvider')]
    public function test_require_integer_or_null_wrong($string): void
    {
        $this->assertFalse(Validator::requireIntegerOrNull($string));
    }

    /**
     * @return array[]
     */
    public static function requireIntegerOrNullWrongProvider(): array
    {
        return [
            ['hello'],
        ];
    }

    #[DataProvider('requireIntegerSuccessProvider')]
    public function test_require_integer_success($string): void
    {
        $this->assertTrue(Validator::requireInteger($string));
    }

    /**
     * @return array[]
     */
    public static function requireIntegerSuccessProvider(): array
    {
        return [
            [1],
            [34],
            [19994],
            ['34'],
        ];
    }

    #[DataProvider('requireIntegerWrongProvider')]
    public function test_require_integer_wrong($string): void
    {
        $this->assertFalse(Validator::requireInteger($string));
    }

    /**
     * @return array[]
     */
    public static function requireIntegerWrongProvider(): array
    {
        return [
            ['hello'],
        ];
    }
}

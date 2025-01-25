<?php

namespace Tests\Unit\Models;

use App\Exceptions\DateException;
use App\Models\Date;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class DateTest extends TestCase
{
    #[DataProvider('createObjectSuccessProvider')]
    public function test_create_object_success(?string $year, ?string $month, ?string $day): void
    {
        new Date($year, $month, $day);
        $this->assertTrue(true);
    }

    /**
     * @return array[]
     */
    public static function createObjectSuccessProvider(): array
    {
        return [
            ['2023', '12', '01'],
            ['2023', '12', '31'],
            ['2023', '12', '3?'],
            ['2023', '12', '?9'],
            ['2023', '01', '01'],
            ['2023', '?9', '01'],
            ['2023', '1?', '01'],
            ['2023', null, null],
            [null, '12', '01'],
            [null, null, null],
            ['2?23', '12', '01'],
            ['????', '??', '??'],
            ['????', '02', '29'],
            ['????', '11', '30'],
        ];
    }

    #[DataProvider('createObjectWrongProvider')]
    public function test_create_object_wrong(?string $year, ?string $month, ?string $day): void
    {
        $this->expectException(DateException::class);
        new Date($year, $month, $day);
    }

    /**
     * @return array[]
     */
    public static function createObjectWrongProvider(): array
    {
        return [
            // count wrong
            ['20234', '12', '01'],
            ['2023', '2', '01'],
            ['2023', '12', '1'],
            ['2023', '212', '13'],

            // month year
            ['202D', '10', '13'],
            ['20D3', '10', '13'],
            ['2D23', '10', '13'],
            ['D023', '10', '13'],

            // month wrong
            ['2023', '90', '13'],
            ['2023', '1F', '13'],
            ['2023', '1F', '13'],
            ['2023', '00', '13'],
            ['2023', '2?', '13'],

            // day wrong
            ['2023', '10', '43'],
            ['2023', '10', '3F'],
            ['2023', '10', 'A3'],
            ['2023', '10', '00'],
            ['2023', '10', '32'],
            ['2023', '10', '4?'],

            // date wrong
            ['2023', '11', '31'],
            ['2023', '02', '29'],
            ['????', '02', '30'],
            ['????', '11', '31'],
            [null, '02', '30'],
            [null, '11', '31'],
        ];
    }

    #[DataProvider('decodeSuccessProvider')]
    public function test_decode_success(?string $text, ?Date $expected): void
    {
        $this->assertEquals($expected, Date::decode($text));
    }

    /**
     * @return array[]
     */
    public static function decodeSuccessProvider(): array
    {
        return [
            [null, null],
            ['', new Date(null, null, null)],
            ['2023-07-13', new Date('2023', '07', '13')],
            ['????-07-13', new Date(null, '07', '13')],
            ['2023-??-13', new Date('2023', null, '13')],
            ['2023-07-??', new Date('2023', '07', null)],
            ['20?3-07-0?', new Date('20?3', '07', '0?')],
        ];
    }

    #[DataProvider('decodeWrongProvider')]
    public function test_decode_wrong(?string $text): void
    {
        $this->expectException(DateException::class);
        Date::decode($text);
    }

    /**
     * @return array[]
     */
    public static function decodeWrongProvider(): array
    {
        return [
            ['123333'],
            ['2020-04'],
            ['2020-04-'],
            ['200-04-01'],
            ['2020-4-01'],
            ['2020-04-1'],
        ];
    }

    #[DataProvider('encodeProvider')]
    public function test_encode(?Date $date, ?string $expected): void
    {
        $this->assertEquals($expected, Date::encode($date));
    }

    /**
     * @return array[]
     */
    public static function encodeProvider(): array
    {
        return [
            [null, null],
            [new Date(null, null, null), ''],
            [new Date('????', '??', '??'), ''],
            [new Date('2020', null, null), '2020-??-??'],
            [new Date(null, '07', null), '????-07-??'],
            [new Date(null, null, '1?'), '????-??-1?'],
            [new Date('2023', '11', '10'), '2023-11-10'],
        ];
    }

    #[DataProvider('chechSuccessTextProvider')]
    public function test_check_text_success(string $text): void
    {
        $this->assertTrue(Date::checkText($text));
    }

    /**
     * @return array[]
     */
    public static function chechSuccessTextProvider(): array
    {
        return [
            ['2023-12-01'],
            ['2023-12-31'],
            ['2023-12-3?'],
            ['2023-12-?9'],
            ['2023-01-01'],
            ['2023-?9-01'],
            ['2023-1?-01'],
            ['2023-??-??'],
            ['????-12-01'],
            ['????-??-??'],
            ['2?23-12-01'],
            ['????-??-??'],
            ['????-02-29'],
            ['????-11-30'],
        ];
    }

    #[DataProvider('checkWrongTextProvider')]
    public function testcheck_text_wrong(string $text): void
    {
        $this->assertFalse(Date::checkText($text));
    }

    /**
     * @return array[]
     */
    public static function checkWrongTextProvider(): array
    {
        return [
            // count wrong
            ['20234-12-01'],
            ['2023-2-01'],
            ['2023-12-1'],
            ['2023-212-13'],
            ['2023'],
            ['12'],
            ['2023-12'],
            ['23-12'],

            // month year
            ['202D-10-13'],
            ['20D3-10-13'],
            ['2D23-10-13'],
            ['D023-10-13'],

            // month wrong
            ['2023-90-13'],
            ['2023-1F-13'],
            ['2023-1F-13'],
            ['2023-00-13'],
            ['2023-2?-13'],

            // day wrong
            ['2023-10-43'],
            ['2023-10-3F'],
            ['2023-10-A3'],
            ['2023-10-00'],
            ['2023-10-32'],
            ['2023-10-4?'],

            // date wrong
            ['2023-11-31'],
            ['2023-02-29'],
            ['????-02-30'],
            ['????-11-31'],
        ];
    }

    #[DataProvider('chechSuccessProvider')]
    public function test_check_success(?string $year, ?string $month, ?string $day): void
    {
        $this->assertTrue(Date::check($year, $month, $day));
    }

    /**
     * @return array[]
     */
    public static function chechSuccessProvider(): array
    {
        return [
            ['2023', '12', '01'],
            ['2023', '12', '31'],
            ['2023', '12', '3?'],
            ['2023', '12', '?9'],
            ['2023', '01', '01'],
            ['2023', '?9', '01'],
            ['2023', '1?', '01'],
            ['2023', null, null],
            [null, '12', '01'],
            [null, null, null],
            ['2?23', '12', '01'],
            ['????', '??', '??'],
            ['????', '02', '29'],
            ['????', '11', '30'],
        ];
    }

    #[DataProvider('checkWrongProvider')]
    public function testcheck_wrong(?string $year, ?string $month, ?string $day): void
    {
        $this->assertFalse(Date::check($year, $month, $day));
    }

    /**
     * @return array[]
     */
    public static function checkWrongProvider(): array
    {
        return [
            // count wrong
            ['20234', '12', '01'],
            ['2023', '2', '01'],
            ['2023', '12', '1'],
            ['2023', '212', '13'],

            // month year
            ['202D', '10', '13'],
            ['20D3', '10', '13'],
            ['2D23', '10', '13'],
            ['D023', '10', '13'],

            // month wrong
            ['2023', '90', '13'],
            ['2023', '1F', '13'],
            ['2023', '1F', '13'],
            ['2023', '00', '13'],
            ['2023', '2?', '13'],

            // day wrong
            ['2023', '10', '43'],
            ['2023', '10', '3F'],
            ['2023', '10', 'A3'],
            ['2023', '10', '00'],
            ['2023', '10', '32'],
            ['2023', '10', '4?'],

            // date wrong
            ['2023', '11', '31'],
            ['2023', '02', '29'],
            ['????', '02', '30'],
            ['????', '11', '31'],
            [null, '02', '30'],
            [null, '11', '31'],
        ];
    }

    #[DataProvider('hasUnknownProvider')]
    public function test_has_unknown(?string $year, ?string $month, ?string $day, bool $expected): void
    {
        $this->assertEquals(
            $expected,
            (new Date($year, $month, $day))->hasUnknown
        );
    }

    /**
     * @return array[]
     */
    public static function hasUnknownProvider(): array
    {
        return [
            ['2023', '12', '01', false],
            [null, '12', '01', true],
            ['2023', null, '01', true],
            ['2023', '12', null, true],
            ['202?', '12', '01', true],
            ['2023', '1?', '01', true],
            ['2023', '12', '0?', true],
        ];
    }

    #[DataProvider('isEmptyProvider')]
    public function test_is_empty(?string $year, ?string $month, ?string $day, bool $expected): void
    {
        $this->assertEquals(
            $expected,
            (new Date($year, $month, $day))->isEmpty
        );
    }

    /**
     * @return array[]
     */
    public static function isEmptyProvider(): array
    {
        return [
            [null, null, null, true],
            ['2023', null, '01', false],
            ['2023', '12', null, false],
            ['2023', '12', '0?', false],
        ];
    }

    #[DataProvider('stringProvider')]
    public function test_string(?string $year, ?string $month, ?string $day, ?string $expected): void
    {
        $this->assertEquals(
            $expected,
            (new Date($year, $month, $day))->string
        );
    }

    /**
     * @return array[]
     */
    public static function stringProvider(): array
    {
        return [
            ['2023', '12', '01', '2023-12-01'],
            [null, '12', '01', '????-12-01'],
            ['2023', null, '01', '2023-??-01'],
            ['2023', '12', null, '2023-12-??'],
            ['202?', '12', '01', '202?-12-01'],
            [null, null, null, null],
        ];
    }
}

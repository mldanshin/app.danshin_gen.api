<?php

namespace Tests\Feature\Helpers;

use App\Helpers\Date as DateHelper;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class DateTest extends TestCase
{
    #[DataProvider('birthProvider')]
    public function test_birth(
        string $expected,
        ?string $actualDate,
        ?\DateInterval $actualAge,
        bool $actualIsLife
    ): void {
        $this->assertMatchesRegularExpression($expected, DateHelper::getBirth($actualDate, $actualAge, $actualIsLife));
    }

    /**
     * @return array[]
     */
    public static function birthProvider(): array
    {
        return [
            ['#01.12.2020#', '2020-12-01', null, false],
            ['##', null, null, true],
            ["#01.12.2020 \(2 .+\)#", '2020-12-01', new \DateInterval('P2Y'), true],
            ["#01.12.2020 \(1 .+\)#", '2020-12-01', new \DateInterval('P0Y1M'), true],
            ["#01.12.2020 \(20 .+\)#", '2020-12-01', new \DateInterval('P0Y0M20D'), true],
            ['#01.12.2020#', '2020-12-01', new \DateInterval('P0Y0M20D'), false],
        ];
    }

    #[DataProvider('deathProvider')]
    public function test_death(
        string $expected,
        ?string $actualDate,
        ?\DateInterval $actualAge,
        ?\DateInterval $actualIntervalDeath
    ): void {
        $this->assertMatchesRegularExpression(
            $expected,
            DateHelper::getDeath($actualDate, $actualAge, $actualIntervalDeath)
        );
    }

    /**
     * @return array[]
     */
    public static function deathProvider(): array
    {
        return [
            ['#01.12.2020#', '2020-12-01', null, null],
            ['##', null, new \DateInterval('P2Y'), new \DateInterval('P12Y')],
            ["#01.12.2020 \(2 .+ 25 .+\)#", '2020-12-01', new \DateInterval('P25Y'), new \DateInterval('P2Y')],
            ["#01.12.2020 \(1 .+\)#", '2020-12-01', null, new \DateInterval('P0Y1M')],
        ];
    }

    #[DataProvider('periodLiveProvider')]
    public function test_period_live(string $expected, ?string $actualBirthDate, ?string $actualDeathDate): void
    {
        $this->assertEquals(
            $expected,
            DateHelper::periodLive($actualBirthDate, $actualDeathDate)
        );
    }

    /**
     * @return array[]
     */
    public static function periodLiveProvider(): array
    {
        return [
            ['(?)', '', null],
            ['(?)', null, null],
            ['(?)', '', null],
            ['(?-?)', '', ''],
            ['(01.01.2000-?)', '2000-01-01', ''],
            ['(01.01.2000-01.??.2020)', '2000-01-01', '2020-??-01'],
            ['(?-01.01.2020)', '', '2020-01-01'],
            ['(01.01.????-??.10.2020)', '????-01-01', '2020-10-??'],
        ];
    }

    #[DataProvider('formatSuccessProvider')]
    public function test_format_success($expected, $actual): void
    {
        $this->assertEquals($expected, DateHelper::format($actual));
    }

    /**
     * @return array[]
     */
    public static function formatSuccessProvider(): array
    {
        return [
            [null, ''],
            ['', ''],
            ['09.01.2000', '2000-01-09'],
            ['09.01.????', '????-01-09'],
        ];
    }

    #[DataProvider('formatWrongProvider')]
    public function test_format_wrong($actual): void
    {
        $this->expectException(\Exception::class);
        DateHelper::format($actual);
    }

    /**
     * @return array[]
     */
    public static function formatWrongProvider(): array
    {
        return [
            ['null'],
            ['blabla'],
            ['2000-01-9'],
            ['????-09'],
            ['????.09.01'],
        ];
    }

    public function test_date_interval_success(): void
    {
        $arrayData = [
            ['60 '.__('date.year.plural'), 'P60Y20D'],
            ['1 '.__('date.year.nominative'), 'P1Y6M'],
            ['2 '.__('date.year.accusative'), 'P2Y6M'],
            ['113 '.__('date.year.plural'), 'P113Y'],
            ['121 '.__('date.year.nominative'), 'P121Y'],
            ['521 '.__('date.year.nominative'), 'P521Y'],
            ['111121 '.__('date.year.nominative'), 'P111121Y'],
            ['6 '.__('date.month.plural'), 'P6M2D'],
            ['1 '.__('date.month.nominative'), 'P1M2D'],
            ['3 '.__('date.month.accusative'), 'P3M2D'],
            ['3 '.__('date.day.accusative'), 'P3D'],
            ['1 '.__('date.day.nominative'), 'P1D'],
            ['20 '.__('date.day.plural'), 'P20D'],
        ];

        foreach ($arrayData as $item) {
            $this->assertEquals($item[0], DateHelper::dateInterval(new \DateInterval($item[1])));
        }
    }
}

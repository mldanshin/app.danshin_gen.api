<?php

namespace Tests\Unit\Models;

use App\Models\CalculatorDateInterval as CalculatorDateIntervalModel;
use App\Models\Date;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class CalculatorDateIntervalTest extends TestCase
{
    #[DataProvider('createSuccessProvider')]
    public function test_create_success(
        \DateTime $today,
        ?Date $birthDate,
        ?Date $deathDate,
        ?\DateInterval $expectedAge,
        ?\DateInterval $expectedIntervalBirth,
        ?\DateInterval $expectedIntervalDeath,
    ): void {
        $model = new CalculatorDateIntervalModel($today, $birthDate, $deathDate);

        $this->assertTrue($this->assertEqualsDateInterval($expectedAge, $model->age));
        $this->assertTrue($this->assertEqualsDateInterval($expectedIntervalBirth, $model->intervalBirth));
        $this->assertTrue($this->assertEqualsDateInterval($expectedIntervalDeath, $model->intervalDeath));
    }

    public static function createSuccessProvider(): array
    {
        return [
            [
                new \DateTime('2021-10-15'),
                null,
                null,
                null,
                null,
                null,
            ],
            [
                new \DateTime('2021-10-15'),
                new Date(null, null, null),
                null,
                null,
                null,
                null,
            ],
            [
                new \DateTime('2021-10-15'),
                new Date(null, null, null),
                null,
                null,
                null,
                null,
            ],
            [
                new \DateTime('2021-10-15'),
                new Date('2000', '??', '10'),
                new Date(null, null, null),
                null,
                null,
                null,
            ],
            [
                new \DateTime('2021-10-15'),
                new Date('2000', '12', '15'),
                new Date('????', '10', '01'),
                null,
                new \DateInterval('P20Y10M'),
                null,
            ],
            [
                new \DateTime('2021-10-15'),
                new Date('2000', '??', '15'),
                new Date('????', '10', '01'),
                null,
                null,
                null,
            ],
            [
                new \DateTime('2021-10-15'),
                new Date('2000', '10', '10'),
                new Date(null, null, null),
                null,
                new \DateInterval('P21Y5D'),
                null,
            ],
            [
                new \DateTime('2021-10-15'),
                new Date('2000', '10', '10'),
                new Date('2020', '10', '11'),
                new \DateInterval('P20Y1D'),
                new \DateInterval('P21Y5D'),
                new \DateInterval('P1Y4D'),
            ],
            [
                new \DateTime('2021-10-15'),
                new Date(null, null, null),
                new Date('2020', '10', '11'),
                null,
                null,
                new \DateInterval('P1Y4D'),
            ],
        ];
    }

    #[DataProvider('createWrongProvider')]
    public function test_create_wrong(
        \DateTime $today,
        ?Date $birthDate,
        ?Date $deathDate
    ): void {
        $this->expectException(\Exception::class);

        new CalculatorDateIntervalModel($today, $birthDate, $deathDate);
    }

    public static function createWrongProvider(): array
    {
        return [
            [new \DateTime('2021-10-15'), new Date('2022', '01', '01'), null],
            [new \DateTime('2021-10-15'), new Date('2022', '01', '01'), new Date('2023', '01', '01')],
            [new \DateTime('2021-10-15'), new Date(null, null, null), new Date('2023', '01', '01')],
            [new \DateTime('2021-10-15'), new Date('2023', '01', '01'), new Date('2022', '01', '01')],
        ];
    }

    private function assertEqualsDateInterval(?\DateInterval $interval1, ?\DateInterval $interval2): bool
    {
        if ($interval1 === null && $interval2 === null) {
            return true;
        }

        $seconds1 = $interval1->y * 31536000
            + $interval1->m * 2592000
            + $interval1->d * 86400
            + $interval1->h * 3600
            + $interval1->i * 60
            + $interval1->s;

        $seconds2 = $interval2->y * 31536000
             + $interval2->m * 2592000
             + $interval2->d * 86400
             + $interval2->h * 3600
             + $interval2->i * 60
             + $interval2->s;

        if ($seconds1 === $seconds2) {
            return true;
        } else {
            return false;
        }
    }
}

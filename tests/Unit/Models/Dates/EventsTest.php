<?php

namespace Tests\Unit\Models\Dates;

use App\Models\Date as DateModel;
use App\Models\Dates\Birth as BirthModel;
use App\Models\Dates\Events as EventsModel;
use App\Models\Dates\Person as PersonModel;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class EventsTest extends TestCase
{
    public function test_empty_true(): void
    {
        $model = new EventsModel(
            collect(),
            collect(),
            collect(),
            collect(),
            collect(),
            collect(),
            collect(),
            collect(),
            collect()
        );
        $this->assertTrue($model->isEmpty);
    }

    #[DataProvider('emptyFalseProvider')]
    public function test_empty_false(
        Collection $pastBirth,
        Collection $pastBirthWould,
        Collection $pastDeath,
        Collection $todayBirth,
        Collection $todayBirthWould,
        Collection $todayDeath,
        Collection $nearestBirth,
        Collection $nearestBirthWould,
        Collection $nearestDeath
    ): void {
        $model = new EventsModel(
            $pastBirth,
            $pastBirthWould,
            $pastDeath,
            $todayBirth,
            $todayBirthWould,
            $todayDeath,
            $nearestBirth,
            $nearestBirthWould,
            $nearestDeath
        );
        $this->assertFalse($model->isEmpty);
    }

    public static function emptyFalseProvider(): array
    {
        return [
            [
                collect(
                    new BirthModel(
                        new DateModel('2022', '01', '10'),
                        new PersonModel(1, 'Surname', collect(), null, null),
                        new \DateInterval('P7D')
                    )
                ),
                collect(),
                collect(),
                collect(),
                collect(),
                collect(),
                collect(),
                collect(),
                collect(),
            ],
        ];
    }
}

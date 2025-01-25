<?php

namespace Tests\Feature\Repositories\Dates;

use App\Models\Eloquent\People as PeopleEloquentModel;
use App\Repositories\Dates\Dirty as DirtyRepository;
use Database\Seeders\GenderSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

final class DirtyTest extends TestCase
{
    use RefreshDatabase;

    #[DataProvider('createSuccessProvider')]
    public function test_create_success(int $beforeDay, \DateTime $today, int $afterDay): void
    {
        $this->seed();

        $repository = new DirtyRepository(
            $beforeDay,
            $today,
            $afterDay
        );
        $this->assertInstanceOf(DirtyRepository::class, $repository);
    }

    /**
     * @return array[]
     */
    public static function createSuccessProvider(): array
    {
        return [
            [3, new \DateTime, 3],
            [30, new \DateTime, 30],
        ];
    }

    #[DataProvider('createWrongProvider')]
    public function test_create_wrong(int $beforeDay, \DateTime $today, int $afterDay): void
    {
        $this->seed();

        $this->expectException(\Exception::class);

        new DirtyRepository(
            $beforeDay,
            $today,
            $afterDay
        );
    }

    /**
     * @return array[]
     */
    public static function createWrongProvider(): array
    {
        return [
            [-3, new \DateTime, 3],
            [35, new \DateTime, 30],
            [3, new \DateTime, 0],
        ];
    }

    #[DataProvider('beforeBirthProvider')]
    public function test_before_birth(int $beforeDay, \DateTime $today, int $afterDay, int $expected): void
    {
        $this->seedPeopleBirth();

        $repository = new DirtyRepository(
            $beforeDay,
            $today,
            $afterDay
        );

        $this->assertEquals($expected, $repository->getBeforeBirth()->count());
    }

    /**
     * @return array[]
     */
    public static function beforeBirthProvider(): array
    {
        return [
            [
                3,
                new \DateTime('2021-10-15'),
                3,
                1,
            ],
            [
                3,
                new \DateTime('2021-10-15'),
                7,
                3,
            ],
            [
                3,
                new \DateTime('2021-01-02'),
                7,
                2,
            ],
        ];
    }

    #[DataProvider('beforeDeathProvider')]
    public function test_before_death(int $beforeDay, \DateTime $today, int $afterDay, int $expected): void
    {
        $this->seedPeopleDeath();

        $repository = new DirtyRepository(
            $beforeDay,
            $today,
            $afterDay
        );

        $this->assertEquals($expected, $repository->getBeforeDeath()->count());
    }

    /**
     * @return array[]
     */
    public static function beforeDeathProvider(): array
    {
        return [
            [
                3,
                new \DateTime('2021-10-15'),
                3,
                1,
            ],
            [
                3,
                new \DateTime('2021-10-15'),
                7,
                3,
            ],
            [
                3,
                new \DateTime('2021-01-02'),
                7,
                2,
            ],
        ];
    }

    #[DataProvider('todayBirthProvider')]
    public function test_today_birth(int $beforeDay, \DateTime $today, int $afterDay, int $expected): void
    {
        $this->seedPeopleBirth();

        $repository = new DirtyRepository(
            $beforeDay,
            $today,
            $afterDay
        );

        $this->assertEquals($expected, $repository->getTodayBirth()->count());
    }

    /**
     * @return array[]
     */
    public static function todayBirthProvider(): array
    {
        return [
            [
                3,
                new \DateTime('2021-10-15'),
                3,
                2,
            ],
            [
                7,
                new \DateTime('2021-10-10'),
                3,
                0,
            ],
            [
                7,
                new \DateTime('2021-01-02'),
                3,
                0,
            ],
        ];
    }

    #[DataProvider('todayDeathProvider')]
    public function test_today_death(int $beforeDay, \DateTime $today, int $afterDay, int $expected): void
    {
        $this->seedPeopleDeath();

        $repository = new DirtyRepository(
            $beforeDay,
            $today,
            $afterDay
        );

        $this->assertEquals($expected, $repository->getTodayDeath()->count());
    }

    /**
     * @return array[]
     */
    public static function todayDeathProvider(): array
    {
        return [
            [
                3,
                new \DateTime('2021-10-15'),
                3,
                2,
            ],
            [
                7,
                new \DateTime('2021-10-10'),
                3,
                0,
            ],
            [
                7,
                new \DateTime('2021-01-02'),
                3,
                0,
            ],
        ];
    }

    #[DataProvider('afterBirthProvider')]
    public function test_after_birth(int $beforeDay, \DateTime $today, int $afterDay, int $expected): void
    {
        $this->seedPeopleBirth();

        $repository = new DirtyRepository(
            $beforeDay,
            $today,
            $afterDay
        );

        $this->assertEquals($expected, $repository->getAfterBirth()->count());
    }

    /**
     * @return array[]
     */
    public static function afterBirthProvider(): array
    {
        return [
            [
                1,
                new \DateTime('2021-10-15'),
                3,
                0,
            ],
            [
                3,
                new \DateTime('1999-10-15'),
                7,
                1,
            ],
            [
                3,
                new \DateTime('1999-12-27'),
                7,
                1,
            ],
            [
                10,
                new \DateTime('1999-12-27'),
                7,
                3,
            ],
        ];
    }

    #[DataProvider('afterDeathProvider')]
    public function test_after_death(int $beforeDay, \DateTime $today, int $afterDay, int $expected): void
    {
        $this->seedPeopleDeath();

        $repository = new DirtyRepository(
            $beforeDay,
            $today,
            $afterDay
        );

        $this->assertEquals($expected, $repository->getAfterDeath()->count());
    }

    /**
     * @return array[]
     */
    public static function afterDeathProvider(): array
    {
        return [
            [
                10,
                new \DateTime('2021-10-15'),
                3,
                2,
            ],
            [
                1,
                new \DateTime('2021-10-15'),
                7,
                0,
            ],
            [
                3,
                new \DateTime('1999-12-27'),
                7,
                1,
            ],
            [
                10,
                new \DateTime('1999-12-27'),
                7,
                3,
            ],
        ];
    }

    private function seedPeopleBirth(): void
    {
        (new GenderSeeder)->run();
        PeopleEloquentModel::factory()->create(['is_unavailable' => 0, 'birth_date' => '2010-10-09']);
        PeopleEloquentModel::factory()->create(['is_unavailable' => 1, 'birth_date' => '2010-10-09']);
        PeopleEloquentModel::factory()->create(['is_unavailable' => 0, 'birth_date' => '2010-10-13']);
        PeopleEloquentModel::factory()->create(['is_unavailable' => 0, 'birth_date' => '2010-10-01']);
        PeopleEloquentModel::factory()->create(['is_unavailable' => 0, 'birth_date' => '2020-12-01']);
        PeopleEloquentModel::factory()->create(['is_unavailable' => 0, 'birth_date' => '2020-10-17']);
        PeopleEloquentModel::factory()->create(['is_unavailable' => 0, 'birth_date' => '2020-10-22']);
        PeopleEloquentModel::factory()->create(['is_unavailable' => 0, 'birth_date' => '2020-10-15']);
        PeopleEloquentModel::factory()->create(['is_unavailable' => 1, 'birth_date' => '2020-01-01']);
        PeopleEloquentModel::factory()->create(['is_unavailable' => 0, 'birth_date' => '????-10-10']);
        PeopleEloquentModel::factory()->create(['is_unavailable' => 0, 'birth_date' => '2020-10-??']);
        PeopleEloquentModel::factory()->create(['is_unavailable' => 0, 'birth_date' => '']);
        PeopleEloquentModel::factory()->create(['is_unavailable' => 0, 'birth_date' => '2020-10-15']);
        PeopleEloquentModel::factory()->create(['is_unavailable' => 0, 'birth_date' => '????-10-17']);
        PeopleEloquentModel::factory()->create(['is_unavailable' => 0, 'birth_date' => '1979-12-28']);
        PeopleEloquentModel::factory()->create(['is_unavailable' => 0, 'birth_date' => '1979-12-13']);
        PeopleEloquentModel::factory()->create(['is_unavailable' => 0, 'birth_date' => '1988-01-04']);
        PeopleEloquentModel::factory()->create(['is_unavailable' => 0, 'birth_date' => '1988-01-08']);
        PeopleEloquentModel::factory()->create(['is_unavailable' => 0, 'birth_date' => '????-01-01']);
    }

    private function seedPeopleDeath(): void
    {
        (new GenderSeeder)->run();
        PeopleEloquentModel::factory()->create(['is_unavailable' => 0, 'death_date' => '2010-10-09']);
        PeopleEloquentModel::factory()->create(['is_unavailable' => 0, 'death_date' => '2010-10-09']);
        PeopleEloquentModel::factory()->create(['is_unavailable' => 0, 'death_date' => '2010-10-13']);
        PeopleEloquentModel::factory()->create(['is_unavailable' => 0, 'death_date' => '2010-10-01']);
        PeopleEloquentModel::factory()->create(['is_unavailable' => 0, 'death_date' => '2020-12-01']);
        PeopleEloquentModel::factory()->create(['is_unavailable' => 1, 'death_date' => '2020-10-17']);
        PeopleEloquentModel::factory()->create(['is_unavailable' => 1, 'death_date' => '2020-10-22']);
        PeopleEloquentModel::factory()->create(['is_unavailable' => 0, 'death_date' => '2020-10-15']);
        PeopleEloquentModel::factory()->create(['is_unavailable' => 0, 'death_date' => '2020-01-01']);
        PeopleEloquentModel::factory()->create(['is_unavailable' => 0, 'death_date' => '????-10-10']);
        PeopleEloquentModel::factory()->create(['is_unavailable' => 0, 'death_date' => '2020-10-??']);
        PeopleEloquentModel::factory()->create(['is_unavailable' => 0, 'death_date' => '']);
        PeopleEloquentModel::factory()->create(['is_unavailable' => 0, 'death_date' => null]);
        PeopleEloquentModel::factory()->create(['is_unavailable' => 0, 'death_date' => '2020-10-15']);
        PeopleEloquentModel::factory()->create(['is_unavailable' => 0, 'death_date' => '????-10-17']);
        PeopleEloquentModel::factory()->create(['is_unavailable' => 0, 'death_date' => '1979-12-28']);
        PeopleEloquentModel::factory()->create(['is_unavailable' => 0, 'death_date' => '1979-12-13']);
        PeopleEloquentModel::factory()->create(['is_unavailable' => 0, 'death_date' => '1988-01-04']);
        PeopleEloquentModel::factory()->create(['is_unavailable' => 0, 'death_date' => '1988-01-08']);
        PeopleEloquentModel::factory()->create(['is_unavailable' => 0, 'death_date' => '????-01-01']);
    }
}

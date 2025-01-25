<?php

namespace Tests\Feature\Helpers;

use App\Helpers\Person as PersonHelper;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

final class PersonTest extends TestCase
{
    #[DataProvider('surnameStringProvider')]
    public function test_surname_string($value): void
    {
        $this->assertEquals($value, PersonHelper::surname($value));
    }

    /**
     * @return array[]
     */
    public static function surnameStringProvider(): array
    {
        return [
            ['Ivanov'],
            ['Petrov'],
            ['Sidorov'],
        ];
    }

    public function test_surname_empty(): void
    {
        $this->assertEquals(__('person.surname.null'), PersonHelper::surname(''));
        $this->assertEquals(__('person.surname.null'), PersonHelper::surname(null));
    }

    #[DataProvider('oldSurnameStringProvider')]
    public function test_old_surname_string($expected, $actual): void
    {
        $this->assertEquals($expected, PersonHelper::oldSurname($actual));
    }

    /**
     * @return array[]
     */
    public static function oldSurnameStringProvider(): array
    {
        return [
            ['(Ivanov)', collect(['Ivanov'])],
            ['(Ivanov,Petrov)', collect(['Ivanov', 'Petrov'])],
            ['', collect([])],
            ['', null],
        ];
    }

    #[DataProvider('nameStringProvider')]
    public function test_name_string($value): void
    {
        $this->assertIsString(PersonHelper::surname($value));
        $this->assertEquals($value, PersonHelper::surname($value));
    }

    /**
     * @return array[]
     */
    public static function nameStringProvider(): array
    {
        return [
            ['Den'],
            ['Maksim'],
            ['Ivan'],
        ];
    }

    public function test_name_empty(): void
    {
        $this->assertEquals(__('person.name.null'), PersonHelper::name(''));
        $this->assertEquals(__('person.name.null'), PersonHelper::name(null));
    }

    #[DataProvider('patronymicStringProvider')]
    public function test_patronymic_string($value): void
    {
        $this->assertIsString(PersonHelper::surname($value));
        $this->assertEquals($value, PersonHelper::surname($value));
    }

    /**
     * @return array[]
     */
    public static function patronymicStringProvider(): array
    {
        return [
            ['Maksimovich'],
            ['Ivanovich'],
            ['Petrovich'],
        ];
    }

    public function test_patronymic_empty(): void
    {
        $this->assertEquals('', PersonHelper::patronymic(''));
    }

    public function test_patronymic_null(): void
    {
        $this->assertEquals(__('person.patronymic.null'), PersonHelper::patronymic(null));
    }

    #[DataProvider('patronymicEditProvider')]
    public function test_patronymic_edit(?string $expectedParam, string $expectedReturn): void
    {
        $this->assertEquals($expectedReturn, PersonHelper::patronymicEdit($expectedParam));
    }

    /**
     * @return array[]
     */
    public static function patronymicEditProvider(): array
    {
        return [
            [null, ''],
            ['', '!'],
            ['Petrovich', 'Petrovich'],
        ];
    }
}

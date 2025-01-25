<?php

namespace App\Models;

use App\Exceptions\DateException;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'date',
    title: 'Дата.',
    required: [
        'hasUnknown',
        'isEmpty',
    ]
)]
final readonly class Date
{
    #[OA\Property(
        description: 'Является ли дата неизестной. '
            .'Дата неизвестна, если год или месяц или день, '
            .'имеют хотя бы одну известную цифру. '
            .'Неизвестные данные будут заменены на ? (вопросительный знак). '
    )]
    public bool $hasUnknown;

    #[OA\Property(
        description: 'Является ли дата пустой. '
            .'Дата пустая, если год и месяц и день пустые '
            .'(проверка каждого элемента на empty() == true).'
    )]
    public bool $isEmpty;

    #[OA\Property(
        description: 'Строка даты в формате YYYY-MM-DD.'
    )]
    public ?string $string;

    /**
     * @throws DateException
     */
    public function __construct(
        #[OA\Property(
            description: 'Год.'
        )]
        public ?string $year,

        #[OA\Property(
            description: 'Месяц.'
        )]
        public ?string $month,

        #[OA\Property(
            description: 'День.'
        )]
        public ?string $day
    ) {
        if (! self::check($year, $month, $day)) {
            throw new DateException('Invalid date string');
        }

        $this->setHasUnknown();
        $this->setIsEmpty();
        $this->setString();
    }

    /**
     * @throws DateException
     */
    public static function decode(?string $text): ?self
    {
        if ($text === null) {
            return null;
        }

        if ($text === '') {
            return new Date(null, null, null);
        }

        $array = explode('-', $text);

        if (count($array) != 3) {
            throw new DateException('Invalid date string');
        }

        return new Date(
            ($array[0] === '????') ? null : $array[0],
            ($array[1] === '??') ? null : $array[1],
            ($array[2] === '??') ? null : $array[2],
        );
    }

    public static function encode(?Date $date): ?string
    {
        if ($date === null) {
            return null;
        }

        if (($date->year === null) && ($date->month === null) && ($date->day === null)) {
            return '';
        }

        if (($date->year === '????') && ($date->month === '??') && ($date->day === '??')) {
            return '';
        }

        return (($date->year === null) ? '????' : $date->year)
            .'-'
            .(($date->month === null) ? '??' : $date->month)
            .'-'
            .(($date->day === null) ? '??' : $date->day);
    }

    public static function checkText(string $text): bool
    {
        if ($text === '') {
            return false;
        }

        $array = explode('-', $text);

        if (count($array) != 3) {
            return false;
        }

        return self::check($array[0], $array[1], $array[2]);
    }

    public static function check(?string $year, ?string $month, ?string $day): bool
    {
        if ($year !== null) {
            if (strlen($year) !== 4) {
                return false;
            }

            if (! in_array($year[0], [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, '?'])) {
                return false;
            }

            if (! in_array($year[1], [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, '?'])) {
                return false;
            }

            if (! in_array($year[2], [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, '?'])) {
                return false;
            }

            if (! in_array($year[3], [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, '?'])) {
                return false;
            }
        }

        if ($month !== null) {
            if (strlen($month) !== 2) {
                return false;
            }

            if (! in_array($month[0], [0, 1, '?'])) {
                return false;
            }

            if (! in_array($month[1], [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, '?'])) {
                return false;
            }

            if (! str_contains($month, '?')) {
                if ((int) $month > 12 || (int) $month < 1) {
                    return false;
                }
            }
        }

        if ($day !== null) {
            if (strlen($day) !== 2) {
                return false;
            }

            if (! in_array($day[0], [0, 1, 2, 3, '?'])) {
                return false;
            }

            if (! in_array($day[1], [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, '?'])) {
                return false;
            }

            if (! str_contains($day, '?')) {
                if ((int) $day > 31 || (int) $day < 1) {
                    return false;
                }
            }
        }

        if ($month === null || $day === null) {
            return true;
        }

        if ($year !== null && ! str_contains($year, '?')) {
            if (! str_contains($month, '?') && ! str_contains($day, '?')) {
                if (! checkdate($month, $day, $year)) {
                    return false;
                }
            }
        } else {
            if (! str_contains($month, '?') && ! str_contains($day, '?')) {
                if (! checkdate($month, $day, 2020)) {
                    return false;
                }
            }
        }

        return true;
    }

    private function setHasUnknown(): void
    {
        if (empty($this->year) || empty($this->month) || empty($this->day)) {
            $this->hasUnknown = true;
        } elseif (str_contains($this->year, '?') || str_contains($this->month, '?') || str_contains($this->day, '?')) {
            $this->hasUnknown = true;
        } else {
            $this->hasUnknown = false;
        }
    }

    private function setIsEmpty(): void
    {
        if (empty($this->year) && empty($this->month) && empty($this->day)) {
            $this->isEmpty = true;
        } else {
            $this->isEmpty = false;
        }
    }

    private function setString(): void
    {
        $this->string = self::encode($this);
    }
}

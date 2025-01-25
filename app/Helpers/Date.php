<?php

namespace App\Helpers;

final class Date
{
    public static function getBirth(?string $date, ?\DateInterval $age, bool $isLife): string
    {
        if ($date === null || $date === '') {
            return '';
        } else {
            $str = self::format($date);
            if ($isLife && $age !== null) {
                $str .= ' '.__('person.birth_date.age', [/* @phpstan-ignore-line */
                    'age' => self::dateInterval($age),
                ]);
            }

            return $str;
        }
    }

    public static function getDeath(?string $date, ?\DateInterval $age, ?\DateInterval $interval): string
    {
        if ($date === null || $date === '') {
            return '';
        } else {
            $str = self::format($date);
            if ($interval !== null) {
                $str .= ' ';
                if ($age === null) {
                    $str .= __('person.death_date.interval.short', [/* @phpstan-ignore-line */
                        'death' => self::dateInterval($interval),
                    ]);
                } else {
                    $str .= __('person.death_date.interval.long', [/* @phpstan-ignore-line */
                        'death' => self::dateInterval($interval),
                        'age' => self::dateInterval($age),
                    ]);
                }
            }

            return $str;
        }
    }

    public static function periodLive(?string $birthDate, ?string $deathDate): string
    {
        $str = '(';

        if (empty($birthDate)) {
            $str .= '?';
        } else {
            $str .= self::format($birthDate);
        }
        if ($deathDate !== null) {
            $str .= '-';
            if ($deathDate === '') {
                $str .= '?';
            } else {
                $str .= self::format($deathDate);
            }
        }

        $str .= ')';

        return $str;
    }

    /**
     * @throws \Exception
     */
    public static function format(?string $date): string
    {
        if ($date === null || $date === '') {
            return '';
        }

        $pattern = "#[0-9\?]{4}-([0\?]{1}[1-9\?]{1}|[1\?]{1}[012\?]{1})-([0-2\?]{1}[0-9\?]{1}|[3\?]{1}[01\?]{1})#";
        $message = 'invalid date format, the date must strictly match the format:
                yyyy-mm-dd, if there is no digit, it is replaced with a question mark: ?';
        if (! preg_match($pattern, $date)) {
            throw new \Exception($message);
        }

        $array = explode('-', $date);

        return $array[2].'.'.$array[1].'.'.$array[0];
    }

    public static function dateInterval(\DateInterval $date): string
    {
        if ($date->y != 0) {
            $case = self::getCase($date->y);

            return $date->y.' '.__("date.year.$case"); /* @phpstan-ignore-line */
        } elseif ($date->m != 0) {
            $case = self::getCase($date->m);

            return $date->m.' '.__("date.month.$case"); /* @phpstan-ignore-line */
        } else {
            $case = self::getCase($date->d);

            return $date->d.' '.__("date.day.$case"); /* @phpstan-ignore-line */
        }
    }

    private static function getCase(int $interval): string
    {
        $interval = (string) $interval;
        if (strlen($interval) > 2) {
            $interval = $interval[strlen($interval) - 2].$interval[strlen($interval) - 1];
        }

        if ($interval > 4 && $interval < 21) {
            return 'plural';
        } else {
            $s = $interval[strlen($interval) - 1];
            if ($s == 1) {
                return 'nominative';
            } elseif ($s >= 2 && $s <= 4) {
                return 'accusative';
            } else {
                return 'plural';
            }
        }
    }
}

<?php

namespace App\Models;

final class DateTimeCustom extends \DateTime
{
    private static $mockNow;

    public function __construct($time = 'now', $timezone = null)
    {
        if (self::$mockNow !== null) {
            parent::__construct(self::$mockNow, $timezone);
        } else {
            parent::__construct($time, $timezone);
        }
    }

    public static function setMockNow($time)
    {
        self::$mockNow = $time;
    }

    public static function resetMockNow()
    {
        self::$mockNow = null;
    }
}

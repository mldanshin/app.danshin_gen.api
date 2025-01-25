<?php

namespace App\Repositories\Dates;

use App\Models\CalculatorDateInterval;
use App\Models\Date;
use App\Models\Dates\Birth as BirthModel;
use App\Models\Dates\BirthWould as BirthWouldModel;
use App\Models\Dates\Death as DeathModel;
use App\Models\Dates\Event as EventModel;
use App\Models\Dates\Events as EventsModel;
use App\Models\Dates\Person as PersonModel;
use Illuminate\Support\Collection;

final class DatesUpcoming
{
    private EventsModel $events;

    private Dirty $dirty;

    private \DateTime $dateNearest;

    private \DateTime $dateToday;

    public function get(\DateTime $date, int $pastDay, int $nearestDay): EventsModel
    {
        $this->dateToday = $date;
        $this->initialize($pastDay, $nearestDay);

        return $this->events;
    }

    private function initialize(int $pastDay, int $nearestDay): void
    {
        $pastBirth = collect();
        $pastBirthWould = collect();
        $pastDeath = collect();
        $todayBirth = collect();
        $todayBirthWould = collect();
        $todayDeath = collect();
        $nearestBirth = collect();
        $nearestBirthWould = collect();
        $nearestDeath = collect();

        $duration = 'P'.$nearestDay.'D';
        $today = new \DateTime($this->dateToday->format('Y-m-d H:i:s'));
        $this->dateNearest = $today->add(new \DateInterval($duration));

        $this->dirty = new Dirty(
            $pastDay,
            $this->dateToday,
            $nearestDay
        );

        $this->setBirth('getPastBirth', $pastBirth, $pastBirthWould);
        $this->setDeath('getPastDeath', $pastDeath);

        $this->setBirth('getTodayBirth', $todayBirth, $todayBirthWould);
        $this->setDeath('getTodayDeath', $todayDeath);

        $this->setBirth('getNearestBirth', $nearestBirth, $nearestBirthWould);
        $this->setDeath('getNearestDeath', $nearestDeath);

        $this->events = new EventsModel(
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
    }

    /**
     * @param  Collection<int, BirthModel>  $birth
     * @param  Collection<int, BirthWouldModel>  $birthWould
     */
    private function setBirth(
        string $funcName,
        Collection &$birth,
        Collection &$birthWould
    ): void {
        $dirtyCollection = $this->dirty->$funcName();
        foreach ($dirtyCollection as $item) {
            $person = new PersonModel(
                $item->id,
                $item->surname,
                ($item->oldSurname()->count() > 0) ? $item->oldSurname()->orderBy('order')->pluck('surname') : null,
                $item->name,
                $item->patronymic
            );
            $calculate = new CalculatorDateInterval(
                $this->dateNearest,
                Date::decode($item->birth_date),
                Date::decode($item->death_date)
            );

            if ($calculate->age !== null && $calculate->intervalDeath === null) {
                $birth->push(
                    new BirthModel(
                        Date::decode($item->birth_date),
                        $person,
                        $calculate->age
                    )
                );
            } elseif ($calculate->intervalBirth !== null) {
                $birthWould->push(
                    new BirthWouldModel(
                        Date::decode($item->birth_date),
                        $person,
                        $calculate->intervalBirth
                    )
                );
            }
        }
    }

    /**
     * @param  Collection<int, DeathModel>  $death
     */
    private function setDeath(string $funcName, Collection &$death): void
    {
        $dirtyCollection = $this->dirty->$funcName();
        foreach ($dirtyCollection as $item) {
            $calculate = new CalculatorDateInterval(
                $this->dateNearest,
                Date::decode($item->birth_date),
                Date::decode($item->death_date)
            );
            $interval = $calculate->intervalDeath;
            if ($interval !== null) {
                $death->push(
                    new DeathModel(
                        Date::decode($item->death_date),
                        new PersonModel(
                            $item->id,
                            $item->surname,
                            ($item->oldSurname()->count() > 0) ? $item->oldSurname()->orderBy('order')->pluck('surname') : null,
                            $item->name,
                            $item->patronymic
                        ),
                        $calculate->age,
                        $interval
                    )
                );
            }
        }
    }

    /**
     * @param  EventModel[]  $array
     * @return EventModel[]
     */
    private function sort(array $array): array
    {
        $func = function ($item1, $item2) {
            $date1 = substr($item1->date->string, 5);
            $date2 = substr($item2->date->string, 5);

            if (preg_match("#^12-\d\d#", $date1)) {
                return 0;
            } else {
                if ($date1 > $date2) {
                    return 1;
                } else {
                    return 0;
                }
            }
        };

        usort($array, $func);

        return $array;
    }
}

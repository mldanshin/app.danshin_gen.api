<?php

namespace App\Repositories\Dates;

use App\Models\CalculatorDateInterval;
use App\Models\Date;
use App\Models\Dates\Birth as BirthModel;
use App\Models\Dates\BirthWould as BirthWouldModel;
use App\Models\Dates\Death as DeathModel;
use App\Models\Dates\Events as EventsModel;
use App\Models\Dates\Person as PersonModel;
use Illuminate\Support\Collection;

final class DatesUpcoming
{
    private EventsModel $events;

    private Dirty $dirty;

    private \DateTime $dateBefore;

    private \DateTime $dateToday;

    public function get(\DateTime $date, int $beforeDay, int $afterDay): EventsModel
    {
        $this->dateToday = $date;
        $this->initialize($beforeDay, $afterDay);

        return $this->events;
    }

    private function initialize(int $beforeDay, int $afterDay): void
    {
        $beforeBirth = collect();
        $beforeBirthWould = collect();
        $beforeDeath = collect();
        $todayBirth = collect();
        $todayBirthWould = collect();
        $todayDeath = collect();
        $afterBirth = collect();
        $afterBirthWould = collect();
        $afterDeath = collect();

        $duration = 'P'.$beforeDay.'D';
        $today = new \DateTime($this->dateToday->format('Y-m-d H:i:s'));
        $this->dateBefore = $today->add(new \DateInterval($duration));

        $this->dirty = new Dirty(
            $beforeDay,
            $this->dateToday,
            $afterDay  
        );

        $this->setBirth('getBeforeBirth', $beforeBirth, $beforeBirthWould);
        $this->setDeath('getBeforeDeath', $beforeDeath);

        $this->setBirth('getTodayBirth', $todayBirth, $todayBirthWould);
        $this->setDeath('getTodayDeath', $todayDeath);

        $this->setBirth('getAfterBirth', $afterBirth, $afterBirthWould);
        $this->setDeath('getAfterDeath', $afterDeath);

        $this->events = new EventsModel(
            $beforeBirth,
            $beforeBirthWould,
            $beforeDeath,
            $todayBirth,
            $todayBirthWould,
            $todayDeath,
            $afterBirth,
            $afterBirthWould,
            $afterDeath,
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
                $this->dateBefore,
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
                $this->dateBefore,
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
}

<?php

namespace App\Repositories\Dates;

use App\Models\Eloquent\People as PeopleEloquent;
use Illuminate\Support\Collection;

final class Dirty
{
    private string $past;

    private string $today;

    private string $nearest;

    public function __construct(int $pastDay, \DateTime $today, int $nearestDay)
    {
        $this->today = $today->format('m-d');

        $this->validate($pastDay, $nearestDay);

        $this->initialize($pastDay, $today, $nearestDay);
    }

    /**
     * @return Collection<int, PeopleEloquent>
     */
    public function getPastBirth(): Collection
    {
        return PeopleEloquent::select('id', 'surname', 'name', 'patronymic', 'birth_date', 'death_date')
            ->whereNotNull('birth_date')
            ->where('birth_date', '<>', '')
            ->whereRaw("birth_date NOT LIKE '%?%'")
            ->get()
            ->filter(function ($item) {
                $date = substr($item->birth_date, 5);
                if (
                    ($this->today > $this->past && $date < $this->today && $date >= $this->past)
                    || ($this->today < $this->past && ($date < $this->today || $date > $this->past))
                ) {
                    return $item;
                }
            });
    }

    /**
     * @return Collection<int, PeopleEloquent>
     */
    public function getPastDeath(): Collection
    {
        return PeopleEloquent::select('id', 'surname', 'name', 'patronymic', 'birth_date', 'death_date')
            ->whereNotNull('death_date')
            ->where('death_date', '<>', '')
            ->whereRaw("death_date NOT LIKE '%?%'")
            ->get()
            ->filter(function ($item) {
                $date = substr($item->death_date, 5);
                if (
                    ($this->today > $this->past && $date < $this->today && $date >= $this->past)
                    || ($this->today < $this->past && ($date < $this->today || $date > $this->past))
                ) {
                    return $item;
                }
            });
    }

    /**
     * @return Collection<int, PeopleEloquent>
     */
    public function getTodayBirth(): Collection
    {
        return PeopleEloquent::select('id', 'surname', 'name', 'patronymic', 'birth_date', 'death_date')
            ->whereNotNull('birth_date')
            ->whereRaw("birth_date NOT LIKE '%?%'")
            ->where('birth_date', 'like', "____-{$this->today}")
            ->get();
    }

    /**
     * @return Collection<int, PeopleEloquent>
     */
    public function getTodayDeath(): Collection
    {
        return PeopleEloquent::select('id', 'surname', 'name', 'patronymic', 'birth_date', 'death_date')
            ->whereNotNull('death_date')
            ->whereRaw("death_date NOT LIKE '%?%'")
            ->where('death_date', 'like', "____-{$this->today}")
            ->get();
    }

    /**
     * @return Collection<int, PeopleEloquent>
     */
    public function getNearestBirth(): Collection
    {
        return PeopleEloquent::select('id', 'surname', 'name', 'patronymic', 'birth_date', 'death_date')
            ->whereNotNull('birth_date')
            ->where('birth_date', '<>', '')
            ->whereRaw("birth_date NOT LIKE '%?%'")
            ->get()
            ->filter(function ($item) {
                $date = substr($item->birth_date, 5);
                if (
                    ($this->today < $this->nearest && $date > $this->today && $date <= $this->nearest)
                    || ($this->today > $this->nearest && ($date > $this->today || $date < $this->nearest))
                ) {
                    return $item;
                }
            });
    }

    /**
     * @return Collection<int, PeopleEloquent>
     */
    public function getNearestDeath(): Collection
    {
        return PeopleEloquent::select('id', 'surname', 'name', 'patronymic', 'birth_date', 'death_date')
            ->whereNotNull('death_date')
            ->where('death_date', '<>', '')
            ->whereRaw("death_date NOT LIKE '%?%'")
            ->get()
            ->filter(function ($item) {
                $date = substr($item->death_date, 5);
                if (
                    ($this->today < $this->nearest && $date > $this->today && $date <= $this->nearest)
                    || ($this->today > $this->nearest && ($date > $this->today || $date < $this->nearest))
                ) {
                    return $item;
                }
            });
    }

    private function initialize(int $pastDay, \DateTime $today, int $nearestDay): void
    {
        $todayCopy = clone $today;
        $this->past = $today->sub(new \DateInterval("P{$pastDay}D"))->format('m-d');
        $this->nearest = $todayCopy->add(new \DateInterval("P{$nearestDay}D"))->format('m-d');
    }

    /**
     * @throws \Exception
     */
    private function validate(int $pastDay, int $nearestDay): void
    {
        if ($pastDay < 1 || $pastDay > 30) {
            throw new \Exception('past day must be an integer from 1 to 30');
        }

        if ($nearestDay < 1 || $nearestDay > 30) {
            throw new \Exception('past day must be an integer from 1 to 30');
        }
    }
}

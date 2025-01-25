<?php

namespace App\Repositories\Dates;

use App\Models\Eloquent\People as PeopleEloquent;
use Illuminate\Support\Collection;

final class Dirty
{
    private string $before;
    private string $today;
    private string $after;


    public function __construct(int $beforeDay, \DateTime $today, int $afterDay)
    {
        $this->today = $today->format('m-d');

        $this->validate($beforeDay, $afterDay);

        $this->initialize($beforeDay, $today, $afterDay);
    }

    /**
     * @return Collection<int, PeopleEloquent>
     */
    public function getBeforeBirth(): Collection
    {
        return PeopleEloquent::select('id', 'surname', 'name', 'patronymic', 'birth_date', 'death_date')
            ->whereNotNull('birth_date')
            ->where('birth_date', '<>', '')
            ->whereRaw("birth_date NOT LIKE '%?%'")
            ->get()
            ->filter(function ($item) {
                $date = substr($item->birth_date, 5);
                if (
                    ($this->today > $this->after && $date < $this->today && $date >= $this->after)
                    || ($this->today < $this->after && ($date < $this->today || $date > $this->after))
                ) {
                    return $item;
                }
            });
    }

    /**
     * @return Collection<int, PeopleEloquent>
     */
    public function getBeforeDeath(): Collection
    {
        return PeopleEloquent::select('id', 'surname', 'name', 'patronymic', 'birth_date', 'death_date')
            ->whereNotNull('death_date')
            ->where('death_date', '<>', '')
            ->whereRaw("death_date NOT LIKE '%?%'")
            ->get()
            ->filter(function ($item) {
                $date = substr($item->death_date, 5);
                if (
                    ($this->today > $this->after && $date < $this->today && $date >= $this->after)
                    || ($this->today < $this->after && ($date < $this->today || $date > $this->after))
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
    public function getAfterBirth(): Collection
    {
        return PeopleEloquent::select('id', 'surname', 'name', 'patronymic', 'birth_date', 'death_date')
            ->whereNotNull('birth_date')
            ->where('birth_date', '<>', '')
            ->whereRaw("birth_date NOT LIKE '%?%'")
            ->get()
            ->filter(function ($item) {
                $date = substr($item->birth_date, 5);
                if (
                    ($this->today < $this->before && $date > $this->today && $date <= $this->before)
                    || ($this->today > $this->before && ($date > $this->today || $date < $this->before))
                ) {
                    return $item;
                }
            });
    }

    /**
     * @return Collection<int, PeopleEloquent>
     */
    public function getAfterDeath(): Collection
    {
        return PeopleEloquent::select('id', 'surname', 'name', 'patronymic', 'birth_date', 'death_date')
            ->whereNotNull('death_date')
            ->where('death_date', '<>', '')
            ->whereRaw("death_date NOT LIKE '%?%'")
            ->get()
            ->filter(function ($item) {
                $date = substr($item->death_date, 5);
                if (
                    ($this->today < $this->before && $date > $this->today && $date <= $this->before)
                    || ($this->today > $this->before && ($date > $this->today || $date < $this->before))
                ) {
                    return $item;
                }
            });
    }

    private function initialize(int $beforeDay, \DateTime $today, int $afterDay): void
    {
        $todayCopy = clone $today;
        $this->before = $todayCopy->add(new \DateInterval("P{$beforeDay}D"))->format('m-d');
        $this->after = $today->sub(new \DateInterval("P{$afterDay}D"))->format('m-d');
    }

    /**
     * @throws \Exception
     */
    private function validate(int $beforeDay, int $afterDay): void
    {
        if ($beforeDay < 1 || $beforeDay > 30) {
            throw new \Exception('$before day must be an integer from 1 to 30');
        }

        if ($afterDay < 1 || $afterDay > 30) {
            throw new \Exception('after day must be an integer from 1 to 30');
        }
    }
}

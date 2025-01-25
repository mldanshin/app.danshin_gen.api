<?php

namespace App\Models;

final class CalculatorDateInterval
{
    public readonly ?\DateInterval $age;

    public readonly ?\DateInterval $intervalBirth;

    public readonly ?\DateInterval $intervalDeath;

    public function __construct(\DateTime $today, ?Date $birthDate, ?Date $deathDate)
    {
        $this->initialize($today, $birthDate, $deathDate);
    }

    /**
     * @throws \Exception
     */
    private function initialize(\DateTime $today, ?Date $birthDate, ?Date $deathDate): void
    {
        $todayFormat = $today->format('Y-m-d');

        if ($birthDate === null || $birthDate->hasUnknown) {
            $this->age = null;
            $this->intervalBirth = null;
        } else {
            if ($birthDate->string > $todayFormat) {
                throw new \Exception('The date of birth cannot be earlier than the current date');
            }

            if ($deathDate === null) {
                $this->age = $this->diffDate(new \DateTime($birthDate->string), $today);
            } elseif ($deathDate->hasUnknown) {
                $this->age = null;
            } else {
                if ($deathDate->string > $todayFormat) {
                    throw new \Exception('the date of death cannot be earlier than the current date');
                }
                if ($birthDate->string > $deathDate->string) {
                    throw new \Exception('the date of birth cannot be earlier than the date of death');
                }
                $this->age = $this->diffDate(
                    new \DateTime($birthDate->string),
                    new \DateTime($deathDate->string)
                );
            }

            $this->intervalBirth = $this->diffDate(new \DateTime($birthDate->string), $today);
        }

        if (empty($deathDate) || $deathDate->hasUnknown) {
            $this->intervalDeath = null;
        } else {
            if ($deathDate->string > $todayFormat) {
                throw new \Exception('the date of death cannot be earlier than the current date');
            }
            $this->intervalDeath = $this->diffDate(new \DateTime($deathDate->string), $today);
        }
    }

    private function diffDate(\DateTime $dateStart, \DateTime $dateEnd): \DateInterval
    {
        return $dateEnd->diff($dateStart);
    }
}

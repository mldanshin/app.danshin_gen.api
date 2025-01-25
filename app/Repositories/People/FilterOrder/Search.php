<?php

namespace App\Repositories\People\FilterOrder;

use Illuminate\Database\Eloquent\Builder;

final class Search
{
    public function getBuilder(?string $text, Builder $builder): Builder
    {
        $lines = $this->explode($text);

        return $this->build($builder, $lines);
    }

    /**
     * @return string[]
     */
    private function explode(?string $text): array
    {
        $linesDirty = explode(' ', trim($text));

        $lines = [];

        foreach ($linesDirty as $item) {
            if (! empty($item)) {
                $lines[] = $item;
            }
        }

        return $lines;
    }

    /**
     * @param  string[]  $lines
     */
    private function build(Builder $builder, array $lines): Builder
    {
        if (count($lines) < 1 || count($lines) > 3) {
            return $builder;
        } elseif (count($lines) === 1) {
            return $builder->where('surname', 'like', "%{$lines[0]}%")
                ->orWhere('name', 'like', "%{$lines[0]}%")
                ->orWhere('patronymic', 'like', "%{$lines[0]}%")
                ->orWhereHas('oldSurname', function (Builder $query) use ($lines) {
                    $query->where('surname', 'like', "%{$lines[0]}%");
                });
        } elseif (count($lines) === 2) {
            return $builder
                ->where(function (Builder $query) use ($lines) {
                    $query->where('surname', 'like', "%{$lines[0]}%")
                        ->where('name', 'like', "%{$lines[1]}%");
                })
                ->orWhere(function (Builder $query) use ($lines) {
                    $query->where('name', 'like', "%{$lines[1]}%")
                        ->whereHas('oldSurname', function (Builder $query) use ($lines) {
                            $query->where('surname', 'like', "%{$lines[0]}%");
                        });
                })
                ->orWhere(function (Builder $query) use ($lines) {
                    $query->where('name', 'like', "%{$lines[0]}%")
                        ->where('patronymic', 'like', "%{$lines[1]}%");
                });
        } elseif (count($lines) === 3) {
            return $builder->where('surname', 'like', "%{$lines[0]}%")
                ->where('name', 'like', "%{$lines[1]}%")
                ->where('patronymic', 'like', "%{$lines[2]}%");
        }
    }
}

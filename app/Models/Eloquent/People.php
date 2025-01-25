<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property bool $is_unavailable
 * @property int $gender_id
 * @property string|null $surname
 * @property string|null $name
 * @property string|null $patronymic
 * @property string|null $birth_date
 * @property string|null $birth_place
 * @property string|null $death_date
 * @property string|null $burial_place
 * @property string|null $note
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * строка с датой должна быть null или
 * соответствовать формату гггг-мм-дд,
 * другие форматы не допустимы,
 * не известная цифра заменяется вопросительным знаком
 */
final class People extends Model
{
    use HasFactory;

    public function gender(): BelongsTo
    {
        return $this->belongsTo(Gender::class);
    }

    public function oldSurname(): HasMany
    {
        return $this->hasMany(OldSurname::class, 'person_id');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class, 'person_id');
    }

    public function emails(): HasMany
    {
        return $this->hasMany(Email::class, 'person_id');
    }

    public function internet(): HasMany
    {
        return $this->hasMany(Internet::class, 'person_id');
    }

    public function phones(): HasMany
    {
        return $this->hasMany(Phone::class, 'person_id');
    }

    public function residences(): HasMany
    {
        return $this->hasMany(Residence::class, 'person_id');
    }

    public function childrens(): HasMany
    {
        return $this->hasMany(ParentChild::class, 'parent_id');
    }

    public function parents(): HasMany
    {
        return $this->hasMany(ParentChild::class, 'child_id');
    }

    public function parentsPerson(): BelongsToMany
    {
        return $this->belongsToMany(People::class, 'parent_child', 'child_id', 'parent_id');
    }

    public function photo(): HasMany
    {
        return $this->hasMany(Photo::class, 'person_id');
    }
}

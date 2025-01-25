<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $slug
 */
final class Gender extends Model
{
    use HasFactory;

    /**
     * @var bool
     */
    public $timestamps = false;

    public function people(): HasMany
    {
        return $this->hasMany(People::class, 'gender_id');
    }

    public function marriages(): BelongsToMany
    {
        return $this->belongsToMany(MarriageRole::class, 'marriage_role_gender', 'gender_id', 'role_id');
    }

    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(ParentRole::class, 'parent_role_gender', 'gender_id', 'parent_id');
    }
}

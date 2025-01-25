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
final class MarriageRole extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'marriage_roles';

    /**
     * @var bool
     */
    public $timestamps = false;

    public function genders(): BelongsToMany
    {
        return $this->belongsToMany(Gender::class, 'marriage_role_gender', 'role_id', 'gender_id');
    }

    public function scope1(): HasMany
    {
        return $this->hasMany(MarriageRoleScope::class, 'role1_id');
    }

    public function scope2(): HasMany
    {
        return $this->hasMany(MarriageRoleScope::class, 'role2_id');
    }
}

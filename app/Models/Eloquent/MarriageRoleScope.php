<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $role1_id
 * @property int $role2_id
 */
final class MarriageRoleScope extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'marriage_role_scope';

    /**
     * @var bool
     */
    public $timestamps = false;

    public function role1(): BelongsTo
    {
        return $this->belongsTo(MarriageRole::class, 'role1_id');
    }

    public function role2(): BelongsTo
    {
        return $this->belongsTo(MarriageRole::class, 'role2_id');
    }
}

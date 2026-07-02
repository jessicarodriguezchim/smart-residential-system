<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'first_name', 'last_name', 'phone', 'email', 'status'])]
class Owner extends Model
{
    use HasFactory;

    /**
     * User account associated with the owner.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Lots owned by this owner.
     */
    public function lots(): HasMany
    {
        return $this->hasMany(Lot::class);
    }

    /**
     * Documents belonging to the owner.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(OwnerDocument::class);
    }

    /**
     * Maintenance fees billed to this owner.
     */
    public function maintenanceFees(): HasMany
    {
        return $this->hasMany(MaintenanceFee::class);
    }
}

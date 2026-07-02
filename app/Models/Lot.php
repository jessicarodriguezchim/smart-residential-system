<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['owner_id', 'number', 'street', 'surface_area', 'status', 'notes'])]
class Lot extends Model
{
    use HasFactory;

    /**
     * The owner of the lot.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(Owner::class);
    }

    /**
     * Visits hosted by this lot.
     */
    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }

    /**
     * Maintenance fees registered to this lot.
     */
    public function maintenanceFees(): HasMany
    {
        return $this->hasMany(MaintenanceFee::class);
    }
}

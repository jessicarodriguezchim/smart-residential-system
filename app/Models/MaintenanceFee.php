<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['lot_id', 'owner_id', 'amount', 'penalty_amount', 'month', 'year', 'due_date', 'status', 'notes'])]
class MaintenanceFee extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'amount' => 'decimal:2',
            'penalty_amount' => 'decimal:2',
        ];
    }

    /**
     * The lot that this maintenance fee is associated with.
     */
    public function lot(): BelongsTo
    {
        return $this->belongsTo(Lot::class);
    }

    /**
     * The owner who owes this fee.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(Owner::class);
    }

    /**
     * Payments associated with this fee.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Penalties/late fees associated with this fee.
     */
    public function penalties(): HasMany
    {
        return $this->hasMany(Penalty::class);
    }
}

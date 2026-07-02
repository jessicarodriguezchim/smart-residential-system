<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['maintenance_fee_id', 'amount', 'reason', 'applied_at', 'status'])]
class Penalty extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'applied_at' => 'date',
            'amount' => 'decimal:2',
        ];
    }

    /**
     * The maintenance fee this penalty was applied to.
     */
    public function maintenanceFee(): BelongsTo
    {
        return $this->belongsTo(MaintenanceFee::class);
    }
}

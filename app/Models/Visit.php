<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'lot_id',
    'visitor_name',
    'visitor_id_number',
    'vehicle_plate',
    'entry_registered_by',
    'exit_registered_by',
    'entry_at',
    'exit_at',
    'qr_code',
    'status',
    'notes'
])]
class Visit extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'entry_at' => 'datetime',
            'exit_at' => 'datetime',
        ];
    }

    /**
     * Lot being visited.
     */
    public function lot(): BelongsTo
    {
        return $this->belongsTo(Lot::class);
    }

    /**
     * User (vigilante) who registered the entry.
     */
    public function entryRegisteredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'entry_registered_by');
    }

    /**
     * User (vigilante) who registered the exit.
     */
    public function exitRegisteredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'exit_registered_by');
    }
}

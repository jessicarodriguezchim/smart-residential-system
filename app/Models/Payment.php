<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['maintenance_fee_id', 'amount', 'payment_method', 'transaction_id', 'payment_date', 'status', 'receipt_path', 'registered_by'])]
class Payment extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'payment_date' => 'datetime',
            'amount' => 'decimal:2',
        ];
    }

    /**
     * The maintenance fee associated with this payment.
     */
    public function maintenanceFee(): BelongsTo
    {
        return $this->belongsTo(MaintenanceFee::class);
    }

    /**
     * Admin user who registered/approved this payment.
     */
    public function registeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_by');
    }
}

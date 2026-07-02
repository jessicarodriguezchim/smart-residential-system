<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['owner_id', 'document_type', 'file_path', 'file_name', 'file_size', 'mime_type', 'verified_at'])]
class OwnerDocument extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'verified_at' => 'datetime',
        ];
    }

    /**
     * Owner associated with this document.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(Owner::class);
    }
}

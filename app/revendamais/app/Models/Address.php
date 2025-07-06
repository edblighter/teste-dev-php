<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Supplier;

class Address extends Model
{
    protected $table = 'addresses';

    protected $fillable = [
        'supplier_id', 'street', 'city', 'state', 'post_code', 'country'
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}

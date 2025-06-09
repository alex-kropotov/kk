<?php
declare(strict_types=1);

namespace App\Domain\Entity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyOwner extends Model
{
    protected $table = 'property_owners';

    protected $fillable = [
        'property_id',
        'first_name',
        'last_name',
        'phone',
        'email',
        'address',
        'contract_date',
        'notes'
    ];

    protected $casts = [
        'contract_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
}

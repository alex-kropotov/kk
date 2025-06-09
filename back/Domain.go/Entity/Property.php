<?php
declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Enum\PropertyType;
use App\Domain\Enum\DealType;
use App\Domain\Enum\PropertyStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Property extends Model
{
    protected $table = 'properties';

    protected $fillable = [
        'user_id',
        'code',
        'name',
        'type',
        'status',
        'deal_type',
        'price',
        'old_price',
        'currency',
        'city',
        'municipality',
        'address',
        'latitude',
        'longitude',
        'rooms',
        'bedrooms',
        'bathrooms',
        'area',
        'lot_area',
        'floors',
        'current_floor',
        'year_built',
        'garage',
        'parking',
        'elevator',
        'terrace',
        'basement',
        'attic',
        'heating',
        'cooling',
        'furnished',
        'security',
        'garden',
        'view_type',
        'last_renovation',
        'is_active',
        'view_count',
        'features'
    ];

    protected $casts = [
        'type' => PropertyType::class,
        'deal_type' => DealType::class,
        'status' => PropertyStatus::class,
        'is_active' => 'boolean',
        'heating' => 'boolean',
        'cooling' => 'boolean',
        'furnished' => 'boolean',
        'security' => 'boolean',
        'garden' => 'boolean',
        'elevator' => 'boolean',
        'terrace' => 'boolean',
        'basement' => 'boolean',
        'attic' => 'boolean',
        'garage' => 'boolean',
        'parking' => 'boolean',
        'features' => 'array',
        'price' => 'decimal:2',
        'old_price' => 'decimal:2',
        'area' => 'decimal:2',
        'lot_area' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(PropertyDetail::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(PropertyDocument::class);
    }

    public function owner(): HasOne
    {
        return $this->hasOne(PropertyOwner::class);
    }

    public function history(): HasMany
    {
        return $this->hasMany(PropertyHistory::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDetailByLocale(string $locale = 'sr'): ?PropertyDetail
    {
        return $this->details()->where('locale', $locale)->first();
    }
}

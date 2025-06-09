<?php
declare(strict_types=1);

namespace App\Domain\Entity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyDocument extends Model
{
    protected $table = 'property_documents';

    protected $fillable = [
        'property_id',
        'type',
        'file_path',
        'file_name',
        'title',
        'size',
        'mime_type',
        'is_public'
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'size' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
}

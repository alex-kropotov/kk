<?php
declare(strict_types=1);

namespace App\Domain\Entity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'username',
        'password_hash',
        'role',
        'agent_code'
    ];

    protected $hidden = [
        'password_hash'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }
}
